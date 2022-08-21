<?php

namespace app\modules\administrace\controllers;

use yii\web\Controller;
use app\modules\pdf\models\PdfModel;
use app\modules\order\models\Order;

/**
 * For testing purposes only.
 */

class PdfController extends Controller
{
    public $layout = 'plain.php';

    public function init()
    {

        parent::init();
    }

    public function actionIndex()
    {
        $order = Order::getCompleteOrder(1);

        $this->view->params['keyw'] = ['', ''];

        $source = PdfModel::factory()->createPdf('invoice-novat', 1, 'bail');
        $template = $source['file']->Output($source['name'], \Mpdf\Output\Destination::INLINE);
        exit;
    }
}
