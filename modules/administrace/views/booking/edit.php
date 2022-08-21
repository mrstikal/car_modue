<?php

use app\modules\car\models\Car;
use app\assets\TinyMCEAsset;
use app\modules\common\components\CommonFunctions;
use app\modules\administrace\assets\booking\BookingAsset;
use app\modules\options\models\OptionsTable;
use app\assets\AppAsset;

TinyMCEAsset::register($this);
BookingAsset::register($this);
?>


<div class="administrace-default-index">

    <div class="admin_top_wrap">

        <h1>Úprava rezervace</h1>
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
                            <?php foreach ($this->params['select_types']['car'] as $car) : ?>
                                <option value="<?= $car['id']; ?>" <?php CommonFunctions::selected($car['id'], $booking->car_id); ?>><?= $car['language_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="elements_wrapper" style="padding-bottom: 20px">

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Začátek pronájmu *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="from datetime_picker shortest wide_textfield nomargin" id="date_from" name="date_from" value="<?= Yii::$app->formatter->asDate($booking->date_from, 'd. M. yyyy'); ?>" readonly>
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Konec pronájmu *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="to datetime_picker shortest wide_textfield nomargin" id="date_to" name="date_to" value="<?= Yii::$app->formatter->asDate($booking->date_to, 'd. M. yyyy'); ?>" readonly>
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Nájezd *</div>
                            <div class="cleaner">&nbsp;</div>
                            <span class="no_whitespace">
                                <input type="text" class="shortest wide_textfield nomargin only_digits" id="mileage" name="mileage" value="<?= $booking->mileage; ?>">&nbsp;&nbsp;km
                            </span>
                        </div>

                    </div>

                    <?php
                    $rounder = OptionsTable::getOption('price_rounding', -1);
                    $formatedPrice = \Yii::$app->formatter->asCurrency(round($booking->price, $rounder), 'CZK', [\NumberFormatter::MAX_FRACTION_DIGITS => 0]);
                    ?>

                    <div class="elements_wrapper">

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Cena za pronájem *</div>
                            <div class="cleaner">&nbsp;</div>
                            <span class="no_whitespace">
                                <input type="text" class="shortest wide_textfield nomargin only_digits" id="price_visible" name="price_visible" value="<?= $formatedPrice; ?>">
                            </span>
                            <input type="hidden" id="price" name="price" value="<?= $booking->price; ?>">
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
                                <option value="0" <?php CommonFunctions::selected(0, $booking->use_rider); ?>>Ne</option>
                                <option value="1" <?php CommonFunctions::selected(1, $booking->use_rider); ?>>Ano</option>
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
                            <input type="text" class="shorter wide_textfield nomargin" id="name" name="name" value="<?= $booking->name; ?>">
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">E-mail *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="shorter wide_textfield nomargin" id="email" name="email" value="<?= $booking->email; ?>">
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Telefon</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="shorter wide_textfield nomargin" id="phone" name="phone" value="<?= $booking->phone; ?>">
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
                                <?php foreach ($this->params['select_types']['statuses'] as $value => $name) : ?>
                                    <option value="<?= $value; ?>" <?php CommonFunctions::selected($value, $booking->status); ?>><?= $name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>

                </div>

                <div class="admin_block no_pad" style="padding-bottom: 20px;">

                    <div class="this_element_name bigger">Poznámka:</div>

                    <div class="elements_wrapper">

                        <textarea rows="3" class="booking_note" name="note" id="note"><?= $booking->note; ?></textarea>

                    </div>

                </div>

            </div>

            <div class="infos_top_wrap_right">

                <div class="sticker">
                    <div class="save blue create_order">Vytvořit objednávku</div>
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
    const id = <?php echo json_encode($booking->id) ?>;
</script>