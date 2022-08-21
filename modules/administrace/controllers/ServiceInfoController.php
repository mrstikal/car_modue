<?php

namespace app\modules\administrace\controllers;

use Yii;
use yii\web\Controller;
use app\modules\car\models\ServiceInfo;
use app\modules\car\models\Car;
use yii\web\NotFoundHttpException;
use app\modules\common\components\Response;
use yii\helpers\Url;
use app\modules\user\models\User;

/**
 * Performs actions for service infos.
 */

class ServiceInfoController extends Controller
{
    public $layout = 'main.php';

    /**
     * Renders index page.
     *
     * @return View
     */
    public function actionIndex()
    {
        //service info model instance
        $serviceInfos = ServiceInfo::factory();

        $this->view->params['selected_car'] = 0;

        //filter by car
        $carFilter = Yii::$app->request->get('filter');

        if ($carFilter) {
            $serviceInfos->carId = $carFilter;
            $this->view->params['selected_car'] = $carFilter;
        }

        //get all service infos for first page
        $serviceInfos = $serviceInfos->getAllServiceInfos(1);

        //left menu params
        $this->view->params['keyw'] = ['service_infos', 'all'];
        $this->view->params['record_type'] = 'service_info';
        $this->view->params['view_type'] = 'list';

        //params for selectboxes
        $this->view->params['filter_base'] = [
            'car' => Car::findForFilter(),
            'owners' => ServiceInfo::getOwners(),
            'statuses' => ServiceInfo::getServiceInfoStatuses(false),
        ];

        $this->view->params['records_count'] = $serviceInfos['count'];

        $this->view->params['can_load_more'] = $serviceInfos['can_load_more'];

        return $this->render('index', ['serviceInfos' => $serviceInfos['result']]);
    }

    /**
     * Renders new service info page.
     *
     * @return View
     */
    public function actionNew()
    {
        //left menu params
        $this->view->params['keyw'] = ['service_infos', 'new'];
        $this->view->params['view_type'] = 'new';

        //params for selectboxes
        $this->view->params['select_types'] = [
            'operators' => User::getActiveOperators(),
            'car' => Car::findForFilter(),
            'statuses' => ServiceInfo::getServiceInfoStatuses(false),
        ];

        return $this->render('new');
    }

    /**
     * Renders edit service info page.
     *
     * @return View
     */
    public function actionEdit()
    {
        $params = Yii::$app->request->queryParams;
        $serviceInfo = ServiceInfo::findSingle($params['id']);

        //left menu params
        $this->view->params['keyw'] = ['service_infos', 'edit'];
        $this->view->params['view_type'] = 'edit';

        //params for selectboxes
        $this->view->params['select_types'] = [
            'operators' => User::getActiveOperators(),
            'car' => Car::findForFilter(),
            'statuses' => ServiceInfo::getServiceInfoStatuses(false),
        ];

        return $this->render('edit', ['serviceInfo' => $serviceInfo]);
    }

    /**
     * Saves service info.
     *
     * @return Response
     */
    public function actionSave()
    {
        //new or existing record
        if (empty(Yii::$app->request->post('info_id'))) {
            $model = ServiceInfo::factory();
        } else {
            $model = ServiceInfo::findOne(Yii::$app->request->post('info_id'));
            if (empty($model)) throw new NotFoundHttpException;
        }

        $model->car_id =  Yii::$app->request->post('car_id');
        $model->owner = Yii::$app->request->post('owner');
        $model->status =  Yii::$app->request->post('status');
        $model->title =  Yii::$app->request->post('title');
        $model->description =  Yii::$app->request->post('description');
        $model->amount =  Yii::$app->request->post('amount');

        if (empty(Yii::$app->request->post('info_id'))) {
            $model->date = time();
        }

        $model->save(true);

        //if new record, redirect to index page
        if (empty(Yii::$app->request->post('info_id'))) {
            return $this->redirect(Url::base(true) . '/administrace/service-info/index');
        }

        $response = Response::getResponseBase();

        $response->statusCode = 200;
        $response->data = $model->attributes;

        return $response;
    }

    /**
     * Saves service info status from index page.
     *
     * @return Response
     */
    public function actionSaveStatus()
    {
        $model = ServiceInfo::findOne(['id' => Yii::$app->request->post('id')]);

        if (empty($model)) throw new NotFoundHttpException;

        $model->status = Yii::$app->request->post('status');

        $model->save(false);

        $response = Response::getResponseBase();

        $response->statusCode = 200;
        $response->data = '';

        return $response;
    }

    /**
     * Deletes service info record forever.
     * Only admin can perform this action.
     *
     * @return Response
     */
    public function actionDelete()
    {
        $model = ServiceInfo::findOne(['id' => Yii::$app->request->post('id')]);
        $model->delete();

        $response = Response::getResponseBase();

        $response->statusCode = 200;
        $response->data = '';

        return $response;
    }

    /**
     * Filters service infos by incoming params.
     *
     * @return Response
     */
    public function actionFilterInfos()
    {
        $model = ServiceInfo::factory();
        $model->carId = Yii::$app->request->post('car_id');
        $model->ownerId = Yii::$app->request->post('owner');

        if (!empty(Yii::$app->request->post('status')))
            $model->statuses = [Yii::$app->request->post('status')];

        $result = $model->getAllServiceInfos((int) Yii::$app->request->post('page'));

        $response = Response::getResponseBase();

        $response->statusCode = 200;
        $response->data = ['html' => $this->buildTableRows($result), 'can_load_more' => $result['can_load_more'], 'count' => $result['count']];

        return $response;
    }

    /**
     * Build service infos rows for index page.
     *
     * @param array $result
     * @return string HTML table rows
     */
    private function buildTableRows($result)
    {
        ob_start();
?>
        <?php foreach ($result['result'] as $info) : ?>

            <tr data-id="<?= $info['id']; ?>">
                <td><?= $info['id']; ?></td>
                <td><?= $info['name']; ?></td>
                <td><?= \Yii::$app->formatter->asDate($info['date'], 'dd.MM.YYYY HH:mm'); ?></td>
                <td><?= $info['title']; ?></td>

                <!-- Remember that admin_ico id attr is opposite to current status, i.e. it's future status after click -->
                <td class="status_indicator <?= $info['status'] == 'unsolved' ?  "visible" : "invisible"; ?>">
                    <div class="admin_ico admin_unsolved info_status" id="solved" data-id="<?= $info['id']; ?>">&nbsp;</div>
                </td>
                <td class="status_indicator <?= $info['status'] != 'unsolved' ?  "visible" : "invisible"; ?>">
                    <div class="admin_ico admin_solved info_status" id="unsolved" data-id="<?= $info['id']; ?>">&nbsp;</div>
                </td>

                <td><?= $info['owner']; ?></td>

                <td class="actions">
                    <a href="<?= \Yii::$app->urlManager->createUrl('/administrace/service-info/edit/' . $info['id']); ?>" class="admin_ico admin_edit">&nbsp;</a>
                </td>

                <td class="actions">
                    <div class="admin_ico admin_delete" data-id="<?= $info['id']; ?>">&nbsp;</div>
                </td>
            </tr>

        <?php endforeach; ?>
<?php
        return ob_get_clean();
    }
}
