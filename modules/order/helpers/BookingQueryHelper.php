<?php

namespace app\modules\order\helpers;

use yii\db\Query;
use app\modules\order\models\Booking;

class BookingQueryHelper
{
    /**
     * Map for frontend/backend fields conversions.
     *
     * @var array
     */
    private $fieldsMap = [
        'search_created_from' => 'created_at',
        'search_created_to' => 'created_at',
        'search_start_rent_from' => 'date_from',
        'search_start_rent_to' => 'date_from',
        'search_end_rent_from' => 'date_to',
        'search_end_rent_to' => 'date_to',
        'filter_booking_status' => 'status',
        'filter_car' => 'car_id',
    ];

    /**
     * Creates instance of this model
     *
     * @return BookingQueryHelper
     */
    public static function factory(): BookingQueryHelper
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
        $orderByDirection = $orderByDirection = 'SORT_DESC';
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
                $query->andWhere([$filter['operator'], Booking::tableName() . '.' . $this->fieldsMap[$field], $filter['value']]);
            }

            if ($filter['type'] == "fulltext") {
                $searchPhrase = strip_tags(trim($filter['value']));
                $searchPhrase = preg_replace('/[^\p{L}\p{N}_]+/u', ' ', $searchPhrase);
                $searchPhrase = trim($searchPhrase);
                $looseSearchPhrase = trim(implode('* ', explode(' ', $searchPhrase . ' ')));

                $table = Booking::tableName();

                $query->andWhere([
                    'or',
                    "MATCH ($table.name) AGAINST ('$searchPhrase' IN BOOLEAN MODE)",
                    "MATCH ($table.email) AGAINST ('$searchPhrase' IN BOOLEAN MODE)",
                    "MATCH ($table.phone) AGAINST ('$searchPhrase' IN BOOLEAN MODE)",
                    "MATCH ($table.name) AGAINST ('$looseSearchPhrase' IN BOOLEAN MODE)",
                    "MATCH ($table.email) AGAINST ('$looseSearchPhrase' IN BOOLEAN MODE)",
                    "MATCH ($table.phone) AGAINST ('$looseSearchPhrase' IN BOOLEAN MODE)"
                ]);
            }
        }

        return $query;
    }
}
