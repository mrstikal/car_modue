<?php

namespace app\modules\administrace\assets\cars;

use yii\web\AssetBundle;

class CarAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/administrace/assets/cars';
    public $css = [
        'css/cars.css',
        'css/highcharts.css',
        'css/vanillaSelectBox.css',

    ];
    public $js = [
        'js/dist/cars.js',
        'js/highcharts.min.js',
        'js/sortable.min.js',
        'js/vanillaSelectBox.js',
    ];
    public $depends = [
        'yii\web\YiiAsset', 'app\modules\administrace\assets\functions\FunctionsAsset'
    ];
    public $publishOptions = [
        'forceCopy' => YII_ENV_DEV,
    ];
}
