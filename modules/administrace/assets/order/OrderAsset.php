<?php

namespace app\modules\administrace\assets\order;

use yii\web\AssetBundle;

class OrderAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/administrace/assets/order';
    public $css = [
        'css/order.css',
        'css/bootstrap-datepicker.standalone.css',
    ];
    public $js = [
        'js/dist/order.js',
        'js/bootstrap-datepicker.min.js',
        'js/locales/bootstrap-datepicker.cs.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset', 'app\modules\administrace\assets\functions\FunctionsAsset'
    ];
    public $publishOptions = [
        'forceCopy' => YII_ENV_DEV,
    ];
}
