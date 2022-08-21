<?php

namespace app\modules\administrace\traits;

use yii\db\Query;

/**
 * For papers (invoices, cash registers)
 */

trait PapersQueryTrait
{
    /**
     * Returns allowed paper types with labels.
     *
     * @return void
     */
    public static function getRelatedTypes()
    {
        return [
            static::TYPE_BAIL => 'Kauce',
            static::TYPE_RENT => 'Pronájem'
        ];
    }

    /**
     * Finds previous paper.
     *
     * @param integer $orderId
     * @param integer $baseNumber
     * @param string $basePrefix
     * @return array
     */
    public static function findPrevious(int $orderId = 0, int $baseNumber = 0, string $basePrefix = '')
    {
        return (new Query)
            ->select(['base_prefix', 'base_number', 'full_number'])
            ->from(self::tableName())
            ->where(['base_number' => $baseNumber])
            ->andWhere(['base_prefix' => $basePrefix])
            ->andWhere(['not', ['related_order_id' => $orderId]])
            ->orderBy(['id' => SORT_DESC])
            ->one();
    }

    /**
     * Finds one paper.
     *
     * @param integer $orderId
     * @param string $type
     * @return array
     */
    public static function findSingle(int $orderId, string $type)
    {
        return static::find()->where(['related_order_id' => $orderId])
            ->andWhere(['related_type' => $type])
            ->one();
    }
}
