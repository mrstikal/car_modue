<?php

namespace app\modules\order\models;

use app\modules\order\models\Order;

/**
 * Cashbook model.
 * Generates overview of revenues.
 */
class Cashbook extends Order
{
    /**
     * Filters orders records by timestamp and status.
     *
     * @param int $from
     * @param int $to
     * @return array
     */
    public function getCashbook(int $from, int $to)
    {
        $filters = [
            'search_created_from' => [
                'value' => $from,
                'type' => 'where',
                'operator' => '>'
            ],
            'search_created_to' => [
                'value' => $to,
                'type' => 'where',
                'operator' => '<'
            ],
            'filter_booking_status' => [
                'value' => static::STATUS_FINISHED,
                'type' => 'where',
                'operator' => '='
            ],
        ];

        $orders = $this->getOrders($filters);

        $count = count($orders);

        $totalSum = \Yii::$app->formatter->asCurrency(array_sum(array_column($orders, 'price')), 'CZK', [\NumberFormatter::MAX_FRACTION_DIGITS => 0]);

        return ['orders' => $orders, 'count' => $count, 'totalSum' => $totalSum];
    }
}
