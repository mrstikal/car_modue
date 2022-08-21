<?php

namespace app\modules\administrace\bootstrap;

use yii\base\BootstrapInterface;
use app\modules\administrace\models\Languages;

class BootstrapLanguage implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $app->params['currentLanguage'] = Languages::getLanguage();
    }
}