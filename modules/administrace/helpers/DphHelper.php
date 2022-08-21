<?php

namespace app\modules\administrace\helpers;

/**
 * Simple vat / price calculator helper.
 */

class DphHelper
{

    public static function calculateFromTop($price, $dph)
    {
        return $price - ($price / (($dph / 100) + 1));
    }
}
