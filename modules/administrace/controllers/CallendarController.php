<?php

namespace app\modules\administrace\controllers;

use Yii;
use app\modules\car\models\CarLanguage;
use app\modules\common\components\Response;
use app\modules\administrace\traits\CalculationsTrait;
use app\modules\order\models\Booking;
use app\modules\order\models\Order;
use app\modules\car\models\Car;
use yii\db\Query;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * Performs fullcalendar actions
 */

class CallendarController extends Controller
{
    use CalculationsTrait;

    public $layout = 'main.php';

    protected $ajaxMethods = [];

    /**
     * Renders index page
     *
     * @return View
     */
    public function actionIndex()
    {
        //left menu params
        $this->view->params['keyw'] = ['callendar', 'all'];
        $this->view->params['view_type'] = 'list';

        //selectbox params
        $this->view->params['select_types'] = [
            'car' => Car::findForFilter(),
        ];

        return $this->render('index');
    }

    /**
     * Loads bookings into fullcalendar
     *
     * @return Response bookings data
     */
    public function actionLoadBookings()
    {
        $allBookings = '';

        if (Yii::$app->request->post('record_type') == 'bookings' || Yii::$app->request->post('record_type') == 'all') {

            //query bookings, join car data in current language
            $query = (new Query)
                ->select(['booking.id', 'booking.name', 'booking.phone', 'booking.email', 'booking.date_from', 'booking.date_to', 'car.name AS car_name'])
                ->from(Booking::tableName() . ' booking')
                ->andWhere(['booking.status' => 'active'])
                ->leftJoin(CarLanguage::tableName() . ' car', 'car.car_id = ' . Booking::tableName() . '.car_id AND car.language = "' . \Yii::$app->sourceLanguage . '"');

            //filter car if requested
            if (Yii::$app->request->post('car_id'))  $query->andWhere(['booking.car_id' => Yii::$app->request->post('car_id')]);

            $bookings = $query->orderBy(['booking.id' => SORT_DESC])->all();

            $allBookings = [];

            //walk through data and create final set
            foreach ($bookings as $key => $booking) {
                $allBookings[$key]['start'] = $booking['date_from'] * 1000;
                $allBookings[$key]['end'] = $booking['date_to'] * 1000 + 86400000;
                $allBookings[$key]['customHtml'] = $booking['id'] . '&nbsp;&nbsp;&nbsp;' . $booking['car_name'] . '&nbsp;&nbsp;&nbsp;' . $booking['name'];
                $allBookings[$key]['allDay'] = true;
                $allBookings[$key]['tooltipContent'] = $booking['car_name'] . '<br>' . $booking['name'] . '<br>' . $booking['phone'] . '<br>' . $booking['email'];
                $allBookings[$key]['url'] = Url::base(true) . Url::to('/administrace/booking/edit/') . $booking['id'];
            }
        }

        $response = Response::getResponseBase();

        $response->statusCode = 200;
        $response->data = $allBookings;

        return $response;
    }

    /**
     * Loads orders into fullcalendar 
     *
     * @return Response
     */
    public function actionLoadOrders()
    {
        $allOrders = '';

        if (Yii::$app->request->post('record_type') == 'orders' || Yii::$app->request->post('record_type') == 'all') {

            //query orders, join car data in current language
            $query = (new Query)
                ->select(['order.id', 'order.name', 'order.company_name', 'order.phone', 'order.email', 'order.lease_date_from', 'order.lease_date_to', 'order.is_company', 'car.name AS car_name'])
                ->from(Order::tableName() . ' order')
                ->andWhere(['order.status' => 'in_progress'])
                ->leftJoin(CarLanguage::tableName() . ' car', 'car.car_id = order.car_id AND car.language = "' . \Yii::$app->sourceLanguage . '"');

            //filter car if requested
            if (Yii::$app->request->post('car_id'))  $query->andWhere(['order.car_id' => Yii::$app->request->post('car_id')]);

            $orders = $query->orderBy(['order.id' => SORT_DESC])->all();

            $allOrders = [];

            //walk through data and create final set
            foreach ($orders as $key => $order) {
                if ($order['is_company']) $order['name'] = $order['company_name'];
                $allOrders[$key]['start'] = $order['lease_date_from'] * 1000;
                $allOrders[$key]['end'] = $order['lease_date_to'] * 1000 + 86400000;;
                $allOrders[$key]['customHtml'] = $order['id'] . '&nbsp;&nbsp;&nbsp;'  . $order['car_name'] . '&nbsp;&nbsp;&nbsp;' . $order['name'];
                $allOrders[$key]['allDay'] = true;
                $allOrders[$key]['tooltipContent'] = $order['car_name'] . '<br>' . $order['name'] . '<br>' . $order['phone'] . '<br>' . $order['email'];
                $allOrders[$key]['url'] = Url::base(true) . Url::to('/administrace/order/edit/') . $order['id'];
            }
        }

        $response = Response::getResponseBase();

        $response->statusCode = 200;
        $response->data = $allOrders;

        return $response;
    }
}
