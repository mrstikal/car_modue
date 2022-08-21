<?php

namespace app\modules\pdf\helpers;

use app\modules\pdf\helpers\base\GeneralHelper;
use app\modules\order\models\Order;
use app\modules\options\models\OptionsTable;
use app\modules\country\models\Country;
use app\modules\car\models\Car;
use app\modules\order\helpers\DatetimeHelper;

class Contract extends GeneralHelper
{
    const DOCUMENT_SIZE = 'A4';

    public $orderId;
    public $order;
    public $car;
    public $ourCompany = [];
    public $customer = [];
    public $type = '';
    public $documentName;
    public $footerMargin = 10;
    public $headerMargin = 13;
    public $autoMarginPadding = 1;

    protected function setup()
    {
        $this->getOrder();
        $this->getCustomer();
        $this->getOurCompany();
        $this->getDocumentName();
        $this->getCar();
        $this->setHeader();
        $this->setFooter();
    }

    protected function getDocumentName()
    {
        $this->documentName = 'Smlouva-' . $this->customer['name'];
    }

    protected function fillMap()
    {

        return [

            //our company
            '{we_ico}' => $this->ourCompany['ico'],
            '{we_dic}' => $this->ourCompany['dic'],
            '{we_company_address}' => $this->ourCompany['address'],
            '{we_company_name}' => $this->ourCompany['company_name'],
            '{we_company_street}' => $this->ourCompany['company_street'],
            '{we_company_town}' => $this->ourCompany['company_town'],
            '{we_zip}' => $this->ourCompany['zip'],
            '{we_state}' => $this->ourCompany['state'],
            '{we_infoline}' => $this->ourCompany['infoline'],
            '{we_web}' => $this->ourCompany['web'],
            '{we_company_address}' => $this->ourCompany['address'],
            '{we_dic_row}' => $this->ourCompany['dic_row'],

            //customer
            '{customer_name}' => $this->customer['name'],
            '{customer_street}' => $this->customer['street'],
            '{customer_city}' => $this->customer['city'],
            '{customer_state}' => $this->customer['state'],
            '{customer_ico}' => $this->customer['ico'],
            '{customer_dic}' => $this->customer['dic'],
            '{customer_table}' => $this->createCustomerTable(),

            '{contrac_text}' => $this->spanMe($this->pageBreak(OptionsTable::getOption('texts_contract', ''))),

            //car
            '{car_brand}' => 'Tesla',
            '{car_vin}' => $this->car['vin'],
            '{car_spz}' => $this->car['spz'],
            '{car_color}' => $this->car['color'],

            //order
            '{rent_start}' => \Yii::$app->formatter->asDate($this->order['lease_date_from'], 'dd.MM.YYYY'),
            '{rent_end}' => \Yii::$app->formatter->asDate($this->order['lease_date_to'], 'dd.MM.YYYY'),
            '{vehicle_handover}' => \Yii::$app->formatter->asDate($this->order['vehicle_handover_date'], 'dd.MM.YYYY') . ' ' . $this->order['vehicle_handover_time'] . ', ' . $this->order['vehicle_handover_place'],
            '{vehicle_return}' => \Yii::$app->formatter->asDate($this->order['vehicle_return_date'], 'dd.MM.YYYY') . ' ' . $this->order['vehicle_return_time'] . ', ' . $this->order['vehicle_return_place'],
            '{rent_days}' => DatetimeHelper::getNumOfDays($this->order['lease_date_from'], $this->order['lease_date_to']),
            '{rent_tachometer}' => \Yii::$app->formatter->asInteger($this->car['mileage_now']) . ' km',
            '{rent_rider}' => $this->order['use_rider'] ? 'Ano' : 'Ne',
            '{rent_mileage}' => \Yii::$app->formatter->asInteger($this->order['mileage']) . ' km',
            '{rent_bail}' => \Yii::$app->formatter->asCurrency($this->order['bail_value'], 'CZK', [\NumberFormatter::MAX_FRACTION_DIGITS => 0]),
        ];
    }

    private function getOrder()
    {
        $this->order = Order::getCompleteOrder($this->orderId);
    }

    private function getCar()
    {
        $this->car = Car::findSingle($this->order['car_id']);
    }

    private function getCustomer()
    {
        $isCompany = $this->order['is_company'] == 1;

        $this->customer['name'] = $isCompany ? $this->order['company_name'] : $this->order['name'];
        $this->customer['street'] = $isCompany ? $this->order['company_street'] : $this->order['street'];
        $this->customer['city'] = $isCompany ? $this->order['company_zip'] . ' ' .  $this->order['company_town'] : $this->order['zip'] . ' ' .  $this->order['town'];
        $this->customer['state'] = $isCompany ? Country::getCountryName($this->order['company_state']) :  Country::getCountryName($this->order['state']);
        $this->customer['ico'] = $isCompany ? $this->order['ico'] : '';
        $this->customer['dic'] = $isCompany ? $this->order['dic'] : '';

        $this->customer['deputy_name'] = $this->order['name'];
        $this->customer['deputy_address'] = $this->order['street'] . ', ' . $this->order['zip'] . ' ' .  $this->order['town'] . ', ' . Country::getCountryName($this->order['company_state']);
        $this->customer['deputy_birth_number'] = $this->order['birth_number'];
        $this->customer['deputy_identity_card_number'] = $this->order['identity_card_number'];
        $this->customer['phone'] = $this->order['phone'];
        $this->customer['birth_number'] = $this->order['birth_number'];
        $this->customer['permanent_residence'] = $this->order['permanent_residence'];

        if (empty($this->customer['dic']) && $isCompany) $this->customer['dic'] = 'nepřiděleno';
        if (!$isCompany) $this->customer['dic'] = 'nepřiděleno';
    }

    private function getOurCompany()
    {
        $this->ourCompany['dph'] = OptionsTable::getOption('fin_dph', 0);
        $this->ourCompany['dph_amount'] = OptionsTable::getOption('fin_dph_amount', 21);
        $this->ourCompany['account_number'] = OptionsTable::getOption('fin_account_number', '');
        $this->ourCompany['bank_code'] = OptionsTable::getOption('fin_bank_code', '');
        $this->ourCompany['ico'] = OptionsTable::getOption('fin_ico', '');
        $this->ourCompany['dic'] = OptionsTable::getOption('fin_dic', '');
        $this->ourCompany['registration'] = OptionsTable::getOption('fin_registration', '');
        $this->ourCompany['company_name'] = OptionsTable::getOption('place_company_name', '');
        $this->ourCompany['company_street'] = OptionsTable::getOption('place_company_street', '');
        $this->ourCompany['company_town'] = OptionsTable::getOption('place_company_town', '');
        $this->ourCompany['zip'] = OptionsTable::getOption('place_zip', '');
        $this->ourCompany['state'] = OptionsTable::getOption('place_state', '');
        $this->ourCompany['infoline'] = OptionsTable::getOption('place_infoline', '');
        $this->ourCompany['web'] = OptionsTable::getOption('place_web', '');
        $this->ourCompany['address'] = $this->ourCompany['company_street'] . ', ' . $this->ourCompany['zip'] . ' ' . $this->ourCompany['company_town'] . ', ' . $this->ourCompany['state'];

        if ($this->ourCompany['dic'] == 'DIČ: ') $this->ourCompany['dic'] = 'není plátce DPH';

        if ($this->ourCompany['dph'] == 1) {
            $this->ourCompany['dic_row'] = '<tr>
            <td class="left">DIČ:</td>
            <td class="right">' . $this->ourCompany['dic'] . '</td>
            </tr>';
        }

        if ($this->ourCompany['dph'] == 0) {
            $this->ourCompany['dic_row'] = '<tr>
            <td colspan=2 class="left">není plátce DPH</td>
            </tr>';
        }
    }

    private function spanMe($string)
    {
        return str_replace(['{', '}'], ['<span class="medium">{', '}</span>'], $string);
    }

    private function pageBreak($string)
    {
        return str_ireplace('{pagebreak}', '<pagebreak page-break-type="slice" />', $string);
    }

    public function createCustomerTable()
    {
        ob_start();
?>
        <?php if ($this->type == 'person') : ?>

            <tr>
                <td class="left" colspan=2>Soukromá osoba</td>
            </tr>

            <tr>
                <td class="left">Jméno a příjmení:</td>
                <td class="right"><?= $this->order['name']; ?></td>
            </tr>

            <tr>
                <td class="left">Rodné číslo:</td>
                <td class="right"><?= $this->order['birth_number']; ?></td>
            </tr>

            <tr>
                <td class="left">Číslo OP:</td>
                <td class="right"><?= $this->order['identity_card_number']; ?></td>
            </tr>

            <tr>
                <td class="left">Datum narození:</td>
                <td class="right"><?= $this->order['birth_date']; ?></td>
            </tr>

            <tr>
                <td class="left">Bydliště:</td>
                <td class="right"><?= $this->order['street'] . ', ' . $this->order['zip'] . ' ' .  $this->order['town'] . ', ' . Country::getCountryName($this->order['company_state']); ?></td>
            </tr>

            <tr>
                <td class="left">Tel.:</td>
                <td class="right"><?= $this->order['phone']; ?></td>
            </tr>


        <?php endif; ?>

        <?php if ($this->type == 'company') : ?>

            <tr>
                <td class="left" colspan=2>Fyzická osoba – podnikatel nebo společnost s r.o.</td>
            </tr>

            <tr>
                <td class="left">Společnost:</td>
                <td class="right"><?= $this->customer['name']; ?></td>
            </tr>

            <tr>
                <td class="left">Se sídlem:</td>
                <td class="right"><?= $this->customer['street'] . ', ' . $this->customer['city'] . ', ' . $this->customer['state']; ?></td>
            </tr>

            <tr>
                <td class="left">Zastoupená:</td>
                <td class="right"><?= $this->customer['deputy_name'] . ', ' . $this->customer['permanent_residence'] . ', RČ: ' . $this->customer['deputy_birth_number'] . ', OP: ' . $this->customer['deputy_identity_card_number']; ?></td>
            </tr>

            <tr>
                <td class="left">IČO:</td>
                <td class="right"><?= $this->customer['ico']; ?></td>
            </tr>

            <tr>
                <td class="left">DIČ:</td>
                <td class="right"><?= $this->customer['dic']; ?></td>
            </tr>

            <tr>
                <td class="left">Tel.:</td>
                <td class="right"><?= $this->customer['phone']; ?></td>
            </tr>

        <?php endif; ?>


    <?php

        return ob_get_clean();
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

    public function setHeader()
    {

        ob_start();
    ?>
        <span class="header_wrapper">

            <div class="logo">&nbsp;</div>
            <div class="aligner">
                <span class="document_type orange_fill orange_border">Smlouva o pronájmu vozidla</span>
            </div>
            <div class="header_divider orange_border"></div>

        </span>
        <div class="header_spacer">&nbsp;</div>
<?php
        $this->header = ob_get_clean();
    }
}
