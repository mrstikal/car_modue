<?php

namespace app\modules\order\helpers;

use yii\db\Query;
use app\modules\order\models\Order;

class OrderQueryHelper
{
    private $fieldsMap = [];

    public function __construct()
    {
        $this->fieldsMap = [
            'search_created_from' => Order::tableName() . '.created_at',
            'search_created_to' => Order::tableName() . '.created_at',
            'search_start_rent_from' => Order::tableName() . '.lease_date_from',
            'search_start_rent_to' => Order::tableName() . '.lease_date_from',
            'search_end_rent_from' => Order::tableName() . '.lease_date_to',
            'search_end_rent_to' => Order::tableName() . '.lease_date_to',
            'filter_booking_status' => Order::tableName() . '.status',
            'filter_car' => Order::tableName() . '.car_id',
            'search_invoice_number' => 'invoice.full_number',
            'search_variable_symbol' => 'invoice.variable_symbol',
        ];
    }

    /**
     * Creates instance of this model
     *
     * @return OrderQueryHelper
     */
    public static function factory(): OrderQueryHelper
    {
        return new static();
    }

    /**
     * Filters query by incoming params.
     *
     * @param Query $query
     * @param array $filters
     * @return Query
     */
    public function filterQuery(Query $query, array $filters): Query
    {
        $orderByDirection = 'SORT_DESC';
        $orderByValue = 'created_at';

        $key = array_search('order-by', array_filter(array_combine(array_keys($filters), array_column($filters, 'type'))));
        if (isset($filters[$key])) {
            $orderByValue = $filters[$key]['value'];
        }

        $key = array_search('order-direction', array_filter(array_combine(array_keys($filters), array_column($filters, 'type'))));
        if (isset($filters[$key])) {
            $orderByDirection = $filters[$key]['value'];
        }


        $query->orderBy([$orderByValue => constant($orderByDirection)]);

        foreach ($filters as $field => $filter) {

            if ($filter['type'] == "where") {
                $query->andWhere([$filter['operator'], $this->fieldsMap[$field], $filter['value']]);
            }

            if ($filter['type'] == "fulltext") {
                $searchPhrase = strip_tags(trim($filter['value']));
                $searchPhrase = preg_replace('/[^\p{L}\p{N}_]+/u', ' ', $searchPhrase);
                $searchPhrase = trim($searchPhrase);
                $looseSearchPhrase = trim(implode('* ', explode(' ', $searchPhrase . ' ')));

                $table = Order::tableName();

                $query->andWhere([
                    'or',
                    "MATCH ($table.name) AGAINST ('$searchPhrase' IN BOOLEAN MODE)",
                    "MATCH ($table.email) AGAINST ('$searchPhrase' IN BOOLEAN MODE)",
                    "MATCH ($table.phone) AGAINST ('$searchPhrase' IN BOOLEAN MODE)",
                    "MATCH ($table.company_name) AGAINST ('$searchPhrase' IN BOOLEAN MODE)",
                    "MATCH ($table.name) AGAINST ('$looseSearchPhrase' IN BOOLEAN MODE)",
                    "MATCH ($table.email) AGAINST ('$looseSearchPhrase' IN BOOLEAN MODE)",
                    "MATCH ($table.phone) AGAINST ('$looseSearchPhrase' IN BOOLEAN MODE)",
                    "MATCH ($table.company_name) AGAINST ('$looseSearchPhrase' IN BOOLEAN MODE)"
                ]);
            }
        }

        return $query;
    }
}
