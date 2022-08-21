<?php

namespace app\modules\pdf\helpers;

use app\modules\pdf\helpers\base\GeneralHelper;
use app\modules\order\models\Order;
use app\modules\options\models\OptionsTable;
use app\modules\country\models\Country;
use app\modules\car\models\Car;
use app\modules\order\helpers\DatetimeHelper;

class TransferProtocol extends GeneralHelper
{
    const DOCUMENT_SIZE = 'A4';

    public $orderId;
    public $order;
    public $type = '';
    public $footerMargin = 10;
    public $headerMargin = 13;
    public $autoMarginPadding = 0;

    protected function setup()
    {
        $this->getOrder();
        $this->getCustomer();
        $this->getOurCompany();
        $this->getDocumentName();
        $this->getCar();
        $this->setHeader();
    //    $this->setFooter();
    }

    protected function getDocumentName()
    {
        $this->documentName = 'Předávací protokol - ' . $this->customer['name'];
    }

    protected function fillMap()
    {
        return [
            '{car_name}' => $this->car['language_name'],
            '{customer_name}' => $this->customer['name'],
            '{car_spz}' => $this->car['spz'],
            '{birth_number}' => $this->customer['birth_number'],
            '{permanent_residence}' => $this->customer['address'],
            '{mileage_now}' => $this->car['mileage_now'],
            '{tires_front_right}' => $this->car['tire_condition']['tires_front_right'],
            '{tires_front_left}' => $this->car['tire_condition']['tires_front_left'],
            '{tires_back_right}' => $this->car['tire_condition']['tires_back_right'],
            '{tires_back_left}' => $this->car['tire_condition']['tires_back_left'],
            '{tires_spare}' => $this->car['tire_condition']['tires_spare'],
            '{current_condition}' => nl2br($this->car['current_condition']),
            '{vehicle_handover_date}' => \Yii::$app->formatter->asDate($this->order['vehicle_handover_date'], 'dd.MM.YYYY'),
            '{vehicle_return_date}' => \Yii::$app->formatter->asDate($this->order['vehicle_return_date'], 'dd.MM.YYYY'),
            '{company_name}' => $this->ourCompany['company_name'],
            '{inner_space}' => str_repeat('&nbsp;', 40),
            '{inner_space_more}' => str_repeat('&nbsp;', 60),
        ];
    }

    private function getOrder()
    {
        $this->order = Order::getCompleteOrder($this->orderId);
    }

    private function getCar()
    {
        $this->car = Car::findSingle($this->order['car_id']);
        $this->car['tire_condition'] = json_decode($this->car['tire_condition'], true);
    }

    private function getCustomer()
    {
        $isCompany = $this->order['is_company'] == 1;

        $this->customer['name'] = $this->order['name'];
        $this->customer['street'] = $this->order['street'];
        $this->customer['city'] = $this->order['zip'] . ' ' .  $this->order['town'];
        $this->customer['state'] = Country::getCountryName($this->order['state']);
        $this->customer['birth_number'] = $this->order['birth_number'];
        $this->customer['full_address'] = $this->customer['street'] . ', ' . $this->customer['city'] . ', ' . $this->customer['state'];
        $this->customer['address'] = $isCompany ? $this->order['permanent_residence'] : $this->customer['full_address'];
    }

    private function getOurCompany()
    {
        $this->ourCompany['company_name'] = OptionsTable::getOption('place_company_name', '');
        $this->ourCompany['company_street'] = OptionsTable::getOption('place_company_street', '');
        $this->ourCompany['company_town'] = OptionsTable::getOption('place_company_town', '');
        $this->ourCompany['zip'] = OptionsTable::getOption('place_zip', '');
        $this->ourCompany['state'] = OptionsTable::getOption('place_state', '');
        $this->ourCompany['infoline'] = OptionsTable::getOption('place_infoline', '');
        $this->ourCompany['web'] = OptionsTable::getOption('place_web', '');
    }

    private function spanMe($string)
    {
        return '<span class="medium">' . $string . '</span>';
    }

    public function setHeader()
    {

        ob_start();
?>
        <span class="header_wrapper">

            <div class="logo">&nbsp;</div>
            <div class="aligner">
                <span class="document_type orange_fill orange_border">Předávací protokol</span>
            </div>
            <div class="header_divider orange_border"></div>

        </span>
        <div class="header_spacer">&nbsp;</div>
<?php
        $this->header = ob_get_clean();
    }

    public function setFooter()
    {
        ob_start();
    ?>
        <div class="footer_wrapper">

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
