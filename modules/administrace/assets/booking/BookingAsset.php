<?php

namespace app\modules\administrace\assets\booking;

use yii\web\AssetBundle;

class BookingAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/administrace/assets/booking';
    public $css = [
        'css/booking.css',
        'css/vanillaSelectBox.css',
        'css/datepicker.min.css',
        'css/bootstrap-datepicker.standalone.css',
    ];
    public $js = [
        'js/bootstrap-datepicker.min.js',
        'js/locales/bootstrap-datepicker.cs.min.js',
        'js/dist/booking.js',
        'js/datepicker-full.min.js',
        'js/locales/cs.js',
        'js/vanillaSelectBox.js',
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
