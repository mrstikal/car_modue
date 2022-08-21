<?php

namespace app\modules\pdf\helpers;

use app\modules\pdf\helpers\base\GeneralHelper;
use app\modules\order\models\Order;
use app\modules\options\models\OptionsTable;
use app\modules\country\models\Country;
use app\modules\car\models\Car;
use app\modules\administrace\helpers\DphHelper;

class InvoiceVat extends GeneralHelper
{
    const DOCUMENT_SIZE = 'A4';

    public $orderId;
    public $order;
    public $ourCompany = [];
    public $customer = [];
    public $footer;
    public $type = '';
    public $documentName;
    public $footerMargin = 10;

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
        $this->documentName = $this->order["invoice_{$this->type}_full_number"];
    }

    protected function fillMap()
    {
        return [
            //invoice props
            '{invoice_number}' => $this->order["invoice_{$this->type}_full_number"],
            '{payment_method}' => Order::getOrderPaymentMethods()[$this->order["invoice_{$this->type}_payment_method"]],
            '{variable_symbol}' => $this->order["invoice_{$this->type}_variable_symbol"],
            '{issue_date}' => \Yii::$app->formatter->asDate($this->order["invoice_{$this->type}_issue_date"], 'dd.MM.YYYY'),
            '{due_at}' => \Yii::$app->formatter->asDate($this->order["invoice_{$this->type}_due_at"], 'dd.MM.YYYY'),
            '{supply_date}' => \Yii::$app->formatter->asDate($this->order["invoice_{$this->type}_supply_date"], 'dd.MM.YYYY'),

            //invoice price table
            '{car_sentence}' => $this->type == 'rent' ? 'Pronájem vozidla ' . Car::getName($this->order['car_id']) : 'Vratná kauce ' .  Car::getName($this->order['car_id']),
            '{car_name}' => Car::getName($this->order['car_id']),
            '{order_price}' => $this->type == 'rent'
                ? \Yii::$app->formatter->asCurrency($this->order['price'], 'CZK', [\NumberFormatter::MAX_FRACTION_DIGITS => 0])
                : \Yii::$app->formatter->asCurrency($this->order['bail_value'], 'CZK', [\NumberFormatter::MAX_FRACTION_DIGITS => 0]),
            '{order_dph}' => \Yii::$app->formatter->asCurrency($this->order['dph'], 'CZK', [\NumberFormatter::MAX_FRACTION_DIGITS => 2]),
            '{order_dph_base}' => \Yii::$app->formatter->asCurrency($this->order['dph_base'], 'CZK', [\NumberFormatter::MAX_FRACTION_DIGITS => 2]),
            '{order_dph_amount}' => $this->ourCompany['dph_amount'] . '%',

            //our company
            '{we_dph}' => $this->ourCompany['dph'],
            '{we_dph_amount}' => $this->ourCompany['dph_amount'],
            '{we_account_number}' => $this->ourCompany['account_number'],
            '{we_bank_code}' => $this->ourCompany['bank_code'],
            '{we_ico}' => $this->ourCompany['ico'],
            '{we_dic}' => $this->ourCompany['dic'],
            '{we_registration}' => $this->ourCompany['registration'],
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
            $this->customer['ico'] = $isCompany ? 'IČO: ' . $this->order['ico'] : '';
            $this->customer['dic'] = $isCompany ? 'DIČ: ' . $this->order['dic'] : '';

            if ($this->customer['dic'] == 'DIČ: ' && $isCompany) $this->customer['dic'] = 'DIČ: nepřiděleno';
        } else {
            $this->customer['name'] = $this->order['billing_name'];
            $this->customer['street'] = $this->order['billing_street'];
            $this->customer['city'] = $this->order['billing_zip'] . ' ' .  $this->order['billing_town'];
            $this->customer['state'] = Country::getCountryName($this->order['billing_state']);
            $this->customer['ico'] = !empty($this->order['billing_ico']) ? 'IČO: ' . $this->order['billing_ico'] : '';
            $this->customer['dic'] = !empty($this->order['billing_dic']) ? 'DIČ: ' . $this->order['billing_dic'] : '';
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

            <div class="registration"><?= $this->ourCompany['registration']; ?></div>

            <div class="invoice_divider orange_border"></div>

            <table width="100%" class="footer_table">
                <tr>
                    <td width="50%" class="orange_text"><strong><?= $this->ourCompany['company_name']; ?></strong></td>
                    <td></td>
                </tr>

                <tr>
                    <td width="50%"><?= $this->ourCompany['company_street']; ?></td>
                    <td></td>
                </tr>

                <tr>
                    <td width="50%"><?= $this->ourCompany['zip'] . ' ' . $this->ourCompany['company_town']; ?></td>
                    <td style="text-align: right;">Zákaznická linka: <?= $this->ourCompany['infoline']; ?></td>
                </tr>

                <tr>
                    <td width="50%"><?= $this->ourCompany['state']; ?></td>
                    <td style="text-align: right;"><?= $this->ourCompany['web']; ?></td>
                </tr>
            </table>

        </div>
<?php
        $this->footer = ob_get_clean();
    }
}
