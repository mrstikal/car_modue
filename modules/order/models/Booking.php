<?php

namespace app\modules\order\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Query;
use app\modules\car\models\CarLanguage;
use app\modules\order\helpers\BookingQueryHelper;

/**
 * Base model for bookings table.
 */
class Booking extends ActiveRecord
{
    /**
     * Available statuses
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_SOLVED = 'solved';
    const STATUS_DECLINED = 'declined';
    const STATUS_DELETED = 'deleted';

    /**
     * Pagination offset for ajax lazy loading, i.e. num of items per page
     */
    private $paginationOffset = 50;

    /**
     * Actual page for lazy loading.
     *
     * @var integer
     */
    public $page = 0;

    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return 'booking';
    }

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [['car_id', 'date_from', 'date_to', 'mileage', 'price'], 'integer'],
            [['name', 'email', 'phone', 'status', 'note'], 'string'],
            [['car_id', 'date_from', 'date_to', 'mileage', 'price', 'email'], 'required'],
            [['status'], 'in', 'range' => array_keys(self::getBookingStatuses(true))]
        ];
    }

    /**
     * Creates instance of this model
     *
     * @return Booking
     */
    public static function factory(): Booking
    {
        return new static();
    }

    /**
     * Returns available service infos statuses
     * status value => status label
     *
     * @param bool $includeDeleteStatus
     * 
     * @return array
     */
    public static function getBookingStatuses(bool $includeDeleteStatus = false): array
    {
        $statuses = [
            static::STATUS_ACTIVE => 'Aktivní',
            static::STATUS_SOLVED => 'Objednáno',
            static::STATUS_DECLINED => 'Stornováno',
        ];

        if ($includeDeleteStatus) {
            $statuses[static::STATUS_DELETED] = 'Smazáno (lze obnovit)';
        }

        return $statuses;
    }

    /**
     * Returns all bookings.
     *
     * @param array $filters
     * @param integer $page pagination for lazy loading
     * @return array
     */
    public function getAllBookings(array $filters = [], int $page = 0): array
    {
        $this->page = $page;
        return $this->getBookings($filters);
    }

    /**
     * General query for booking model
     *
     * @return array
     */
    private function getBookings($filters): array
    {
        //query base
        $query = (new Query)
            ->select(static::tableName() . '.*')
            ->addSelect('car.name AS car_name')
            ->from(static::tableName());

        $query->andWhere([static::tableName() . '.status' => array_keys(self::getBookingStatuses())]);

        $query = BookingQueryHelper::factory()->filterQuery($query, $filters);

        //join car table
        $query->leftJoin(CarLanguage::tableName() . ' car', 'car.car_id = ' . static::tableName() . '.car_id AND car.language = "' . \Yii::$app->sourceLanguage . '"');

        $countingQuery = clone $query;
        $count = $countingQuery->count();

        //if lazy loading is set, set offset and limit
        //try to load one more record than pagination offset - we will use it for 'we can load more' detection
        if (!empty($this->page)) $query->offset(($this->page - 1) * $this->paginationOffset)->limit($this->paginationOffset + 1);

        $result = $query->all();

        //if lazy loading is set and detection record exists
        if (count($result) > $this->paginationOffset && !empty($this->page)) {
            //unset detection record
            array_pop($result);
            return ['result' => $result, 'can_load_more' => 1, 'count' => $count];
        }

        //if lazy loading is set and detection record doesn't exists
        if (count($result) <= $this->paginationOffset && !empty($this->page)) {
            return ['result' => $result, 'can_load_more' => 0, 'count' => $count];
        }

        //if no lazy loading, just return query result
        if (empty($this->page)) {
            return $result;
        }
    }

    /**
     * Finds single booking by ID.
     *
     * @param integer $bookingId
     * @return array
     */
    public static function findSingle(int $bookingId)
    {
        return (new Query)
            ->select(static::tableName() . '.*')
            ->from(static::tableName())
            ->where(['id' => $bookingId])
            ->one();
    }
}
