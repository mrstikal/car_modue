<?php

use yii\helpers\Url;
use app\modules\administrace\assets\serviceinfos\ServiceInfoAsset;
use app\modules\common\components\CommonFunctions;

ServiceInfoAsset::register($this);
?>

<div class="administrace-default-index">

    <div class="admin_top_wrap">

        <h1>Přehled sevisních informací</h1>

    </div>

    <div class="cleaner">&nbsp;</div>
    <div class="admin_help">Pokud si nejste jisti funkcí dané ikony (tlačítka), přidržte nad ní kurzor myši déle než 1 vteřinu</div>

    <div class="list_cars_related list_records">

        <div class="list_records_inner">

            <div class="info_filters">

                <div class="info_filters_head">Fitrovat dle:</div>

                <div class="one_filter_item">
                    <div class="one_filter_item_label">Vozidla</div>
                    <select class="select_type select_car" id="car_id" name="car_id">
                        <option value="0">---</option>
                        <?php foreach ($this->params['filter_base']['car'] as $car) : ?>
                            <option value="<?= $car['id']; ?>" <?php CommonFunctions::selected($car['id'], $this->params['selected_car']); ?>><?= $car['language_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="one_filter_item">
                    <div class="one_filter_item_label">Autora záznamu</div>
                    <select class="select_type select_owner" id="owner" name="owner">
                        <option value="0">---</option>
                        <?php foreach ($this->params['filter_base']['owners'] as $owner) : ?>
                            <option value="<?= $owner['id']; ?>"><?= $owner['owner']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="one_filter_item">
                    <div class="one_filter_item_label">Stavu</div>
                    <select class="select_type select_status" id="status" name="status">
                        <option value="0">---</option>
                        <?php foreach ($this->params['filter_base']['statuses'] as $status => $statusName) : ?>
                            <option value="<?= $status; ?>"><?= $statusName; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="one_filter_item">
                    <div class="one_filter_item_label">&nbsp;</div>
                    <div class="reset_filters">&times;&nbsp;&nbsp;Vynulovat všechny filtry&nbsp;&nbsp;&times;</div>
                </div>

            </div>

            <div class="records_found">Počet nalezených záznamů: <span><?= $this->params['records_count']; ?></span></div>

            <table class="admin_table service_info_table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Vozidlo</th>
                        <th>Datum vzniku</th>
                        <th>Název</th>
                        <th>Stav</th>
                        <th>Zaznamenal</th>
                        <th colspan=2>Akce</th>
                    </tr>
                </thead>

                <tbody>
                </tbody>
            </table>

            <div>
                <div class="load_more_records save<?php if ($this->params['can_load_more']) echo ' visible'; ?>">Načíst další záznamy</div>
            </div>

        </div>
    </div>

</div>

<div class="overlay car_deletion">

    <div class="ovelay_inner">

        <div class="overlay_inner_head dangerous_action">
            <p>Opravdu chcete smazat<br>toto servisní info?</p>
        </div>
        <div class="overlay_confirm deletion_confirm">Ano</div>
        <div class="overlay_deny deletion_cancel">Ne</div>

        <input type="hidden" class="deletion_id">

    </div>

</div>

<script>
    const pagetype = 'service infos index';
    const status_ajax_url = <?php echo json_encode(\Yii::$app->urlManager->createUrl('/administrace/service-info/save-status')) ?>;
    const delete_ajax_url = <?php echo json_encode(\Yii::$app->urlManager->createUrl('/administrace/service-info/delete')) ?>;
    const filter_ajax_url = <?php echo json_encode(\Yii::$app->urlManager->createUrl('/administrace/service-info/filter-infos')) ?>;
</script>