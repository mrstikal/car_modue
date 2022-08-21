<?php

use yii\helpers\Url;
use app\modules\car\models\CarLanguage;
use app\modules\administrace\assets\fullcalendar\FullCallendarAsset;
use app\assets\TooltipAsset;

FullCallendarAsset::register($this);
TooltipAsset::register($this);
?>

<div class="administrace-default-index">

    <div class="admin_top_wrap">

        <h1>Kalendář</h1>

    </div>

    <div class="cleaner">&nbsp;</div>

    <div class="filter_wrapper">

        <div class="one_filter_item">
            <div class="one_filter_item_label">Vozidlo</div>
            <div class="cleaner">&nbsp;</div>
            <select class="select_type" id="select_car">
                <option value="0">Vše</option>
                <?php foreach ($this->params['select_types']['car'] as $car) : ?>
                    <option value="<?= $car['id']; ?>"><?= $car['language_name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="one_filter_item">
            <div class="one_filter_item_label">Rezervace / Objednávky</div>
            <div class="cleaner">&nbsp;</div>
            <select class="select_type" id="select_type">
                <option value="all">Vše</option>
                <option value="bookings">Rezervace</option>
                <option value="orders">Objednávky</option>
            </select>
        </div>

    </div>

    <div class="description_wrapper">
        <span class="color_to_explain" style="background-color: #4d8439;">&nbsp;</span><span class="color_explained"> = Rezervace</span>
        <span class="color_to_explain" style="background-color: #2e56bb;">&nbsp;</span><span class="color_explained"> = Objednávky</span>
        <div class="cleaner" style="margin-bottom: 5px;">&nbsp;</div>
        <span>Číslo na začátku každého záznamu je jeho interní ID. Pomůže Vám v lepší orientaci v kalendáři.</span>
    </div>

    <div class="callendar_wrapper">

    </div>

    <script>
        const pagetype = 'callendar index';
        const bookings_ajax_url = <?php echo json_encode(\Yii::$app->urlManager->createUrl('/administrace/callendar/load-bookings')) ?>;
        const orders_ajax_url = <?php echo json_encode(\Yii::$app->urlManager->createUrl('/administrace/callendar/load-orders')) ?>;
    </script>