<?php

namespace app\modules\order\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Query;
use app\modules\order\models\Invoice;
use app\modules\order\models\CashRegister;
use app\modules\car\models\CarLanguage;
use app\modules\order\helpers\OrderQueryHelper;
use app\modules\country\models\Country;

/**
 * Base model for orders table.
 */
class Order extends ActiveRecord
{
    /**
     * Available statuses
     */
    const STATUS_FINISHED = 'finished';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_CANCELED = 'canceled';

    /**
     * Available payment methods
     */
    const PAYMENT_METHOD_CASH = 'cash';
    const PAYMENT_METHOD_TRANSFER = 'transfer';

    /**
     * Customer types
     */
    const CUSTOMER_INDIVIDUAL = 0;
    const CUSTOMER_BUSINESS = 1;

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
     * These fields needs to be filled with time() when empty
     *
     * @var array
     */
    private static $dateFields = [
        'invoice_rent_issue_date',
        'invoice_rent_supply_date',
        'invoice_rent_due_at',
        'cash_register_rent_payment_date',
        'invoice_bail_issue_date',
        'invoice_bail_supply_date',
        'invoice_bail_due_at',
        'cash_register_bail_payment_date',
    ];

    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return 'order_item';
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
            [
                [
                    'booking_id', 'invoice_id', 'bail_invoice_id', 'cash_register_id', 'bail_cash_register_id', 'operator_id', 'state', 'company_state',
                    'billing_state', 'car_id', 'lease_date_from', 'lease_date_to', 'mileage', 'price', 'vehicle_handover_date',
                    'vehicle_return_date', 'contractual_fine'
                ],
                'integer'
            ],
            [
                [
                    'is_company', 'payment_in_advance', 'use_rider', 'create_invoice', 'create_bail_invoice', 'create_cash_register', 'create_bail_cash_register', 'different_bill_address'
                ],
                'integer', 'max' => 1, 'min' => 0
            ],
            [
                [
                    'name', 'email', 'phone', 'birth_number', 'identity_card_number', 'permanent_residence', 'street', 'zip', 'town', 'company_name',
                    'company_street', 'company_zip', 'company_town', 'billing_name', 'billing_street', 'billing_zip', 'billing_town', 'billing_ico', 'billing_dic', 'status',
                    'vehicle_handover_place', 'vehicle_return_place', 'birth_date', 'ico', 'dic'
                ],
                'string'
            ],
            [['is_company', 'payment_in_advance', 'different_bill_address'], 'default', 'value' => 0],
            ['status', 'default', 'value' => 'active'],
            [['state', 'company_state', 'billing_state'], 'default', 'value' => 57],
            [
                [
                    'name', 'phone', 'birth_number', 'car_id', 'lease_date_from', 'lease_date_to', 'mileage', 'price',
                    'vehicle_handover_date', 'vehicle_handover_date', 'vehicle_handover_place', 'vehicle_return_place', 'vehicle_handover_time', 'vehicle_return_time',
                ],
                'required'
            ],
            [['invoice_id'], 'required', 'when' => function ($model) {
                return !isset($model->create_invoice) && $model->create_invoice == 1;
            }],
            [['bail_invoice_id'], 'required', 'when' => function ($model) {
                return !isset($model->create_bail_invoice) && $model->create_bail_invoice == 1;
            }],
            [['cash_register_id'], 'required', 'when' => function ($model) {
                return !isset($model->create_cash_register) && $model->create_cash_register == 1;
            }],
            [['bail_cash_register_id'], 'required', 'when' => function ($model) {
                return !isset($model->create_bail_cash_register) && $model->create_bail_cash_register == 1;
            }],
            [['street', 'zip', 'town'], 'required', 'when' => function ($model) {
                return !isset($model->is_company) || $model->is_company == 0;
            }],
            [['company_name', 'company_street', 'company_zip', 'company_town', 'ico'], 'required', 'when' => function ($model) {
                return isset($model->is_company) && $model->is_company == 1;
            }],
            [['billing_name', 'billing_street', 'billing_zip', 'billing_town'], 'required', 'when' => function ($model) {
                return isset($model->different_bill_address) && $model->different_bill_address == 1;
            }],
            ['status', 'in', 'range' => array_keys(static::getOrderStatuses())],
        ];
    }

    /**
     * Creates instance of this model
     *
     * @return Order
     */
    public static function factory(): Order
    {
        return new static();
    }

    /**
     * Returns available order statuses
     * status value => status label
     *
     * @return array
     */
    public static function getOrderStatuses(): array
    {
        return [
            static::STATUS_FINISHED => 'Ukončeno',
            static::STATUS_IN_PROGRESS => 'Aktivní',
            static::STATUS_CANCELED => 'Stornováno',
        ];
    }

    /**
     * Returns available order payment methods
     * status value => status label
     *
     * @return array
     */
    public static function getOrderPaymentMethods(): array
    {
        return [
            static::PAYMENT_METHOD_CASH => 'V hotovosti',
            static::PAYMENT_METHOD_TRANSFER => 'Bankovním převodem'
        ];
    }

    /**
     * Returns available customer types
     * status value => status label
     *
     * @return array
     */
    public static function getCustomerTypes(): array
    {
        return [
            static::CUSTOMER_INDIVIDUAL => 'Fyzická osoba',
            static::CUSTOMER_BUSINESS => 'Firma / Podnikatel'
        ];
    }

    /**
     * Gets complete record with all joined params.
     *
     * @param integer $id
     * @return array
     */
    public static function getCompleteOrder(int $id)
    {
        $query = (new Query)
            ->select('order_table.*')
            ->from(self::tableName() . ' AS order_table')
            ->where(['order_table.id' => $id]);

        $query->leftJoin(Invoice::tableName() . ' AS invoice_table_rent', 'invoice_table_rent.related_order_id = order_table.id AND invoice_table_rent.related_type = "rent"');
        $query->addSelect([
            'invoice_table_rent.payment_method AS invoice_rent_payment_method',
            'invoice_table_rent.actual_prefix AS invoice_rent_actual_prefix',
            'invoice_table_rent.actual_number AS invoice_rent_actual_number',
            'invoice_table_rent.full_number AS invoice_rent_full_number',
            'invoice_table_rent.issue_date AS invoice_rent_issue_date',
            'invoice_table_rent.supply_date AS invoice_rent_supply_date',
            'invoice_table_rent.due_at AS invoice_rent_due_at',
            'invoice_table_rent.variable_symbol AS invoice_rent_variable_symbol',
        ]);

        $query->leftJoin(Invoice::tableName() . ' AS invoice_table_bail', 'invoice_table_bail.related_order_id = order_table.id AND invoice_table_bail.related_type = "bail"');
        $query->addSelect([
            'invoice_table_bail.payment_method AS invoice_bail_payment_method',
            'invoice_table_bail.actual_prefix AS invoice_bail_actual_prefix',
            'invoice_table_bail.actual_number AS invoice_bail_actual_number',
            'invoice_table_bail.full_number AS invoice_bail_full_number',
            'invoice_table_bail.issue_date AS invoice_bail_issue_date',
            'invoice_table_bail.supply_date AS invoice_bail_supply_date',
            'invoice_table_bail.due_at AS invoice_bail_due_at',
            'invoice_table_bail.variable_symbol AS invoice_bail_variable_symbol',
        ]);

        $query->leftJoin(CashRegister::tableName() . ' AS cash_register_table_rent', 'cash_register_table_rent.related_order_id = order_table.id AND cash_register_table_rent.related_type = "rent"');
        $query->addSelect([
            'cash_register_table_rent.actual_prefix AS cash_register_rent_actual_prefix',
            'cash_register_table_rent.actual_number AS cash_register_rent_actual_number',
            'cash_register_table_rent.full_number AS cash_register_rent_full_number',
            'cash_register_table_rent.payment_date AS cash_register_rent_payment_date',
        ]);

        $query->leftJoin(CashRegister::tableName() . ' AS cash_register_table_bail', 'cash_register_table_bail.related_order_id = order_table.id AND cash_register_table_bail.related_type = "bail"');
        $query->addSelect([
            'cash_register_table_bail.actual_prefix AS cash_register_bail_actual_prefix',
            'cash_register_table_bail.actual_number AS cash_register_bail_actual_number',
            'cash_register_table_bail.full_number AS cash_register_bail_full_number',
            'cash_register_table_bail.payment_date AS cash_register_bail_payment_date',
        ]);

        $query->leftJoin(Country::tableName() . ' AS country_table', 'country_table.id = order_table.company_state');
        $query->addSelect([
            'country_table.title_native AS customer_country',
        ]);

        $attributes = $query->one();

        array_walk($attributes, function (&$value, $key) {
            if (in_array($key, static::$dateFields)) {
                if ($value === null) $value = time();
            } else {
                if ($value === null) $value = '';
            }
        });

        return $attributes;
    }

    /**
     * Returns all orders.
     *
     * @param array $filters
     * @param integer $page pagination for lazy loading
     * @return array
     */
    public function getAllOrders(array $filters = [], int $page = 0): array
    {
        $this->page = $page;
        return $this->getOrders($filters);
    }

    /**
     * General query for order model
     *
     * @return array
     */
    protected function getOrders($filters): array
    {
        //query base
        $query = (new Query)
            ->select(static::tableName() . '.*')
            ->addSelect(['car.name AS car_name', 'car.id AS car_id'])
            ->addSelect(['invoice.full_number AS full_number', 'invoice.variable_symbol as variable_symbol', 'invoice.issue_date as issue_date'])
            ->addSelect(['cash_register.payment_date AS cash_register_payment_date', 'cash_register.full_number as cash_register_full_number'])
            ->from(static::tableName());

        $query = OrderQueryHelper::factory()->filterQuery($query, $filters);

        //join car table
        $query->leftJoin(CarLanguage::tableName() . ' car', 'car.car_id = ' . static::tableName() . '.car_id AND car.language = "' . \Yii::$app->sourceLanguage . '"');

        //join invoice table
        $query->leftJoin(Invoice::tableName() . ' invoice', 'invoice.related_order_id = ' . static::tableName() . '.id AND invoice.related_type = "rent"');

        //join cash register table
        $query->leftJoin(CashRegister::tableName() . ' cash_register', 'cash_register.related_order_id = ' . static::tableName() . '.id AND cash_register.related_type = "rent"');

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
}
