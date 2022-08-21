<?php

namespace app\modules\administrace\assets\serviceinfos;

use yii\web\AssetBundle;

class ServiceInfoAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/administrace/assets/serviceinfos';
    public $css = [
        'css/service-infos.css',
        'css/vanillaSelectBox.css',
    ];
    public $js = [
        'js/dist/service-infos.js',
        'js/vanillaSelectBox.js',
    ];
    public $depends = [
        'yii\web\YiiAsset', 'app\modules\administrace\assets\functions\FunctionsAsset'
    ];
    public $publishOptions = [
        'forceCopy' => YII_ENV_DEV,
    ];
}
