<?php

namespace app\modules\administrace\assets\fullcalendar;

use yii\web\AssetBundle;

class FullCallendarAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/administrace/assets/fullcalendar';
    public $css = [
        'css/main.css', 'css/styles.css', 'css/vanillaSelectBox.css'
    ];
    public $js = [
        'js/dist/fullcalendar.js', 'js/main.min.js', 'js/locales-all.min.js', 'js/vanillaSelectBox.js'
    ];
    public $depends = [
        'yii\web\YiiAsset', 'app\modules\administrace\assets\functions\FunctionsAsset', 'app\modules\administrace\assets\admin\AdminAsset'
    ];
    public $publishOptions = [
        'forceCopy' => YII_ENV_DEV,
    ];
}
