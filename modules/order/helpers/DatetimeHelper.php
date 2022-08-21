<?php

namespace app\modules\order\helpers;

use Carbon\Carbon;

/**
 * Simple helper for start and end dates.
 */
class DatetimeHelper
{
    public static function startFrom(int $timestamp, bool $returnFormated = true)
    {
        $startOfDay = Carbon::createFromTimestamp($timestamp)->startOfDay()->timestamp;

        if ($returnFormated) {
            return \Yii::$app->formatter->asDate($startOfDay, 'dd.MM.YYYY');
        }

        return $startOfDay;
    }

    public static function endOn(int $timestamp, bool $returnFormated = true)
    {
        $endOfDay = Carbon::createFromTimestamp($timestamp)->endOfDay()->timestamp;

        if ($returnFormated) {
            return \Yii::$app->formatter->asDate($endOfDay, 'dd.MM.YYYY');
        }

        return $endOfDay;
    }

    public static function getNumOfDays(int $startTimestamp, int $endTimestamp)
    {
        $start = Carbon::createFromTimestamp($startTimestamp);
        $end = Carbon::createFromTimestamp($endTimestamp);

        return round($start->floatDiffInDays($end) - 1);
    }
}
