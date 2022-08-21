<?php

namespace app\modules\pdf\helpers;

use app\modules\pdf\helpers\base\GeneralHelper;
use app\modules\options\models\OptionsTable;

class CarCheck extends GeneralHelper
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
        $this->setHeader();
    }

    protected function getDocumentName()
    {
        $this->documentName = 'Kontrola vozidla';
    }

    protected function fillMap()
    {
        return [
            '{texts_basic_check}' => $this->createRows(OptionsTable::getOption('texts_basic_check', '')),
            '{texts_consumables_check}' => $this->createRows(OptionsTable::getOption('texts_consumables_check', '')),
        ];
    }

    protected function createRows($fill)
    {
        $fill = explode(';', $fill);
        $fill = array_map('trim', $fill);
        $fill = array_filter($fill);

        ob_start();
?>
        <div class="all_rows">

            <?php foreach ($fill as $row) : ?>

                <div class="one_row">
                    <span class="one_row_triangle"><svg class="svg_triangle" width='2.8mm' height='3.3mm'>
                            <path fill="#aaaaaa" d="M 0,3 8,7.7 0,12 z" />
                        </svg></span>
                    <span class="one_row_name"><?= $row; ?></span>
                    <span class="one_row_name">&nbsp;</span>
                    <span class="one_row_check">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                </div>

            <?php endforeach; ?>

        </div>

    <?php
        return ob_get_clean();
    }

    public function setHeader()
    {

        ob_start();
    ?>
        <span class="header_wrapper">

            <div class="logo">&nbsp;</div>
            <div class="aligner">
                <span class="document_type orange_fill orange_border">Kontrola vozidla</span>
            </div>
            <div class="header_divider orange_border"></div>

        </span>
        <div class="header_spacer">&nbsp;</div>
<?php
        $this->header = ob_get_clean();
    }
}
