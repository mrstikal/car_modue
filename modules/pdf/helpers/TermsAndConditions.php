<?php

namespace app\modules\pdf\helpers;

use app\modules\pdf\helpers\base\GeneralHelper;
use app\modules\options\models\OptionsTable;

class TermsAndConditions extends GeneralHelper
{
    const DOCUMENT_SIZE = 'A4';

    public $orderId;
    public $order;
    public $type = '';
    public $footerMargin = 10;
    public $headerMargin = 13;
    public $autoMarginPadding = 1;

    protected function setup()
    {
        $this->getDocumentName();
        $this->getOurCompany();
    }

    protected function getDocumentName()
    {
        $this->documentName = 'Půjčovna Tesla - VOP';
    }

    protected function fillMap()
    {
        return [
            '{vop_content}' => OptionsTable::getOption('texts_terms_and_conditions', ''),
            '{company_name}' => $this->ourCompany['company_name'],
            '{company_street}' => $this->ourCompany['company_street'],
            '{zip}' => $this->ourCompany['zip'],
            '{company_town}' => $this->ourCompany['company_town'],
            '{state}' => $this->ourCompany['state'],
            '{infoline}' => $this->ourCompany['infoline'],
            '{web}' => $this->ourCompany['web'],
        ];
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
        $this->ourCompany['address'] = $this->ourCompany['company_street'] . ', ' . $this->ourCompany['zip'] . ' ' . $this->ourCompany['company_town'] . ', ' . $this->ourCompany['state'];

    }

}
