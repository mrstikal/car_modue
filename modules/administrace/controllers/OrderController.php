<?php

namespace app\modules\administrace\controllers;

use Yii;
use yii\web\Controller;
use app\modules\order\models\Booking;
use app\modules\car\models\Car;
use app\modules\common\components\Response;
use yii\helpers\Url;
use app\modules\user\models\User;
use app\modules\order\helpers\DatetimeHelper;
use app\modules\car\models\ServiceInfo;
use app\modules\order\models\Order;
use app\modules\administrace\traits\CalculationsTrait;
use app\modules\options\models\OptionsTable;
use app\modules\order\models\Invoice;
use app\modules\order\models\CashRegister;
use app\modules\order\helpers\OrderSaveHelper;
use app\modules\pdf\models\PdfModel;

/**
 * Performs order actions.
 */
class OrderController extends Controller
{
    use CalculationsTrait;

    public $layout = 'main.php';

    /**
     * Renders index page.
     *
     * @return View
     */
    public function actionIndex()
    {
        $model = Order::factory();

        $orders = $model->getAllOrders([], 1);

        //Left menu params
        $this->view->params['keyw'] = ['order', 'all'];
        $this->view->params['record_type'] = 'order';
        $this->view->params['view_type'] = 'list';

        //selectboxes params
        $this->view->params['statuses'] = Order::getOrderStatuses();
        $this->view->params['cars'] = Car::findForFilter();

        $this->view->params['records_count'] = $orders['count'];

        $this->view->params['can_load_more'] = $orders['can_load_more'];

        return $this->render('index', ['orders' => $orders['result']]);
    }

    /**
     * Renders new order page.
     *
     * @return View
     */
    public function actionNew()
    {
        $params = Yii::$app->request->queryParams;
        //find linked booking record
        $booking = Booking::findSingle($params['id']);

        //left menu params
        $this->view->params['keyw'] = ['order', 'new'];
        $this->view->params['view_type'] = 'new';

        //car takeover default params
        $this->view->params['place_take_over'] = OptionsTable::getOption('place_take_over');
        $this->view->params['place_return'] = OptionsTable::getOption('place_return');

        $this->view->params['time_take_over'] = OptionsTable::getOption('time_take_over');
        $this->view->params['time_return'] = OptionsTable::getOption('time_return');

        //selectboxes params
        $this->view->params['select_types'] = [
            'car' => Car::findForFilter(),
            'statuses' => Order::getOrderStatuses(),
            'payment_methods' => Order::getOrderPaymentMethods(),
            'customer_types' => Order::getCustomerTypes(),
            'operators' => User::getActiveOperators(),
        ];

        //default numberings for invoices and cash registers
        $this->view->params['numberings'] = $this->getInitPapersNumbers();

        //default bail value
        $this->view->params['bail_value'] = $this->getBailValue($booking['car_id'], $booking['use_rider'])['raw_price'];

        //default variable symbol base
        $this->view->params['variable_symbol_base'] = OptionsTable::getOption('variable_symbol_base', 0);

        return $this->render('new', ['booking' => $booking]);
    }

    /**
     * Renders order edit page.
     *
     * @return View
     */
    public function actionEdit()
    {
        $params = Yii::$app->request->queryParams;
        $order = Order::getCompleteOrder($params['id']);

        //left menu params
        $this->view->params['keyw'] = ['order', 'edit'];
        $this->view->params['view_type'] = 'edit';

        //selectboxes params
        $this->view->params['select_types'] = [
            'car' => Car::findForFilter(),
            'statuses' => Order::getOrderStatuses(),
            'payment_methods' => Order::getOrderPaymentMethods(),
            'customer_types' => Order::getCustomerTypes(),
            'operators' => User::getActiveOperators(),
        ];

        //is operator vat payer?
        $this->view->params['fin_dph'] = OptionsTable::getOption('fin_dph', 0);

        //default numberings for invoices and cash registers
        $this->view->params['numberings'] = $this->getInitPapersNumbers($order['id']);

        //default variable symbol base
        $this->view->params['variable_symbol_base'] = OptionsTable::getOption('variable_symbol_base', 0);

        return $this->render('edit', ['order' => $order]);
    }

    /**
     * Saves order params.
     * Due to complexity of process helper class is used.
     *
     * @return void
     */
    public function actionSave()
    {
        $helper = OrderSaveHelper::factory(Yii::$app->request->post());

        $response = Response::getResponseBase();

        $response->statusCode = 200;
        $response->data = '';

        if (empty(Yii::$app->request->post('id'))) {
            return $this->redirect(Url::base(true) . '/administrace/order/edit/' . $helper->orderId);
        }

        return $response;
    }

    /**
     * Save order status from index page.
     *
     * @return Response
     */
    public function actionSaveStatus()
    {
        $model = Order::findOne(Yii::$app->request->post('id'));
        $model->status = Yii::$app->request->post('new_status');
        $model->save(false);
        $response = Response::getResponseBase();

        $response->statusCode = 200;
        $response->data = $model->attributes;

        return $response;
    }

    /**
     * Renders takeover setting page.
     *
     * @return View
     */
    public function actionGetPlaces()
    {
        $this->view->params['keyw'] = ['order', 'places'];

        $this->view->params['place_take_over'] = OptionsTable::getOption('place_take_over', '');
        $this->view->params['place_return'] = OptionsTable::getOption('place_return', '');

        $this->view->params['time_take_over'] = OptionsTable::getOption('time_take_over', '9:00');
        $this->view->params['time_return'] = OptionsTable::getOption('time_return', '17:00');

        return $this->render('places');
    }

    /**
     * Saves takeover settings.
     *
     * @return Response
     */
    public function actionSavePlaces()
    {
        foreach (\Yii::$app->request->post() as $optionName => $optionValue) {

            $model = OptionsTable::find()->where(['name' => $optionName])->one();

            if (empty($model)) $model = new OptionsTable;

            $model->name = $optionName;
            $model->value = $optionValue;
            $model->save();
        }

        $response = Response::getResponseBase();

        $response->statusCode = 200;
        $response->data = \Yii::$app->request->post();

        return $response;
    }

    /**
     * Renders default numberings page.
     *
     * @return View
     */
    public function actionGetPapersNumbering()
    {
        $this->view->params['keyw'] = ['order', 'papers_numbering'];

        $this->view->params['invoice_numbering_prefix'] = OptionsTable::getOption('invoice_numbering_prefix', '');
        $this->view->params['invoice_numbering_number'] = OptionsTable::getOption('invoice_numbering_number', 1);

        $this->view->params['cash_register_numbering_prefix'] = OptionsTable::getOption('cash_register_numbering_prefix', '');
        $this->view->params['cash_register_numbering_number'] = OptionsTable::getOption('cash_register_numbering_number', 1);

        $this->view->params['variable_symbol_base'] = OptionsTable::getOption('variable_symbol_base', 0);

        return $this->render('papers-numbering');
    }

    /**
     * Saves default numberings settings.
     *
     * @return Response
     */
    public function actionSavePapersNumbering()
    {
        //the invoice number consists of the prefix and the number base
        //check if default invoice numbering changed
        $invoiceNumberingChanged = Yii::$app->request->post('invoiceNumberingChanged');

        if ($invoiceNumberingChanged) {

            $invoice_numbering_prefix = OptionsTable::find()->where(['name' => 'invoice_numbering_prefix'])->one();
            if (empty($invoice_numbering_prefix)) $invoice_numbering_prefix = new OptionsTable;

            $invoice_numbering_prefix->name = 'invoice_numbering_prefix';
            $invoice_numbering_prefix->value = Yii::$app->request->post('invoice_numbering_prefix');
            $invoice_numbering_prefix->save();

            $invoice_numbering_number = OptionsTable::find()->where(['name' => 'invoice_numbering_number'])->one();
            if (empty($invoice_numbering_number)) $invoice_numbering_number = new OptionsTable;

            $invoice_numbering_number->name = 'invoice_numbering_number';
            $invoice_numbering_number->value = Yii::$app->request->post('invoice_numbering_number');
            $invoice_numbering_number->save();

            //invoice numbering usage detector
            $invoice_numbering_used = OptionsTable::find()->where(['name' => 'invoice_numbering_used'])->one();
            if (empty($invoice_numbering_used)) $invoice_numbering_used = new OptionsTable;

            $invoice_numbering_used->name = 'invoice_numbering_used';
            $invoice_numbering_used->value = '0';
            $invoice_numbering_used->save();
        }

        //same as above
        $cashRegisterNumberingChanged = Yii::$app->request->post('cashRegisterNumberingChanged');

        if ($cashRegisterNumberingChanged) {

            $cash_register_numbering_prefix = OptionsTable::find()->where(['name' => 'cash_register_numbering_prefix'])->one();
            if (empty($cash_register_numbering_prefix)) $cash_register_numbering_prefix = new OptionsTable;

            $cash_register_numbering_prefix->name = 'cash_register_numbering_prefix';
            $cash_register_numbering_prefix->value = Yii::$app->request->post('cash_register_numbering_prefix');
            $cash_register_numbering_prefix->save();

            $cash_register_numbering_number = OptionsTable::find()->where(['name' => 'cash_register_numbering_number'])->one();
            if (empty($cash_register_numbering_number)) $cash_register_numbering_number = new OptionsTable;

            $cash_register_numbering_number->name = 'cash_register_numbering_number';
            $cash_register_numbering_number->value = Yii::$app->request->post('cash_register_numbering_number');
            $cash_register_numbering_number->save();

            $cash_register_numbering_used = OptionsTable::find()->where(['name' => 'cash_register_numbering_used'])->one();
            if (empty($cash_register_numbering_used)) $cash_register_numbering_used = new OptionsTable;

            $cash_register_numbering_used->name = 'cash_register_numbering_used';
            $cash_register_numbering_used->value = '0';
            $cash_register_numbering_used->save();
        }

        $variable_symbol_base = OptionsTable::find()->where(['name' => 'variable_symbol_base'])->one();
        if (empty($variable_symbol_base)) $variable_symbol_base = new OptionsTable;
        $variable_symbol_base->name = 'variable_symbol_base';

        $variable_symbol_base->value = Yii::$app->request->post('variable_symbol_base');

        $variable_symbol_base->save();

        $response = Response::getResponseBase();

        $response->statusCode = 200;
        $response->data = \Yii::$app->request->post();

        return $response;
    }

    /**
     * Deletes order record forever.
     * Only admin is allowed for this action.
     *
     * @return Response
     */
    public function actionDelete()
    {
        $model = ServiceInfo::findOne(['id' => Yii::$app->request->post('id')]);
        $model->delete();

        $response = Response::getResponseBase();

        $response->statusCode = 200;
        $response->data = '';

        return $response;
    }

    /**
     * Ajax lazy loading for index page.
     *
     * @return Response
     */
    public function actionAjaxLoad()
    {
        $model = Order::factory();
        $filters = Yii::$app->request->post('filters') ? Yii::$app->request->post('filters') : [];
        $orders = $model->getAllOrders($filters, Yii::$app->request->post('page'));

        $response = Response::getResponseBase();

        $response->statusCode = 200;

        $response->data = ['html' => $this->buildTableRows($orders), 'can_load_more' => $orders['can_load_more'], 'count' => $orders['count']];

        return $response;
    }

    /**
     * Renders pdfs for order.
     *
     * @return void
     */
    public function actionGetPapers()
    {
        $this->view->params['keyw'] = ['', ''];

        $source = PdfModel::factory()->createPdf(\Yii::$app->request->post('pattern'), \Yii::$app->request->post('orderId'), \Yii::$app->request->post('type'));
        $pdf = $source['file']->Output($source['name'] . '.pdf', \Mpdf\Output\Destination::INLINE);
        exit;
    }

    /**
     * Renders order records rows.
     *
     * @param array $result
     * @return string HTML table rows
     */
    private function buildTableRows($result)
    {
        ob_start();
?>
        <?php foreach ($result['result'] as $order) : ?>

            <tr>
                <td><?= $order['car_name']; ?></td>

                <?php
                if ($order['is_company'] == 1) {
                    $name = $order['company_name'];
                } else {
                    $name = $order['name'];
                }
                ?>
                <td><?= $name; ?></td>

                <td><?= $order['email']; ?></td>

                <td style="white-space: nowrap"><?= $order['phone']; ?></td>

                <td><?= DatetimeHelper::startFrom($order['lease_date_from'], true) . '<br>' . DatetimeHelper::endOn($order['lease_date_to'], true); ?></td>

                <td><?= \Yii::$app->formatter->asInteger($order['mileage']); ?> km</td>

                <td><?= \Yii::$app->formatter->asCurrency($order['price'], 'CZK', [\NumberFormatter::MAX_FRACTION_DIGITS => 0]); ?></td>

                <td><?= $order['full_number']; ?></td>

                <td><?= $order['variable_symbol']; ?></td>

                <td><?= \Yii::$app->formatter->asDate($order['created_at'], 'dd.MM.YYYY') . '<br>' . \Yii::$app->formatter->asDate($order['created_at'], 'HH:mm'); ?></td>

                <td data-status="<?= $order['status']; ?>" class="status <?= $order['status']; ?>">
                    <div class="current_status"><?= Order::getOrderStatuses()[$order['status']]; ?></div>
                </td>

                <td class="actions">

                    <a href="<?= \Yii::$app->urlManager->createUrl('/administrace/order/edit/' . $order['id']); ?>" class="admin_ico admin_edit">&nbsp;</a>

                    <?php if ($order['status'] == 'finished' || $order['status'] == 'in_progress') : ?>
                        <div class="admin_ico admin_cancel" data-id="<?= $order['id']; ?>">&nbsp;</div>
                    <?php endif; ?>
                    <?php if ($order['status'] == 'in_progress') : ?>
                        <div class="admin_ico admin_finish" data-id="<?= $order['id']; ?>">&nbsp;</div>
                    <?php endif; ?>

                </td>

            </tr>
        <?php endforeach; ?>
<?php
        return ob_get_clean();
    }

    /**
     * Gets initial numberings for invoices and cash registers.
     *
     * @param integer $orderId
     * @return array
     */
    private function getInitPapersNumbers($orderId = 0)
    {
        $invoiceNumberingUsed = OptionsTable::getOption('invoice_numbering_used', 0);
        $invoiceInits = [];

        $invoice_numbering_prefix = OptionsTable::getOption('invoice_numbering_prefix', 0);
        $invoice_numbering_number = OptionsTable::getOption('invoice_numbering_number', 0);

        if (empty($invoiceNumberingUsed)) {
            $invoiceInits['prefix'] = $invoice_numbering_prefix;
            $invoiceInits['number'] = $invoice_numbering_number;
            $invoiceInits['next_number'] = $invoice_numbering_number - 1;
        } else {
            $prevNumbering = Invoice::findPrevious($orderId, $invoice_numbering_number, $invoice_numbering_prefix);
            if (empty($prevNumbering['base_prefix'])) $prevNumbering['base_prefix'] = $invoice_numbering_prefix;
            if (empty($prevNumbering['base_number'])) $prevNumbering['base_number'] = $invoice_numbering_number;
            if (empty($prevNumbering['full_number'])) $prevNumbering['full_number'] = $invoice_numbering_number;

            $fullNumber = $prevNumbering['full_number'];
            $fullNumber = str_replace($prevNumbering['base_prefix'], '', $fullNumber);
            $fullNumber = ltrim($fullNumber, '0');
            $fullNumber = intval($fullNumber);

            $invoiceInits['prefix'] = $prevNumbering['base_prefix'];
            $invoiceInits['number'] = $prevNumbering['base_number'];

            $invoiceInits['next_number'] = $fullNumber;
        }

        $cashRegisterNumebringUsed = OptionsTable::getOption('cash_register_numbering_used', 0);
        $cashRegisterInits = [];

        $cash_register_numbering_prefix = OptionsTable::getOption('cash_register_numbering_prefix', 0);
        $cash_register_numbering_number = OptionsTable::getOption('cash_register_numbering_number', 0);

        if (empty($cashRegisterNumebringUsed)) {
            $cashRegisterInits['prefix'] = $cash_register_numbering_prefix;
            $cashRegisterInits['number'] = $cash_register_numbering_number;
            $cashRegisterInits['next_number'] = $cash_register_numbering_number - 1;
        } else {
            $prevNumbering = CashRegister::findPrevious($orderId, $cash_register_numbering_number, $cash_register_numbering_prefix);
            if (empty($prevNumbering['base_prefix'])) $prevNumbering['base_prefix'] = $cash_register_numbering_prefix;
            if (empty($prevNumbering['base_number'])) $prevNumbering['base_number'] = $cash_register_numbering_number;
            if (empty($prevNumbering['full_number'])) $prevNumbering['full_number'] = $cash_register_numbering_number;

            $fullNumber = $prevNumbering['full_number'];
            $fullNumber = str_replace($prevNumbering['base_prefix'], '', $fullNumber);
            $fullNumber = ltrim($fullNumber, '0');
            $fullNumber = intval($fullNumber);

            $cashRegisterInits['next_number'] = $fullNumber;
            $cashRegisterInits['prefix'] = $prevNumbering['base_prefix'];
            $cashRegisterInits['number'] = $prevNumbering['base_number'];
        }

        return ['invoiceInits' => $invoiceInits, 'cashRegisterInits' => $cashRegisterInits];
    }
}
