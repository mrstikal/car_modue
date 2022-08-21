<?php

namespace app\modules\administrace\controllers;

use yii\web\Controller;
use app\modules\options\models\OptionsTable;
use app\modules\common\components\Response;

/**
 * Performs company settings actions.
 */
class CompanyController extends Controller
{
	public $layout = 'main.php';

	/**
	 * Renders index page.
	 *
	 * @return View
	 */
	public function actionIndex()
	{
		$this->view->params['keyw'] = ['company', 'all'];

		$this->view->params['fin'] = [
			'dph' => OptionsTable::getOption('fin_dph', 0),
			'dph_amount' => OptionsTable::getOption('fin_dph_amount', 21),
			'account_number' => OptionsTable::getOption('fin_account_number', ''),
			'bank_code' => OptionsTable::getOption('fin_bank_code', ''),
			'ico' => OptionsTable::getOption('fin_ico', ''),
			'dic' => OptionsTable::getOption('fin_dic', ''),
			'registration' => OptionsTable::getOption('fin_registration', ''),
		];

		$this->view->params['place'] = [
			'company_name' => OptionsTable::getOption('place_company_name', ''),
			'company_street' => OptionsTable::getOption('place_company_street', ''),
			'company_town' => OptionsTable::getOption('place_company_town', ''),
			'zip' => OptionsTable::getOption('place_zip', ''),
			'state' => OptionsTable::getOption('place_state', 'Česká republika'),
			'infoline' => OptionsTable::getOption('place_infoline', ''),
			'email' => OptionsTable::getOption('place_email', ''),
			'web' => OptionsTable::getOption('place_web', ''),
			'opening' => OptionsTable::getOption('place_opening', ''),
		];

		$this->view->params['branch'] = [
			'branch_street' => OptionsTable::getOption('branch_street', ''),
			'branch_town' => OptionsTable::getOption('branch_town', ''),
			'branch_zip' => OptionsTable::getOption('branch_zip', ''),
			'branch_state' => OptionsTable::getOption('branch_state', 'Česká republika'),
		];


		return $this->render('index');
	}

	/**
	 * Saves company settings.
	 *
	 * @return Response
	 */
	public function actionSaveSettings()
	{
		$post = \Yii::$app->request->post();

		foreach ($post as $optionName => $optionValue) {
			$model = OptionsTable::findOne(['name' => $optionName]);
			if (empty($model)) {
				$model = new OptionsTable();
				$model->name = $optionName;
			}
			$model->value = $optionValue;
			$model->save();
		}

		$response = Response::getResponseBase();

		$response->statusCode = 200;
		$response->data = '';

		return $response;
	}
}
