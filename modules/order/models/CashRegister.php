<?php

namespace app\modules\order\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use app\modules\administrace\traits\PapersQueryTrait;

/**
 * Base model for cash register table.
 */
class CashRegister extends ActiveRecord
{
    use PapersQueryTrait;

    /**
     * Types of cash register receipt 
     */
    const TYPE_BAIL = 'bail';
    const TYPE_RENT = 'rent';

    public $is_company;

    public function __construct(int $is_company = 0)
    {
        $this->is_company = $is_company;
    }

    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return 'cash_register';
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
            [['related_order_id', 'base_number', 'payment_date'], 'integer'],
            [['related_type', 'base_prefix', 'actual_prefix', 'actual_number', 'full_number'], 'string'],
            [['related_order_id', 'related_type', 'full_number', 'actual_prefix', 'actual_number', 'base_prefix', 'base_number', 'payment_date'], 'required'],
            ['related_type', 'in', 'range' => array_keys(static::getRelatedTypes())],
        ];
    }
}
