<?php

namespace app\modules\administrace;

use app\assets\PolyfillAsset;
use app\modules\administrace\assets\admin\AdminAsset;
use yii\web\YiiAsset;

/**
 * administrace module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\administrace\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        PolyfillAsset::register(\Yii::$app->view);
        AdminAsset::register(\Yii::$app->view);
        parent::init();
    }
}
