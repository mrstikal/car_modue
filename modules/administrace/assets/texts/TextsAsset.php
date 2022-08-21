<?php

namespace app\modules\administrace\assets\texts;

use yii\web\AssetBundle;

class TextsAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/administrace/assets/texts';
    public $css = [
        'css/texts.css', 'css/editor-styles.css'
    ];
    public $js = [
        'js/texts.js'
    ];
    public $depends = [
        'yii\web\YiiAsset', 'app\modules\administrace\assets\functions\FunctionsAsset'
    ];
    public $publishOptions = [
        'forceCopy' => YII_ENV_DEV,
    ];
}
