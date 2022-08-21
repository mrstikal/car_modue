<?php

namespace app\modules\car\models;

use Yii;
use app\modules\car\models\Car;
use yii\behaviors\TimestampBehavior;
use app\modules\options\models\OptionsTable;

/**
 * Calculates car rental price
 */
class Price extends yii\base\Model
{

    /**
     * Car ID we use to calculate price
     *
     * @var int
     */
    public $carId;

    /**
     * Num of kilometers for calculation
     *
     * @var int
     */
    public $mileage;

    /**
     * Number of days for calculation
     *
     * @var int
     */
    public $days;

    /**
     * Price modifiers for selected car
     *
     * @var array
     */
    public $priceModifiers;

    /**
     * Use supplementary insurance?
     *
     * @var boolean
     */
    public $useRider = false;

    /**
     * Init class
     *
     * @param int $carId
     * @param int $mileage
     * @param int $days
     * @param mixed $useRider
     */
    public function __construct($carId, $mileage, $days, $useRider)
    {
        $this->carId = $carId;
        $this->mileage = $mileage;
        $this->days = $days;
        $this->useRider = $useRider;
        $this->setModifiers();
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
     * Creates class instance
     *
     * @param integer $carId
     * @param integer $mileage
     * @param integer $days
     * @return Price
     */
    public static function factory(int $carId, int $mileage, int $days, bool $useRider = false): Price
    {
        return new static($carId, $mileage, $days, $useRider);
    }

    /**
     * Sets price modifiers on class init
     *
     * @return void
     */
    public function setModifiers()
    {
        $this->priceModifiers = Car::getPriceModifiers($this->carId);
    }

    /**
     * Calculates final price for given car, mileage and days
     *
     * @return array
     */
    public function calculatePrice(): array
    {
        $rootPrice = $this->priceModifiers['use_action_price'] ? $this->priceModifiers['action_price'] : $this->priceModifiers['standard_price'];
        $rootPrice *= $this->days;

        //calculates mileage discount
        $mileageDiscount = $this->calculateMileagePrice()['finalDiscount'];

        //calculates days discount 
        $daysDiscount = $this->calculateDailyPrice()['finalDiscount'];

        //substract to final price
        $rawPrice = $rootPrice - $rootPrice * $daysDiscount - $rootPrice * $mileageDiscount;

        //if supplementary insurance
        if ($this->useRider) {
            $rawPrice += $rawPrice * $this->priceModifiers['surcharge_for_rider'] / 100;
        }

        //round by admin setting
        $rounder = OptionsTable::getOption('price_rounding', -1);
        $roundePrice = round($rawPrice, $rounder);

        //format price
        $formatedPrice = \Yii::$app->formatter->asCurrency($roundePrice, 'CZK', [\NumberFormatter::MAX_FRACTION_DIGITS => 0]);

        return [
            'rawPrice' => $rawPrice,
            'roundePrice' => $roundePrice,
            'formatedPrice' => $formatedPrice
        ];
    }

    /**
     * Calculates mileage params for final calculation
     *
     * @return mixed array or int
     */
    public function calculateMileagePrice()
    {
        return $this->calculateDiscountPrice('mileage');
    }

    /**
     * Calculates daily params for final calculation
     *
     * @return mixed array or ints
     */
    public function calculateDailyPrice()
    {
        return $this->calculateDiscountPrice('days');
    }

    /**
     * Calculates price for given mileage or days.
     * Math behind this calculation is quite complex,
     * so there are no comments on individual steps.
     * Just trust it, it works well.
     * 
     * @param string $type
     * @return mixed array or int
     */
    private function calculateDiscountPrice(string $type)
    {
        $basePrice = $this->priceModifiers['use_action_price'] ? $this->priceModifiers['action_price'] : $this->priceModifiers['standard_price'];

        $min = $this->priceModifiers[$type . '_lower_limit'];
        $max = $this->priceModifiers[$type . '_upper_limit'];

        $pricePerUnit = $basePrice / $min;

        $unitRange = $max - $min;
        $discountRange = $this->priceModifiers[$type . '_max_discount'] / 100;
        $discountDivider = $discountRange / $unitRange;

        $maxDiscount = $discountRange;

        if ($this->{$type} <= $min) {
            return [
                'finalPrice' => $this->{$type} * $pricePerUnit,
                'finalpricePerUnit' => $pricePerUnit,
                'finalDiscount' => 0,
            ];
        }

        if ($this->{$type} >= $max) {
            return [
                'finalPrice' => $this->{$type} * $pricePerUnit * $maxDiscount,
                'finalpricePerUnit' => $pricePerUnit,
                'finalDiscount' => $maxDiscount,
            ];
        }

        $rawDiscount = $discountDivider * ($this->{$type} - $min);
        $reversedRawDiscount = 1 - $rawDiscount;
        $cleanedReversedRawDiscount = $reversedRawDiscount - $maxDiscount;

        $unitCoefficient = ($max - $this->{$type}) / ($max - $min);

        $recalculatedUnitCoefficient = $cleanedReversedRawDiscount + $maxDiscount * $unitCoefficient;

        $poweredUnitCoefficient = pow($recalculatedUnitCoefficient, $this->priceModifiers[$type . '_coefficient']);

        $shiftedUnitCoefficient = 1 - $poweredUnitCoefficient * $unitCoefficient;

        $finalDiscount = $discountRange * $shiftedUnitCoefficient;

        $finalCoefficient = 1 - $finalDiscount;

        $finalpricePerUnit = $pricePerUnit * $finalCoefficient;

        $finalPrice = $finalpricePerUnit * $this->{$type};

        return [
            'finalPrice' => $finalPrice,
            'finalpricePerUnit' => $finalpricePerUnit,
            'finalDiscount' => $finalDiscount
        ];
    }

    /**
     * Static version of method above.
     * Needed for backend charts generation.
     *
     * @param string $type
     * @param integer $amount
     * @param array $params
     * @param bool $returnDiscount
     * @return integer
     */
    private static function calculateDiscount(string $type, int $amount, array $params, bool $returnDiscount = false)
    {
        foreach ($params as $key => $value) {
            $newKey = lcfirst(str_replace($type, '', $key));
            unset($params[$key]);
            $params[$newKey] = $value;
        }

        extract($params);

        $coefficient = (float) str_replace(',', '.', $coefficient);

        $min = $lowerLimit;
        $max = $upperLimit;

        $pricePerUnit = $basePrice / $min;

        $unitRange = $max - $min;
        $discountRange = $maxDiscount / 100;
        $discountDivider = $discountRange / $unitRange;

        $maxDiscount = $discountRange;

        if ($amount <= $min) {
            if ($returnDiscount) {
                return 0;
            } else {
                return $pricePerUnit;
            }
        }

        if ($amount >= $max) {
            if ($returnDiscount) {
                return $discountRange;
            } else {
                return $pricePerUnit - $pricePerUnit * $maxDiscount;
            }
        }

        $rawDiscount = $discountDivider * ($amount - $min);
        $reversedRawDiscount = 1 - $rawDiscount;
        $cleanedReversedRawDiscount = $reversedRawDiscount - $maxDiscount;

        $unitCoefficient = ($max - $amount) / ($max - $min);

        $recalculatedUnitCoefficient = $cleanedReversedRawDiscount + $maxDiscount * $unitCoefficient;

        if ($recalculatedUnitCoefficient < 0) $recalculatedUnitCoefficient = 0;

        $poweredUnitCoefficient = pow($recalculatedUnitCoefficient, $coefficient);

        $shiftedUnitCoefficient = 1 - $poweredUnitCoefficient * $unitCoefficient;

        $finalDiscount = $discountRange * $shiftedUnitCoefficient;

        $finalCoefficient = 1 - $finalDiscount;

        $finalpricePerUnit = $pricePerUnit * $finalCoefficient;

        if ($returnDiscount) {
            return $finalDiscount;
        }

        return $finalpricePerUnit;
    }

    /**
     * Calculates price for mileage chart
     *
     * @param array $params
     * @return array
     */
    public static function calculatePricesRangeMileage(array $params)
    {
        $numOfTicks = 10;

        $tickSize = ($params['mileageUpperLimit'] - $params['mileageLowerLimit']) / $numOfTicks;

        $ticks = [];

        $prices = [];

        for ($i = 0; $i <= $numOfTicks; $i++) {
            $ticks[] = $i * $tickSize + $params['mileageLowerLimit'];
        }

        foreach ($ticks as $tick) {
            $prices[] = self::calculateDiscount('mileage', $tick, $params);
        }

        return [
            $ticks, $prices
        ];
    }

    /**
     * Calculates price for days chart
     *
     * @param array $params
     * @return array
     */
    public static function calculatePricesRangeDays($params)
    {
        $numOfTicks = $params['daysUpperLimit'] - $params['daysLowerLimit'];

        $tickSize = 1;

        $ticks = [];

        $prices = [];

        for ($i = 0; $i <= $numOfTicks; $i++) {
            $ticks[] = $i * $tickSize + $params['daysLowerLimit'];
        }

        foreach ($ticks as $tick) {
            $prices[] = self::calculateDiscount('days', $tick, $params);
        }

        return [
            $ticks, $prices
        ];
    }

    /**
     * Calculates price for price test.
     *
     * @param array $params
     * @return string
     */
    public static function calculatePricesRangeFinal(array $params)
    {
        $rootPrice = $params['basePrice'] * $params['daysNum'];

        $mileageDiscount = self::calculateDiscount('mileage', $params['mileageNum'], $params, true);
        $daysDiscount = self::calculateDiscount('days', $params['daysNum'], $params, true);

        //substrac days discount
        $rawPrice = $rootPrice - $rootPrice * $daysDiscount  - $rootPrice * $mileageDiscount;

        //format price
        $formatedPrice = \Yii::$app->formatter->asCurrency(round($rawPrice, 2), 'CZK', [\NumberFormatter::MAX_FRACTION_DIGITS => 0]);

        return $formatedPrice;
    }
}
