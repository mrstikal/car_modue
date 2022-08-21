<?php

namespace app\modules\administrace\assets\company;

use yii\web\AssetBundle;

class CompanyAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/administrace/assets/company';
    public $css = [
        'css/company.css',
    ];
    public $js = [
        'js/dist/company.js'
    ];
    public $depends = [
        'yii\web\YiiAsset', 'app\modules\administrace\assets\functions\FunctionsAsset', 'app\modules\administrace\assets\admin\AdminAsset'
    ];
    public $publishOptions = [
        'forceCopy' => YII_ENV_DEV,
    ];
}
