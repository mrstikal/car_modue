<?php

namespace app\modules\administrace\controllers;

use app\modules\common\components\Response;
use app\modules\options\models\OptionsTable;
use yii\web\Controller;

/**
 * Performs actions for text used in pdfs
 */

class TextsController extends Controller
{
    public $layout = 'main.php';

    /**
     * Renders contract text page.
     *
     * @return View
     */
    public function actionEditContract()
    {
        $this->view->params['keyw'] = ['texts', 'contract'];

        $contract = OptionsTable::getOption('texts_contract', '');

        return $this->render('contract', ['contract' => $contract]);
    }

    /**
     * Saves contract text.
     *
     * @return Response
     */
    public function actionSaveContract()
    {
        $optionValue = \Yii::$app->request->post('texts_contract');

        $model = OptionsTable::find()->where(['name' => 'texts_contract'])->one();

        if (empty($model)) $model = new OptionsTable;

        $model->name = 'texts_contract';
        $model->value = $optionValue;
        $model->save();

        $response = Response::getResponseBase();

        $response->statusCode = 200;
        $response->data = \Yii::$app->request->post();

        return $response;
    }

    /**
     * Renders terms and conditions text page.
     *
     * @return View
     */
    public function actionEditTermsAndConditions()
    {
        $this->view->params['keyw'] = ['texts', 'terms_and_conditions'];

        $terms_and_conditions = OptionsTable::getOption('texts_terms_and_conditions', '');

        return $this->render('terms-and-conditions', ['terms_and_conditions' => $terms_and_conditions]);
    }

    /**
     * Saves terms and conditions text.
     *
     * @return Response
     */
    public function actionSaveTermsAndConditions()
    {
        $optionValue = \Yii::$app->request->post('texts_terms_and_conditions');

        $model = OptionsTable::find()->where(['name' => 'texts_terms_and_conditions'])->one();

        if (empty($model)) $model = new OptionsTable;

        $model->name = 'texts_terms_and_conditions';
        $model->value = $optionValue;
        $model->save();

        $response = Response::getResponseBase();

        $response->statusCode = 200;
        $response->data = \Yii::$app->request->post();

        return $response;
    }

    /**
     * Renders car checklist page.
     *
     * @return View
     */
    public function actionEditCarCheck()
    {
        $this->view->params['keyw'] = ['texts', 'car_check'];

        $basicCheck = OptionsTable::getOption('texts_basic_check', '');
        $consumablesCheck = OptionsTable::getOption('texts_consumables_check', '');

        return $this->render('car-check', ['basicCheck' => $basicCheck, 'consumablesCheck' => $consumablesCheck]);
    }

    /**
     * Saves car checklist page.
     *
     * @return Response
     */
    public function actionSaveCarCheck()
    {
        $optionValue = \Yii::$app->request->post('texts_basic_check');

        $model = OptionsTable::find()->where(['name' => 'texts_basic_check'])->one();

        if (empty($model)) $model = new OptionsTable;

        $model->name = 'texts_basic_check';
        $model->value = $optionValue;
        $model->save();

        $optionValue = \Yii::$app->request->post('texts_consumables_check');

        $model = OptionsTable::find()->where(['name' => 'texts_consumables_check'])->one();

        if (empty($model)) $model = new OptionsTable;

        $model->name = 'texts_consumables_check';
        $model->value = $optionValue;
        $model->save();

        $response = Response::getResponseBase();

        $response->statusCode = 200;
        $response->data = \Yii::$app->request->post();

        return $response;
    }
}
