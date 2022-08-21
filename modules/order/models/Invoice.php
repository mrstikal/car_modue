<?php

namespace app\modules\order\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use app\modules\administrace\traits\PapersQueryTrait;

/**
 * Base model for invoice table.
 */
class Invoice extends ActiveRecord
{
    use PapersQueryTrait;

    /**
     * Types of cash register receipt 
     */
    const TYPE_BAIL = 'bail';
    const TYPE_RENT = 'rent';

    /**
     * Is customer company?
     */
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
        return 'invoice';
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
            [['related_order_id', 'base_number', 'issue_date', 'supply_date', 'due_at'], 'integer'],
            [['related_type', 'payment_method', 'base_prefix', 'actual_prefix', 'actual_number', 'full_number', 'variable_symbol'], 'string'],
            [['related_order_id', 'related_type', 'base_number', 'base_prefix', 'actual_prefix', 'actual_number', 'payment_method', 'full_number', 'issue_date', 'supply_date', 'due_at', 'variable_symbol'], 'required'],
            ['related_type', 'in', 'range' => array_keys(static::getRelatedTypes())],
        ];
    }
}
