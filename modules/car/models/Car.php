<?php

namespace app\modules\car\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Query;
use app\modules\car\models\CarLanguage;
use app\modules\options\models\OptionsTable;

/**
 * Base model for car table
 */
class Car extends ActiveRecord
{
    /**
     * Car tractions
     */
    const TRACTION_REAR_AXLE = 'rear_axle';
    const TRACTION_BOTH_AXLES = 'both_axles';

    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return 'car';
    }

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * @inheritDoc
     */
    public function beforeValidate()
    {
        //to be sure that value is float
        $this->mileage_coefficient = str_replace(',', '.', $this->mileage_coefficient);
        $this->days_coefficient = str_replace(',', '.', $this->days_coefficient);
        return parent::beforeValidate();
    }

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [['battery_capacity', 'traction', 'standard_price', 'spz', 'bail'], 'required'],
            [['traction', 'manufacturer'], 'string', 'max' => 128],
            [['current_condition', 'vin', 'color', 'tire_condition'], 'string'],
            [
                [
                    'battery_capacity', 'standard_price', 'action_price', 'bail', 'position', 'image',
                    'mileage_lower_limit', 'mileage_upper_limit', 'days_lower_limit', 'days_upper_limit', 'mileage_now'
                ],
                'integer'
            ],
            [['mileage_max_discount', 'days_max_discount'], 'integer', 'max' => 100],
            [['mileage_coefficient', 'days_coefficient'], 'number'],
            [['spz'], 'string', 'max' => 32],
            ['action_price', 'required', 'when' => function ($model) {
                return isset($model->use_action_price) && $model->use_action_price == 1;
            }]
        ];
    }

    /**
     * Returns available car tractions
     *
     * @return array
     */
    public static function getTractions(): array
    {
        return [
            static::TRACTION_REAR_AXLE => 'Zadní náprava',
            static::TRACTION_BOTH_AXLES => 'Obě nápravy',
        ];
    }

    /**
     * Returns price factor fields from car model
     *
     * @param integer $carId
     * @return object
     */
    public static function getPriceModifiers(int $carId): array
    {
        return (new Query)
            ->select(['mileage_max_discount', 'mileage_lower_limit', 'mileage_upper_limit', 'mileage_coefficient'])
            ->addSelect(['days_max_discount', 'days_lower_limit', 'days_upper_limit', 'days_coefficient'])
            ->addSelect(['standard_price', 'action_price', 'use_action_price'])
            ->addSelect(['surcharge_for_rider'])
            ->from(static::tableName())
            ->andWhere(['id' => $carId])
            ->one();
    }

    public static function factory()
    {
        return new static;
    }

    /**
     * Base query for car model
     *
     * @return Query instance of query builder
     */
    public static function findMany(): Query
    {
        return (new Query)
            ->select([
                'car.*',
                'language.id AS language_id', 'language.language AS language', 'language.name AS language_name', 'language.slug AS language_slug', 'language.slogan AS language_slogan',
                'language.description AS language_description', 'language.created_at AS language_created_at', 'language.updated_at AS language_updated_at', 'language.status AS language_status',
            ])
            ->from(static::tableName() . ' car')
            ->leftJoin(CarLanguage::tableName() . ' language', 'language.car_id = car.id')
            ->where(['language' => Yii::$app->params['currentLanguage']])
            ->andWhere(['language.status' => [CarLanguage::STATUS_ACTIVE, CarLanguage::STATUS_DRAFT]])
            ->orderBy('car.position');
    }

    /**
     * Finds single car record.
     *
     * @param int $id
     * @return array
     */
    public static function findSingle(int $id)
    {
        return (new Query)
            ->select([
                'car.*',
                'language.id AS language_id', 'language.language AS language', 'language.name AS language_name', 'language.slug AS language_slug', 'language.slogan AS language_slogan',
                'language.description AS language_description', 'language.created_at AS language_created_at', 'language.updated_at AS language_updated_at', 'language.status AS language_status',
            ])
            ->from(static::tableName() . ' car')
            ->leftJoin(CarLanguage::tableName() . ' language', 'language.car_id = car.id')
            ->where(['car.id' => $id])
            ->andWhere(['language.language' => Yii::$app->params['currentLanguage']])
            ->andWhere(['language.status' => [CarLanguage::STATUS_ACTIVE, CarLanguage::STATUS_DRAFT]])
            ->one();
    }

    /**
     * Get car name from language versions table.
     *
     * @param int $id
     * @return string
     */
    public static function getName(int $id)
    {
        return (new Query)
            ->select([
                'language.name AS language_name'
            ])
            ->from(static::tableName() . ' car')
            ->leftJoin(CarLanguage::tableName() . ' language', 'language.car_id = car.id')
            ->where(['car.id' => $id])
            ->andWhere(['language.language' => Yii::$app->params['currentLanguage']])
            ->scalar();
    }

    /**
     * Reduced query for cars list (index page)
     *
     * @return Query
     */
    public static function findList(): Query
    {
        return (new Query)
            ->select([
                'car.id', 'language.id AS language_id',
                'language.car_id AS language_car_id', 'language.name AS language_name', 'language.slug AS language_slug', 'language.status AS language_status',
            ])
            ->from(static::tableName() . ' car')
            ->leftJoin(CarLanguage::tableName() . ' language', 'language.car_id = car.id')
            ->where(['language' => Yii::$app->params['currentLanguage']])
            ->andWhere(['language.status' => [CarLanguage::STATUS_ACTIVE, CarLanguage::STATUS_DRAFT]])
            ->orderBy('car.position');
    }

    /**
     * Find cars for admin filter
     *
     * @return array
     */
    public static function findForFilter(): array
    {
        return (new Query)
            ->select([
                'car.id',
                'language.name AS language_name',
            ])
            ->from(static::tableName() . ' car')
            ->leftJoin(CarLanguage::tableName() . ' language', 'language.car_id = car.id')
            ->where(['language' => Yii::$app->params['currentLanguage']])
            ->andWhere(['language.status' => [CarLanguage::STATUS_ACTIVE, CarLanguage::STATUS_DRAFT]])
            ->orderBy(['car.created_at' => SORT_ASC])
            ->all();
    }

    /**
     * Gets car bail value.
     *
     * @param int $carId
     * @param mixed $useRider
     * @return array
     */
    public static function getBail($carId, $useRider)
    {
        $query = (new Query)
            ->from(static::tableName())
            ->where(['id' => $carId]);

        if ($useRider) {
            $query->select('bail_with_rider');
        } else {
            $query->select('bail');
        }

        $rawPrice = $query->scalar();

        if ($rawPrice === false) {
            $rawPrice = OptionsTable::getOption('bail_with_rider', 25000);
        }

        $formatedPrice = \Yii::$app->formatter->asCurrency($rawPrice, 'CZK', [\NumberFormatter::MAX_FRACTION_DIGITS => 0]);

        return ['formated_price' => $formatedPrice, 'raw_price' => $rawPrice];
    }
}
