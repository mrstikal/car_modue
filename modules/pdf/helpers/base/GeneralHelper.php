<?php

namespace app\modules\pdf\helpers\base;

use yii\helpers\Url;

abstract class GeneralHelper
{
    public $pattern;
    public $html;
    public $header = false;
    public $footer = false;
    public $autoMarginPadding = false;
    public $footerMargin = 0;
    public $headerMargin = 0;

    public function __construct($pattern)
    {
        $this->pattern = $pattern;
    }

    abstract protected function fillMap();
    abstract protected function setup();
    abstract protected function getDocumentName();

    public static function factory($pattern)
    {
        return new static($pattern);
    }

    public function getCssFile()
    {
        return file_get_contents(dirname(__DIR__, 2) . '/patterns/css/' . $this->pattern . '.css');
    }

    public function getCommonCss()
    {
        return file_get_contents(dirname(__DIR__, 2) . '/patterns/css/common.css');
    }

    public function getPattern()
    {
        $paternDir = dirname(__DIR__, 2) . '/patterns/' . $this->pattern . '.php';
        return file_get_contents($paternDir);
    }

    public static function getLogo()
    {
        return '.logo { background-image: url(' . Url::base(true) . \Yii::$app->params['pathToUploadsAdmin'] . 'logo.svg)}';
    }

    public function getHtml()
    {
        $this->setup();

        $html = $this->getPattern($this->pattern);
        $cssFile = $this->getCssFile($this->pattern);
        $commonCssFile = $this->getCommonCss();

        $logo = self::getLogo();

        $css = '<style>' . $logo . $commonCssFile . $cssFile . '</style>';

        $this->html = str_ireplace('{css_styles}', $css, $html);

        $map = $this->fillMap();

        foreach ($map as $source => $replacement) {
            $this->html = str_ireplace($source, $replacement, $this->html);
        }

        return $this->html;
    }
}
