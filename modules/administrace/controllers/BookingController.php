<?php

namespace app\modules\administrace\controllers;

use Yii;
use app\modules\order\models\Booking;
use app\modules\car\models\Car;
use yii\web\NotFoundHttpException;
use app\modules\common\components\Response;
use yii\helpers\Url;
use app\modules\order\helpers\DatetimeHelper;
use app\modules\administrace\traits\CalculationsTrait;
use yii\web\Controller;

/**
 * Maintains all booking operations
 */

class BookingController extends Controller
{
    use CalculationsTrait;

    public $layout = 'main.php';

    /**
     * Renders index page
     *
     * @return View
     */
    public function actionIndex()
    {
        $model = Booking::factory();

        $bookings = $model->getAllBookings([], 1);

        //left menu params
        $this->view->params['keyw'] = ['booking', 'all'];
        $this->view->params['record_type'] = 'booking';
        $this->view->params['view_type'] = 'list';

        //data for selectboxes
        $this->view->params['statuses'] = Booking::getBookingStatuses();
        $this->view->params['cars'] = Car::findForFilter();

        $this->view->params['records_count'] = $bookings['count'];

        $this->view->params['can_load_more'] = $bookings['can_load_more'];

        return $this->render('index', ['bookings' => $bookings['result']]);
    }

    /**
     * Renders new booking page
     *
     * @return View
     */
    public function actionNew()
    {
        //left menu params
        $this->view->params['keyw'] = ['booking', 'new'];
        $this->view->params['view_type'] = 'new';

        //data for selectboxes
        $this->view->params['select_types'] = [
            'car' => Car::findForFilter(),
            'statuses' => Booking::getBookingStatuses()
        ];

        return $this->render('new');
    }

    /**
     * Renders booking edit page
     *
     * @return View
     */
    public function actionEdit()
    {
        $params = Yii::$app->request->queryParams;
        $booking = Booking::findOne($params['id']);

        //left menu params
        $this->view->params['keyw'] = ['booking', 'edit'];
        $this->view->params['view_type'] = 'edit';

        //data for selectboxes
        $this->view->params['select_types'] = [
            'car' => Car::findForFilter(),
            'statuses' => Booking::getBookingStatuses()
        ];

        return $this->render('edit', ['booking' => $booking]);
    }

    /**
     * Saves booking data
     *
     * @return Response
     */
    public function actionSave()
    {
        //Booking model: load or create new
        if (empty(Yii::$app->request->post('id'))) {
            $model = Booking::factory();
        } else {
            $model = Booking::findOne(Yii::$app->request->post('id'));
            if (empty($model)) throw new NotFoundHttpException;
        }

        //Fill model attributes
        foreach (Yii::$app->request->post() as $attribute => $value) {
            if ($model->hasAttribute($attribute)) {
                $model->$attribute = $value;
            }
        }

        //Validate and save model
        if ($model->save(true)) {
            $data = $model->attributes;
        } else {
            $data = $model->getFirstErrors();
        }

        //Redirect from new booking to edit booking
        if (empty(Yii::$app->request->post('id'))) {
            return $this->redirect(Url::base(true) . '/administrace/booking/edit/' . $model->id);
        }

        //Create new order from booking
        if (!empty(Yii::$app->request->post('redirect'))) {
            return $this->redirect(Url::base(true) . '/administrace/order/new/' . $model->id);
        }

        $response = Response::getResponseBase();

        $response->statusCode = 200;
        $response->data = $data;

        return $response;
    }

    /**
     * Save booking status from index page.
     *
     * @return Response
     */
    public function actionSaveStatus()
    {
        $model = Booking::findOne(Yii::$app->request->post('id'));
        $model->status = Yii::$app->request->post('new_status');
        $model->save(false);
        $response = Response::getResponseBase();

        $response->statusCode = 200;
        $response->data = $model->attributes;

        return $response;
    }

    /**
     * Lazy loading for index page.
     *
     * @return Response
     */
    public function actionAjaxLoad()
    {
        $model = Booking::factory();

        $filters = Yii::$app->request->post('filters') ? Yii::$app->request->post('filters') : [];
        $bookings = $model->getAllBookings($filters, Yii::$app->request->post('page'));

        $response = Response::getResponseBase();

        $response->statusCode = 200;

        $response->data = ['html' => $this->buildTableRows($bookings), 'can_load_more' => $bookings['can_load_more'], 'count' => $bookings['count']];

        return $response;
    }

    /**
     * Renders record rows for index page.
     *
     * @param array $result
     * @return string table rows
     */
    private function buildTableRows(array $result)
    {
        ob_start();
?>
        <?php foreach ($result['result'] as $booking) : ?>

            <tr>
                <td><?= $booking['car_name']; ?></td>

                <td><?= $booking['name']; ?></td>

                <td><?= $booking['email']; ?></td>

                <td style="white-space: nowrap"><?= $booking['phone']; ?></td>

                <td><?= DatetimeHelper::startFrom($booking['date_from'], true) . '<br>' . DatetimeHelper::endOn($booking['date_to'], true); ?></td>

                <td><?= \Yii::$app->formatter->asInteger($booking['mileage']); ?> km</td>

                <td><?= \Yii::$app->formatter->asCurrency($booking['price'], 'CZK', [\NumberFormatter::MAX_FRACTION_DIGITS => 0]); ?></td>

                <td><?= \Yii::$app->formatter->asDate($booking['created_at'], 'dd.MM.YYYY') . '<br>' . \Yii::$app->formatter->asDate($booking['created_at'], 'HH:mm'); ?></td>

                <td data-status="<?= $booking['status']; ?>" class="status <?= $booking['status']; ?>">
                    <div class="current_status"><?= Booking::getBookingStatuses()[$booking['status']]; ?></div>
                </td>

                <td class="actions">

                    <?php if ($booking['status'] == 'active') : ?>
                        <a href="<?= \Yii::$app->urlManager->createUrl('/administrace/order/new/' . $booking['id']); ?>" class="admin_ico admin_add_order">&nbsp;</a>
                    <?php else : ?>
                        <div class="admin_ico admin_order_exists">&nbsp;</div>
                    <?php endif; ?>

                    <a href="<?= \Yii::$app->urlManager->createUrl('/administrace/booking/edit/' . $booking['id']); ?>" class="admin_ico admin_edit">&nbsp;</a>

                    <?php if ($booking['status'] == 'active') : ?>
                        <div class="admin_ico admin_decline" data-id="<?= $booking['id']; ?>">&nbsp;</div>
                    <?php elseif ($booking['status'] == 'declined') : ?>
                        <div class="admin_ico admin_refresh" data-id="<?= $booking['id']; ?>">&nbsp;</div>
                    <?php else : ?>
                        <div class="admin_ico">&nbsp;</div>
                    <?php endif; ?>

                </td>

            </tr>

        <?php endforeach; ?>
<?php
        return ob_get_clean();
    }
}
