<?php

use app\modules\car\models\Car;
use app\modules\administrace\components\UserLevels;
use app\modules\administrace\models\Languages;
use app\assets\TinyMCEAsset;
use app\modules\administrace\assets\serviceinfos\ServiceInfoAsset;
use app\modules\common\components\CommonFunctions;
use app\modules\administrace\assets\booking\BookingAsset;
use app\assets\AppAsset;
use yii\web\YiiAsset;

TinyMCEAsset::register($this);
BookingAsset::register($this);
YiiAsset::register($this);
?>

<div class="administrace-default-index">

    <div class="admin_top_wrap">

        <h1>Nová rezervace</h1>
        <div class="cleaner">&nbsp;</div>

    </div>

    <div class="admin_top_wrap infos_wrap">

        <div class="flex_wrapper topped_wrapper">

            <div class="infos_top_wrap_left">

                <div class="admin_block no_pad" style="padding-bottom: 20px;">

                    <div class="this_element_name bigger">Vozidlo:</div>

                    <div class="elements_wrapper_shrinkable js_parent" style="padding-bottom: 20px">
                        <div class="this_element_name">Vyberte vozidlo *</div>
                        <div class="cleaner">&nbsp;</div>
                        <select name="car_id" id="car_id" class="select_type">
                            <?php $cars = $this->params['select_types']['car']; ?>
                            <?php foreach ($cars as $key => $car) : ?>
                                <?php
                                $selected = '';
                                reset($cars);
                                if ($key === key($cars)) $selected = ' selected';
                                ?>
                                <option value="<?= $car['id']; ?>" <?= $selected; ?>><?= $car['language_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="elements_wrapper" style="padding-bottom: 20px">

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Začátek pronájmu *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="from datetime_picker shortest wide_textfield nomargin" id="date_from" name="date_from" readonly>
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Konec pronájmu *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="to datetime_picker shortest wide_textfield nomargin" id="date_to" name="date_to" readonly>
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Nájezd *</div>
                            <div class="cleaner">&nbsp;</div>
                            <span class="no_whitespace">
                                <input type="text" class="shortest wide_textfield nomargin only_digits" id="mileage" name="mileage">&nbsp;&nbsp;km
                            </span>
                        </div>

                    </div>

                    <div class="elements_wrapper">

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Cena za pronájem *</div>
                            <div class="cleaner">&nbsp;</div>
                            <span class="no_whitespace">
                                <input type="text" class="shortest wide_textfield nomargin" id="price_visible" name="price_visible" readonly>
                            </span>
                            <input type="hidden" id="price" name="price">
                        </div>

                        <div class="elements_wrapper_shrinkable">
                            <div class="this_element_name">&nbsp;</div>
                            <div class="cleaner">&nbsp;</div>
                            <div class="calculate_price">Přepočítat cenu</div>
                        </div>

                    </div>


                </div>

                <div class="admin_block no_pad" style="padding-bottom: 20px;">

                    <div class="this_element_name bigger">Připojištění:</div>

                    <div class="elements_wrapper">

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Použít připojištění?</div>
                            <div class="cleaner">&nbsp;</div>
                            <select name="use_rider" id="use_rider" class="select_type">
                                <option value="0" selected>Ne</option>
                                <option value="1">Ano</option>
                            </select>
                        </div>

                        <div class="elements_wrapper_shrinkable">
                            <div class="this_element_name">&nbsp;</div>
                            <div class="cleaner">&nbsp;</div>
                            <div class="bail_value">Výše kauce (jen pro info): <span></span></div>
                        </div>

                    </div>

                </div>

                <div class="admin_block no_pad" style="padding-bottom: 20px;">

                    <div class="this_element_name bigger">Nájemce:</div>

                    <div class="elements_wrapper">

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Jméno a příjmení</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="shorter wide_textfield nomargin" id="name" name="name">
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">E-mail *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="shorter wide_textfield nomargin" id="email" name="email">
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Telefon</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="shorter wide_textfield nomargin" id="phone" name="phone">
                        </div>

                    </div>

                </div>

                <div class="admin_block no_pad" style="padding-bottom: 20px;">

                    <div class="this_element_name bigger">Stav rezervace:</div>

                    <div class="elements_wrapper">

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Stav</div>
                            <div class="cleaner">&nbsp;</div>
                            <select name="status" id="status" class="select_type">
                                <?php $statuses = $this->params['select_types']['statuses']; ?>
                                <?php foreach ($statuses as $value => $name) : ?>
                                    <?php
                                    $selected = '';
                                    reset($statuses);
                                    if ($value === key($statuses)) $selected = ' selected';
                                    ?>
                                    <option value="<?= $value; ?>" <?= $selected; ?>><?= $name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>

                </div>

                <div class="admin_block no_pad" style="padding-bottom: 20px;">

                    <div class="this_element_name bigger">Poznámka:</div>

                    <div class="elements_wrapper">

                        <textarea rows="3" class="booking_note" name="note" id="note"></textarea>

                    </div>

                </div>

            </div>

            <div class="infos_top_wrap_right">

                <div class="sticker">
                    <div class="save save_booking">Uložit změny</div>
                </div>

            </div>

        </div>

    </div>

</div>

<script>
    const pagetype = 'booking new';
    const save_booking_ajax_url = <?php echo json_encode(\Yii::$app->urlManager->createUrl('/administrace/booking/save')) ?>;
    const calculate_price_ajax_url = <?php echo json_encode(\Yii::$app->urlManager->createUrl('/administrace/booking/calculate-price')) ?>;
    const bail_value_ajax_url = <?php echo json_encode(\Yii::$app->urlManager->createUrl('/administrace/booking/bail-value')) ?>;
    const id = 0;
</script>