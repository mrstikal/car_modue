<?php

namespace app\modules\administrace\controllers;

use Yii;
use yii\web\Controller;
use app\modules\car\models\Car;
use app\modules\car\models\CarLanguage;
use app\modules\car\models\Price;
use app\modules\administrace\models\Languages;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use app\modules\common\components\Response;
use yii\web\UploadedFile;
use app\modules\administrace\models\Images;
use yii\helpers\Inflector;
use yii\helpers\Url;
use app\modules\car\models\ServiceInfo;
use app\modules\options\models\OptionsTable;
use app\modules\administrace\traits\CalculationsTrait;

/**
 * Performs car actions
 */

class CarController extends Controller
{
    use CalculationsTrait;

    public $layout = 'main.php';

    public $currentLanguageCode = '';

    //maps incoming params to car attributes
    private $paramsMap = [
        'basePrice' => 'standard_price',
        'mileageNum' => 'test_mileage',
        'daysNum' => 'test_days',

        'mileageLowerLimit' => 'mileage_lower_limit',
        'mileageUpperLimit' => 'mileage_upper_limit',
        'mileageMaxDiscount' => 'mileage_max_discount',
        'mileageCoefficient' => 'mileage_coefficient',

        'daysLowerLimit' => 'days_lower_limit',
        'daysUpperLimit' => 'days_upper_limit',
        'daysMaxDiscount' => 'days_max_discount',
        'daysCoefficient' => 'days_coefficient',
    ];

    /**
     * Renders index page
     *
     * @return View
     */
    public function actionIndex()
    {
        $cars = Car::findList()->all();

        $this->view->params['keyw'] = ['cars', 'all'];
        $this->view->params['translate_me'] = true;
        $this->view->params['record_type'] = 'cars';
        $this->view->params['view_type'] = 'list';

        $this->view->params['actual_language_icon'] = Languages::getIcon(Yii::$app->params['currentLanguage']);

        $this->view->params['is_primary_language'] = Languages::isPrimaryLanguage();

        if (Languages::isPrimaryLanguage()) {
            $this->view->params['other_languages'] = Languages::getLanguages(true);
        } else {
            $this->view->params['other_languages'] = Languages::getLanguages(true, true);
        }

        return $this->render('index', ['cars' => $cars]);
    }

    /**
     * Saves cars order after sorting on index page.
     * This order is used on frontend.
     *
     * @return Response
     */
    public function actionSavePositions()
    {
        $postFields = Yii::$app->request->post();

        foreach ($postFields as $carId => $position) {

            $carModel = Car::findOne($carId);
            if (empty($carModel)) throw new NotFoundHttpException;

            $carModel->position = $position;
            $carModel->save(false);
        }

        $response = Response::getResponseBase();

        $response->statusCode = 200;
        $response->data = '';

        return $response;
    }

    /**
     * Saves car frontend visibility status.
     *
     * @return Response
     */
    public function actionSaveStatus()
    {
        if (!Yii::$app->request->isPost) {
            throw new ForbiddenHttpException;
        }

        $languageId = Yii::$app->request->post('language_id');
        $language = Yii::$app->request->post('language');
        $status = Yii::$app->request->post('status');

        $model = CarLanguage::findOne(['id' => $languageId, 'language' => $language]);

        if (empty($model)) throw new NotFoundHttpException;

        $model->status = $status;

        $model->save(false);

        $response = Response::getResponseBase();

        $response->statusCode = 200;
        $response->data = '';

        return $response;
    }

    /**
     * Renders new car page.
     *
     * @return View
     */
    public function actionNew()
    {
        if (!Languages::isPrimaryLanguage()) {
            Languages::setLanguage(Yii::$app->sourceLanguage);
            $this->refresh();
        }

        $this->view->params['translate_me'] = true;
        $this->view->params['keyw'] = ['cars', 'new'];
        $this->view->params['record_type'] = 'cars';
        $this->view->params['view_type'] = 'new';

        $this->view->params['current_language'] = Languages::getLanguage();
        $this->view->params['is_primary_language'] = Languages::isPrimaryLanguage();

        $this->view->params['tractions'] = Car::getTractions();

        return $this->render('new');
    }

    /**
     * Renders edit car page.
     *
     * @return View
     */
    public function actionEdit()
    {
        //switch language if get param exists
        if (Yii::$app->request->get('lang')) {
            $language = Yii::$app->request->get('lang');
            $language = explode('-', $language);
            $language[1] = strtoupper($language[1]);
            $language = implode('-', $language);
            Languages::setLanguage($language);
            Yii::$app->params['currentLanguage'] = $language;
        }

        $params = Yii::$app->request->queryParams;
        $car = Car::findSingle($params['id']);

        //it is translation of car, so we fill attributes with init values
        if (empty($car) && !CarLanguage::getTraslationExists($params['id'], $language)) {
            $car = CarLanguage::getTranslationTitle($params['id'], Yii::$app->sourceLanguage);
            $car['language_slogan'] = '';
            $car['language_description'] = '';
            $car['language_status'] = 'active';
            $car['language_id'] = $language;
            $car['id'] = $params['id'];
        }

        $this->view->params['translate_me'] = true;
        $this->view->params['keyw'] = ['cars', 'edit'];
        $this->view->params['record_type'] = 'cars';
        $this->view->params['view_type'] = 'edit';

        //for compatibility with main lang switcher add currentLanguageCode param
        $this->view->params['current_language'] = $this->view->params['currentLanguageCode'] = Languages::getLanguage();

        $this->view->params['is_primary_language'] = Languages::isPrimaryLanguage();

        $this->view->params['tractions'] = Car::getTractions();

        $this->view->params['last_service_info'] = ServiceInfo::findForCar($params['id']);

        return $this->render('edit', ['car' => $car]);
    }

    /**
     * Performs car deletion action.
     *
     * @return Response
     */
    public function actionDelete()
    {
        if (!Yii::$app->request->isPost) {
            throw new ForbiddenHttpException;
        }

        $carId = Yii::$app->request->post('car_id');

        //car isn't deleted from db table, only marked as deleted
        CarLanguage::updateAll(['status' => 'deleted'], ['car_id' => $carId]);

        $response = Response::getResponseBase();

        $response->statusCode = 200;
        $response->data = '';

        return $response;
    }

    /**
     * Transfoms from frontend params to backend ones
     *
     * @return array
     */
    private function parseParams()
    {
        $params = [];

        foreach ($this->paramsMap as $backendParam => $frontendParam) {
            $postParam = Yii::$app->request->post($frontendParam);
            if (!empty($postParam)) {
                $params[$backendParam] = $postParam;
            }
        }

        return $params;
    }

    /**
     * Renders data for mileage chart.
     *
     * @return Response
     */
    public function actionMileageChart()
    {
        if (!Yii::$app->request->isPost) {
            throw new ForbiddenHttpException;
        }

        $params = $this->parseParams();

        $result = Price::calculatePricesRangeMileage($params);

        $response = Response::getResponseBase();

        $response->statusCode = 200;
        $response->data = $result;

        return $response;
    }

    /**
     * Renders data for days chart.
     *
     * @return Response
     */
    public function actionDaysChart()
    {
        if (!Yii::$app->request->isPost) {
            throw new ForbiddenHttpException;
        }

        $params = $this->parseParams();

        $result = Price::calculatePricesRangeDays($params);

        $response = Response::getResponseBase();

        $response->statusCode = 200;
        $response->data = $result;

        return $response;
    }

    /**
     * Renders data for price test.
     *
     * @return Response
     */
    public function actionTestPrice()
    {
        if (!Yii::$app->request->isPost) {
            throw new ForbiddenHttpException;
        }

        $params = $this->parseParams();

        $result = Price::calculatePricesRangeFinal($params);

        $response = Response::getResponseBase();

        $response->statusCode = 200;
        $response->data = $result;

        return $response;
    }

    /**
     * Performs car save action.
     *
     * @return Response
     */
    public function actionSaveCar()
    {
        $errors = [];

        $currentLanguage = Languages::getLanguage();

        $attributes = Yii::$app->request->post();

        $languageAttributes = [];

        //get language param and uset it from collection
        foreach ($attributes as $key => $value) {
            if (strpos($key, 'language_') === 0) {
                $from = '/' . preg_quote('language_', '/') . '/';
                $newKey = preg_replace($from, '', $key, 1);
                $languageAttributes[$newKey] = $value;
                unset($attributes[$key]);
            }
        }

        //new or existing car
        if (empty($attributes['car_id'])) {
            $carModel = new Car;
        } else {
            $carModel = Car::findOne($attributes['car_id']);
        }

        //language version
        if (empty($attributes['car_id'])) {
            $carLanguageModel = new CarLanguage;
        } else {
            $carLanguageModel = CarLanguage::findOne(['car_id' => $attributes['car_id'], 'language' => $currentLanguage]);
            if (empty($carLanguageModel)) $carLanguageModel = new CarLanguage;
        }

        $isNewRecord = empty($attributes['car_id']);
        unset($attributes['car_id']);

        //if image uploaded, save it
        $image = UploadedFile::getInstanceByName('image');

        if (!empty($image)) {
            $imageModel = new Images;
            $name = substr($image->name, 0, (strrpos($image->name, ".")));
            $name = Inflector::slug($name);
            $name = substr(ucfirst($name), 0, 100);
            $imageModel->uploaded_image = $image;
            $imageModel->image_name = $name;
            $imageModel->temp_name = $name;
            $imageId = $imageModel->saveAllResolutions();
            $carModel->image = $imageId;
        }

        //fill in car attributes with post data
        foreach ($attributes as $key => $value) {
            $carModel->$key = $value;
        }

        $validated = $carModel->validate();

        //if model validation fails, return errors
        if ($validated) {
            $carModel->save(false);
        } else {
            $carModelErrors = $carModel->getErrors();
            foreach ($carModelErrors as $err) {
                $errors[] = $err[0];
            }
        }

        $carId = $carModel->id;

        //process language version
        $carLanguageModel->car_id = $carId;

        $carLanguageModel->language = $currentLanguage;

        foreach ($languageAttributes as $key => $value) {
            $carLanguageModel->$key = $value;
        }

        $validated = $carLanguageModel->validate();

        //if model validation fails, return errors
        if ($validated) {
            $carLanguageModel->save(false);
        } else {
            $carModelErrors = $carLanguageModel->getErrors();
            foreach ($carModelErrors as $err) {
                $errors[] = $err[0];
            }
        }

        $errorMessage = '';

        $errors = array_unique($errors);

        foreach ($errors as $field) {
            $errorMessage .= '<p>' . $field . '</p>';
        }

        if (empty($error_message) && $isNewRecord) {
            return $this->redirect(Url::base(true) . '/administrace/car/index');
        }

        $response = Response::getResponseBase();

        $response->statusCode = 200;
        $response->data = $errorMessage;

        return $response;
    }

    /**
     * Renders cars setting page
     *
     * @return View
     */
    public function actionSettings()
    {
        $this->view->params['keyw'] = ['cars', 'settings'];
        $this->view->params['surcharge_for_rider'] = OptionsTable::getOption('surcharge_for_rider', 30);
        $this->view->params['bail_with_rider'] = OptionsTable::getOption('bail_with_rider', 25000);
        $this->view->params['standard_bail'] = OptionsTable::getOption('standard_bail', 50000);
        $this->view->params['price_rounding'] = OptionsTable::getOption('price_rounding', -1);

        return $this->render('settings');
    }

    /**
     * Saves cars settings
     *
     * @return Response
     */
    public function actionSaveSettings()
    {
        foreach (\Yii::$app->request->post() as $optionName => $optionValue) {

            $model = OptionsTable::find()->where(['name' => $optionName])->one();

            if (empty($model)) $model = new OptionsTable;

            $model->name = $optionName;
            $model->value = $optionValue;
            $model->save();
        }

        $response = Response::getResponseBase();

        $response->statusCode = 200;
        $response->data = \Yii::$app->request->post();

        return $response;
    }
}
