<?php

namespace app\modules\pdf\helpers;

use app\modules\pdf\helpers\base\GeneralHelper;
use app\modules\order\models\Order;
use app\modules\options\models\OptionsTable;
use app\modules\country\models\Country;
use app\modules\car\models\Car;
use app\modules\pdf\models\NumerToWords;
use app\modules\administrace\helpers\DphHelper;

class CashRegisterVat extends GeneralHelper
{
    const DOCUMENT_SIZE = 'A6-L';

    public $orderId;
    public $order;
    public $ourCompany = [];
    public $customer = [];
    public $footer;
    public $type = '';
    public $documentName;
    public $footerMargin = 5;

    protected function setup()
    {
        $this->getOurCompany();
        $this->getOrder();
        $this->getCustomer();
        $this->getDocumentName();
        $this->setFooter();
    }

    protected function getDocumentName()
    {
        $this->documentName = $this->order["cash_register_{$this->type}_full_number"];
    }

    protected function fillMap()
    {
        if ($this->order['create_bail_invoice'] == 1) {
            $paymentNameBail = 'Úhrada faktury ' . $this->order["invoice_{$this->type}_full_number"];
        }

        if ($this->order['create_bail_invoice'] == 0) {
            $paymentNameBail = 'Úhrada vratné kauce za vozidlo ' . Car::getName($this->order['car_id']);
        }

        if ($this->order['create_invoice'] == 1) {
            $paymentNameRent = 'Úhrada faktury ' . $this->order["invoice_{$this->type}_full_number"];
        }

        if ($this->order['create_invoice'] == 0) {
            $paymentNameRent = 'Úhrada pronájmu vozidla ' . Car::getName($this->order['car_id']);
        }

        $cash_register_number = '<span class="cash_register_heading_prop">číslo: <strong>' . $this->order["cash_register_{$this->type}_full_number"] . '</strong>&nbsp;&nbsp;&nbsp;&nbsp;</span>';

        if ($this->type == 'bail') {
            $cash_register_number = '';
        }

        return [
            //cash register props
            '{cash_register_number}' => $cash_register_number,
            '{cash_register_price}' =>  $this->type == 'rent'
                ? \Yii::$app->formatter->asCurrency($this->order['price'], 'CZK', [\NumberFormatter::MAX_FRACTION_DIGITS => 0])
                : \Yii::$app->formatter->asCurrency($this->order['bail_value'], 'CZK', [\NumberFormatter::MAX_FRACTION_DIGITS => 0]),
            '{cash_register_price_words}' => $this->type == 'rent'
                ? NumerToWords::convertToWords($this->order['price'])
                : NumerToWords::convertToWords($this->order['bail_value']),
            '{cash_register_reason}' => $this->type == 'rent' ? $paymentNameRent : $paymentNameBail,
            '{cash_register_date}' => \Yii::$app->formatter->asDate($this->order["cash_register_{$this->type}_payment_date"], 'dd.MM.YYYY'),
            '{order_dph}' => \Yii::$app->formatter->asCurrency($this->order['dph'], 'CZK', [\NumberFormatter::MAX_FRACTION_DIGITS => 2]),
            '{order_dph_base}' => \Yii::$app->formatter->asCurrency($this->order['dph_base'], 'CZK', [\NumberFormatter::MAX_FRACTION_DIGITS => 2]),
            '{order_dph_amount}' => $this->ourCompany['dph_amount'] . '%',


            //our company
            '{we_ico}' => $this->ourCompany['ico'],
            '{we_dic}' =>  $this->ourCompany['dic'],
            '{we_company_name}' => $this->ourCompany['company_name'],
            '{we_company_street}' => $this->ourCompany['company_street'],
            '{we_company_town}' => $this->ourCompany['company_town'],
            '{we_zip}' => $this->ourCompany['zip'],
            '{we_state}' => $this->ourCompany['state'],
            '{we_infoline}' => $this->ourCompany['infoline'],
            '{we_web}' => $this->ourCompany['web'],

            //customer
            '{customer_name}' => $this->customer['name'],
            '{customer_street}' => $this->customer['street'],
            '{customer_city}' => $this->customer['city'],
            '{customer_state}' => $this->customer['state'],
            '{customer_ico}' => $this->customer['ico'],
            '{customer_dic}' => $this->customer['dic'],
        ];
    }

    private function getOrder()
    {
        $this->order = Order::getCompleteOrder($this->orderId);
        $this->order['price'] += intval($this->order['contractual_fine']);
        $this->order['bail_value'] += intval($this->order['contractual_fine']);
        $this->order['dph'] = $this->type == 'rent'
            ? DphHelper::calculateFromTop($this->order['price'], $this->ourCompany['dph_amount'])
            : DphHelper::calculateFromTop($this->order['bail_value'], $this->ourCompany['dph_amount']);
        $this->order['dph_base'] = $this->type == 'rent'
            ? $this->order['price'] - $this->order['dph']
            : $this->order['bail_value'] - $this->order['dph'];
    }

    private function getCustomer()
    {
        if (empty($this->order['different_bill_address'])) {

            $isCompany = $this->order['is_company'] == 1;

            $this->customer['name'] = $isCompany ? $this->order['company_name'] : $this->order['name'];
            $this->customer['street'] = $isCompany ? $this->order['company_street'] : $this->order['street'];
            $this->customer['city'] = $isCompany ? $this->order['company_zip'] . ' ' .  $this->order['company_town'] : $this->order['zip'] . ' ' .  $this->order['town'];
            $this->customer['state'] = $isCompany ? Country::getCountryName($this->order['company_state']) :  Country::getCountryName($this->order['state']);
            $this->customer['ico'] = $isCompany ? $this->order['ico'] : '';
            $this->customer['dic'] = $isCompany ? $this->order['dic'] : '';

            if (empty($this->customer['dic']) && $isCompany) $this->customer['dic'] = 'nepřiděleno';
            if (!$isCompany) $this->customer['dic'] = 'nepřiděleno';
        } else {
            $this->customer['name'] = $this->order['billing_name'];
            $this->customer['street'] = $this->order['billing_street'];
            $this->customer['city'] = $this->order['billing_zip'] . ' ' .  $this->order['billing_town'];
            $this->customer['state'] = Country::getCountryName($this->order['billing_state']);
            $this->customer['ico'] = !empty($this->order['billing_ico']) ? $this->order['billing_ico'] : '';
            $this->customer['dic'] = !empty($this->order['billing_dic']) ? $this->order['billing_dic'] : '';
        }
    }

    private function getOurCompany()
    {
        $this->ourCompany['dph'] = OptionsTable::getOption('fin_dph', 0);
        $this->ourCompany['dph_amount'] = OptionsTable::getOption('fin_dph_amount', 21);
        $this->ourCompany['account_number'] = OptionsTable::getOption('fin_account_number', '');
        $this->ourCompany['bank_code'] = OptionsTable::getOption('fin_bank_code', '');
        $this->ourCompany['ico'] = 'IČO: ' . OptionsTable::getOption('fin_ico', '');
        $this->ourCompany['dic'] = 'DIČ: ' . OptionsTable::getOption('fin_dic', '');
        $this->ourCompany['registration'] = OptionsTable::getOption('fin_registration', '');
        $this->ourCompany['company_name'] = OptionsTable::getOption('place_company_name', '');
        $this->ourCompany['company_street'] = OptionsTable::getOption('place_company_street', '');
        $this->ourCompany['company_town'] = OptionsTable::getOption('place_company_town', '');
        $this->ourCompany['zip'] = OptionsTable::getOption('place_zip', '');
        $this->ourCompany['state'] = OptionsTable::getOption('place_state', '');
        $this->ourCompany['infoline'] = OptionsTable::getOption('place_infoline', '');
        $this->ourCompany['web'] = OptionsTable::getOption('place_web', '');

        if ($this->ourCompany['dic'] == 'DIČ: ') $this->ourCompany['dic'] = 'není plátce DPH';
    }

    public function setFooter()
    {

        ob_start();
?>
        <div class="footer_wrapper">

            <div class="footer_half"><strong>Vydal: </strong></div>
            <div class="footer_half"><strong>Přijal: </strong></div>

        </div>
<?php
        $this->footer = ob_get_clean();
    }
}
