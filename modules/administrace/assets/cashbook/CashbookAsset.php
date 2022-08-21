<?php

namespace app\modules\administrace\assets\cashbook;

use yii\web\AssetBundle;

class CashbookAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/administrace/assets/cashbook';
    public $css = [
        'css/cashbook.css',
        'css/bootstrap-datepicker.standalone.css',
        'css/datepicker.min.css',
    ];
    public $js = [
        'js/bootstrap-datepicker.min.js',
        'js/locales/bootstrap-datepicker.cs.min.js',
        'js/datepicker-full.min.js',
        'js/locales/cs.js',
        'js/dist/cashbook.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'app\assets\AppAsset',
        'app\modules\administrace\assets\functions\FunctionsAsset',
        'app\modules\administrace\assets\admin\AdminAsset'
    ];
    public $publishOptions = [
        'forceCopy' => YII_ENV_DEV,
    ];
}
