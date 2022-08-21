<?php

namespace app\modules\administrace\traits;

use app\modules\car\models\Car;
use app\modules\car\models\Price;
use app\modules\order\helpers\DatetimeHelper;
use app\modules\common\components\Response;

/**
 * Helps with car rent price calculations.
 */

trait CalculationsTrait
{

    public function actionCalculatePrice()
    {
        $dateFrom = \Yii::$app->request->post('date_from') !== null ? \Yii::$app->request->post('date_from') : \Yii::$app->request->post('lease_date_from');
        $dateTo = \Yii::$app->request->post('date_to') !== null ? \Yii::$app->request->post('date_to') : \Yii::$app->request->post('lease_date_to');

        $numOfDays = DatetimeHelper::getNumOfDays($dateFrom, $dateTo);

        $price = Price::factory(\Yii::$app->request->post('car_id'), \Yii::$app->request->post('mileage'), $numOfDays, (bool) \Yii::$app->request->post('use_rider'))->calculatePrice();

        $response = Response::getResponseBase();

        $response->statusCode = 200;
        $response->data = $price;

        return $response;
    }

    public function actionBailValue()
    {
        $bailValue = Car::getBail(\Yii::$app->request->post('car_id'), \Yii::$app->request->post('use_rider'));

        $response = Response::getResponseBase();

        $response->statusCode = 200;
        $response->data = $bailValue;

        return $response;
    }

    public function getBailValue(int $carId, int $useRider)
    {
        return Car::getBail($carId, $useRider);
    }
}
