<?php

namespace app\modules\pdf\models;

use yii\base\Model;
use yii\helpers\Inflector;

/**
 * Creatres pdf file on demand
 */
class PdfModel extends Model
{
    /**
     * Pdf helpers location
     */
    const HELPER_NAMESPACE = '\app\modules\pdf\helpers\\';

    /**
     * Helper class for final file rendering
     *
     * @var class
     */
    public $helper;

    /**
     * Final pdf file namespace
     *
     * @var string
     */
    public $documentName;

    /**
     * Creates instance of this class
     *
     * @return class PdfModel
     */
    public static function factory()
    {
        return new static();
    }

    /**
     * For testing purposes only
     */
    public function testPdf($pattern, $orderId, $type = 'rent')
    {
        $helperName = Inflector::id2camel($pattern);
        $this->helper = (self::HELPER_NAMESPACE . $helperName)::factory($pattern);

        $this->helper->orderId = $orderId;
        $this->helper->type = $type;

        $html = $this->helper->getHtml();

        return $html;
    }

    /**
     * Creates final pdf document
     *
     * @param string $pattern
     * @param int $orderId
     * @param string $type
     * @return array
     */
    public function createPdf($pattern, $orderId, $type = 'rent')
    {
        $helperName = Inflector::id2camel($pattern);
        $this->helper = (self::HELPER_NAMESPACE . $helperName)::factory($pattern);

        $this->helper->orderId = $orderId;
        $this->helper->type = $type;

        $html = $this->helper->getHtml();
        $documentName = $this->helper->documentName;

        $mpdf = new \Mpdf\Mpdf(
            [
                'format' => $this->helper::DOCUMENT_SIZE,
                'margin_left' => 0,
                'margin_right' => 0,
                'margin_top' => 0,
                'margin_bottom' => 0,
                'dpi' => 120,
                'img_dpi' => 96,
                'default_font' => 'roboto',
                'allow_output_buffering' => true,
                'margin_footer' => $this->helper->footerMargin,
                'margin_header' => $this->helper->headerMargin,
            ]
        );

        $mpdf->setAutoTopMargin = 'stretch';
        $mpdf->setAutoBottomMargin = 'stretch';

        if ($this->helper->autoMarginPadding) {
            $mpdf->autoMarginPadding = $this->helper->autoMarginPadding;
        }

        if ($this->helper->header) {
            $mpdf->SetHTMLHeader($this->helper->header);
        }

        if ($this->helper->footer) {
            $mpdf->SetHTMLFooter($this->helper->footer);
        }

        $mpdf->WriteHTML($html);

        return ['file' => $mpdf, 'name' => $documentName];
    }
}
