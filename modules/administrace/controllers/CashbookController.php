<?php

namespace app\modules\administrace\controllers;

use Carbon\Carbon;
use app\modules\common\components\Response;
use app\modules\order\models\Cashbook;
use yii\web\Controller;

class CashbookController extends Controller
{
    public $layout = 'main.php';

    /**
     * Renders index page.
     *
     * @return View
     */
    public function actionIndex()
    {
        $this->view->params['keyw'] = ['cashbook', ''];

        $now = Carbon::now();

        //default start and end times for datepickers
        $this->view->params['defaultStart'] = $now->startOfMonth()->timestamp;
        $this->view->params['defaultEnd'] = $now->endOfMonth()->timestamp;

        return $this->render('index');
    }

    /**
     * Ajax lazy loading for cashbook records table.
     *
     * @return Response
     */
    public function actionGetCashbook()
    {
        $incoming = (new Cashbook)->getCashbook(\Yii::$app->request->post('date_from'), \Yii::$app->request->post('date_to'));

        $tableRows = $this->buildTableRows($incoming['orders']);

        $response = Response::getResponseBase();

        $response->statusCode = 200;
        $response->data = [
            'tableRows' => $tableRows,
            'count' => $incoming['count'],
            'totalSum' => $incoming['totalSum'],
        ];

        return $response;
    }

    /**
     * Builds record rows for table
     *
     * @param array $incoming
     * @return string html table rows
     */
    private function buildTableRows($incoming)
    {
        ob_start();
?>
        <?php foreach ($incoming as $record) : ?>
            <?php
            $customerName = $record['name'];
            if ($record['is_company']) {
                $customerName = $record['company_name'];
            }
            if ($record['different_bill_address']) {
                $customerName = $record['billing_name'];
            }
            ?>
            <tr>
                <td class="align_left"><?= $record['car_name']; ?></td>
                <td class="align_right"><?= \Yii::$app->formatter->asCurrency($record['price']); ?></td>
                <td><?= $customerName; ?></td>
                <td><?= empty($record['issue_date']) ? '-' : \Yii::$app->formatter->asDate($record['issue_date'], 'dd.MM.YYYY'); ?></td>
                <td><?= empty($record['full_number']) ? '-' : $record['full_number']; ?></td>
                <td><?= empty($record['variable_symbol']) ? '-' : $record['variable_symbol']; ?></td>
                <td><?= empty($record['cash_register_payment_date']) ? '-' : \Yii::$app->formatter->asDate($record['cash_register_payment_date'], 'dd.MM.YYYY'); ?></td>
                <td><?= empty($record['cash_register_full_number']) ? '-' : $record['cash_register_full_number']; ?></td>
            </tr>

        <?php endforeach; ?>
<?php
        return ob_get_clean();
    }
}
