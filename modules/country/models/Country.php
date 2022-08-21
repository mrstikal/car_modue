<?php

namespace app\modules\country\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * Base model for car table
 */
class Country extends ActiveRecord
{
    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return 'country';
    }

    public static function getCountryName($id)
    {
        return (new Query)
            ->select('title_native')
            ->from(self::tableName())
            ->where(['id' => $id])
            ->scalar();
    }
}
