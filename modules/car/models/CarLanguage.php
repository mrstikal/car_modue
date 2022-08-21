<?php

namespace app\modules\car\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\Query;

/**
 * Base model for car language table
 */
class CarLanguage extends ActiveRecord
{
    /**
     * Available statuses
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_DRAFT = 'draft';
    const STATUS_DELETED = 'deleted';

    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return 'car_language';
    }

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'name',
                'slugAttribute' => 'slug',
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [['language', 'name', 'description', 'slogan', 'status'], 'string'],
            ['car_id', 'integer'],
            [['language', 'name', 'car_id'], 'required'],
            ['status', 'in', 'range' => array_keys(static::getCarLanguageStatuses())],
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID jazykové mutace',
            'car_id' => 'ID vozidla',
            'language' => 'Jazyk',
            'name' => 'Název',
            'slug' => 'Slug (zatím nevyužito)',
            'slogan' => 'Slogan',
            'description' => 'Popis',
        ];
    }

    /**
     * Get language part for given car and language
     *
     * @param integer $carId
     * @param string $languageCode
     * @return array
     */
    public static function getTranslation(int $carId, string $languageCode): array
    {
        return (new Query)
            ->select('*')
            ->from(static::tableName())
            ->where(['car_id' => $carId])
            ->andWhere(['language' => $languageCode])
            ->one();
    }

    /**
     * Get language title for given car and language
     *
     * @param integer $carId
     * @param string $languageCode
     * @return array
     */
    public static function getTranslationTitle(int $carId, string $languageCode): array
    {
        return (new Query)
            ->select('name AS language_name')
            ->from(static::tableName())
            ->where(['car_id' => $carId])
            ->andWhere(['language' => $languageCode])
            ->one();
    }

    /**
     * Checks if language part exists for given car and language
     *
     * @param integer $carId
     * @param string $languageCode
     * @return boolean
     */
    public static function getTraslationExists(int $carId, string $languageCode): bool
    {
        return (bool) (new Query)
            ->select('*')
            ->from(static::tableName())
            ->where(['car_id' => $carId])
            ->andWhere(['language' => $languageCode])
            ->count();
    }

    /**
     * Returns available car translations statuses
     * status value => status label
     *
     * @return array
     */
    public static function getCarLanguageStatuses(): array
    {
        return [
            static::STATUS_ACTIVE => 'Aktivní (zobrazeno na webu)',
            static::STATUS_DRAFT => 'Neaktivní (nezobrazeno na webu)',
            static::STATUS_DELETED => 'Smazáno (lze obnovit)',
        ];
    }
}
