<?php

use yii\helpers\Url;
use app\modules\administrace\assets\booking\BookingAsset;
use app\modules\order\helpers\DatetimeHelper;
use app\modules\common\components\CommonFunctions;

BookingAsset::register($this);
?>

<div class="administrace-default-index">

    <div class="admin_top_wrap">

        <h1>Přehled rezervací</h1>

    </div>


    <div class="cleaner">&nbsp;</div>
    <div class="admin_help">Pokud si nejste jisti funkcí dané ikony (tlačítka), přidržte nad ní kurzor myši déle než 1 vteřinu</div>

    <div class="list_booking_related list_records">

        <div class="list_records_inner">

            <div class="data_processors">

                <div class="data_processors_controls">

                    <div class="dpc" data-type="search">
                        <div class="dpc_head">Hledat</div>
                        <div class="dpc_indicator search"><span>+</span></div>
                    </div>

                    <div class="dpc" data-type="filter">
                        <div class="dpc_head">Filtrovat</div>
                        <div class="dpc_indicator filter"><span>+</span></div>
                    </div>

                    <div class="dpc sort" data-type="sort">
                        <div class="dpc_head">Řadit</div>
                        <div class="dpc_indicator filter"><span>+</span></div>
                    </div>

                </div>

                <div class="data_processors_content" data-type="search">

                    <div class="dpc_descr">Hledat:</div>

                    <div class="dpc_items">

                        <div class="one_dpc_item">
                            <div class="one_dpc_item_head big_head">Jméno, název firmy, email nebo telefon</div>
                            <input type="text" class="shorter wide_textfield" id="search_fulltext" name="search_fulltext" data-filter-type="fulltext" data-operator="">
                        </div>

                        <div class="one_dpc_item">

                            <div class="one_dpc_item_head big_head">Datum vytvoření rezervace</div>

                            <div class="one_dpc_item_inliners">

                                <div class="one_dpc_item_one_inliner">
                                    <div class="one_dpc_item_head">od</div>
                                    <input type="text" class="from datetime_picker shortest wide_textfield nomargin" id="search_created_from" name="search_created_from" data-filter-type="where" data-operator=">">
                                </div>

                                <div class="one_dpc_item_one_inliner">
                                    <div class="one_dpc_item_head">do</div>
                                    <input type="text" class="to datetime_picker shortest wide_textfield nomargin" id="search_created_to" name="search_created_to" data-filter-type="where" data-operator="<">
                                </div>

                            </div>

                        </div>

                        <div class="one_dpc_item">

                            <div class="one_dpc_item_head big_head">Datum začátku pronájmu</div>

                            <div class="one_dpc_item_inliners">

                                <div class="one_dpc_item_one_inliner">
                                    <div class="one_dpc_item_head">od</div>
                                    <input type="text" class="from datetime_picker shortest wide_textfield nomargin" id="search_start_rent_from" name="search_start_rent_from" data-filter-type="where" data-operator=">">
                                </div>

                                <div class="one_dpc_item_one_inliner">
                                    <div class="one_dpc_item_head">do</div>
                                    <input type="text" class="to datetime_picker shortest wide_textfield nomargin" id="search_start_rent_to" name="search_start_rent_to" data-filter-type="where" data-operator="<">
                                </div>

                            </div>

                        </div>

                        <div class="one_dpc_item">

                            <div class="one_dpc_item_head big_head">Datum konce pronájmu</div>

                            <div class="one_dpc_item_inliners">

                                <div class="one_dpc_item_one_inliner">
                                    <div class="one_dpc_item_head">od</div>
                                    <input type="text" class="from datetime_picker shortest wide_textfield nomargin" id="search_end_rent_from" name="search_end_rent_from" data-filter-type="where" data-operator=">">
                                </div>

                                <div class="one_dpc_item_one_inliner">
                                    <div class="one_dpc_item_head">do</div>
                                    <input type="text" class="to datetime_picker shortest wide_textfield nomargin" id="search_end_rent_to" name="search_end_rent_to" data-filter-type="where" data-operator="<">
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="use_filters save">Hledat</div>

                    <div class="reset_filters">&times;&nbsp;&nbsp;Vynulovat všechna hledání&nbsp;&nbsp;&times;</div>

                    <div class="close_dpc_group" data-type="search">
                        <div class="dpc_close_text">Zavřít</div>
                        <div class="dpc_close_indicator"><span>+</span></div>
                    </div>

                </div>

                <div class="data_processors_content" data-type="filter">

                    <div class="dpc_descr">Filtrovat:</div>

                    <div class="dpc_items">

                        <div class="one_dpc_item">

                            <div class="one_dpc_item_head big_head">Stav objednávky</div>

                            <select class="select_type" id="filter_booking_status" name="filter_booking_status" data-filter-type="where" data-operator="=">
                                <option value="0" selected disabled>---</option>

                                <?php foreach ($this->params['statuses'] as $val => $key) : ?>
                                    <option value="<?= $val; ?>"><?= $key; ?></option>
                                <?php endforeach; ?>

                            </select>

                        </div>

                        <div class="one_dpc_item">

                            <div class="one_dpc_item_head big_head">Vozidlo</div>

                            <select class="select_type" id="filter_car" name="filter_car" data-filter-type="where" data-operator="=">
                                <option value="0" selected disabled>---</option>

                                <?php foreach ($this->params['cars'] as $car) : ?>
                                    <option value="<?= $car['id']; ?>"><?= $car['language_name']; ?></option>
                                <?php endforeach; ?>

                            </select>

                        </div>

                    </div>

                    <div class="use_filters save">Filtrovat</div>

                    <div class="reset_filters">&times;&nbsp;&nbsp;Vynulovat všechny filtry&nbsp;&nbsp;&times;</div>

                    <div class="close_dpc_group" data-type="filter">
                        <div class="dpc_close_text">Zavřít</div>
                        <div class="dpc_close_indicator"><span>+</span></div>
                    </div>

                </div>

                <div class="data_processors_content" data-type="sort">

                    <div class="dpc_descr">Řadit:</div>

                    <div class="dpc_items">

                        <div class="one_dpc_item">

                            <div class="one_dpc_item_head big_head">Datum</div>

                            <select class="select_type" id="filter_date_order_value" name="filter_date_order_value" data-filter-type="order-by" data-operator="">
                                <option value="0" selected disabled>---</option>
                                <option value="created_at">Vytvoření rezervace</option>
                                <option value="date_from">Začátek rezervace</option>
                                <option value="date_to">Konec rezervace</option>
                            </select>

                        </div>

                        <div class="one_dpc_item">

                            <div class="one_dpc_item_head big_head">Typ řazení</div>

                            <select class="select_type" id="filter_date_order_direction" name="filter_date_order_direction" data-filter-type="order-direction" data-operator="">
                                <option value="0" selected disabled>---</option>
                                <option value="SORT_DESC">Sestupně</option>
                                <option value="SORT_ASC">Vzestupně</option>
                            </select>

                        </div>

                    </div>

                    <div class="use_filters save">Seřadit</div>

                    <div class="reset_filters">&times;&nbsp;&nbsp;Vynulovat řazení&nbsp;&nbsp;&times;</div>

                    <div class="close_dpc_group" data-type="sort">
                        <div class="dpc_close_text">Zavřít</div>
                        <div class="dpc_close_indicator"><span>+</span></div>
                    </div>

                </div>

            </div>

            <div class="records_found">Nalezené záznamy: <span></span></div>

            <table class="admin_table booking_table">
                <thead>
                    <tr>
                        <th>Vozidlo</th>
                        <th>Jmého</th>
                        <th>Email</th>
                        <th>Telefon</th>
                        <th>Od / Do</th>
                        <th>Nájezd</th>
                        <th>Cena</th>
                        <th>Vytvořeno</th>
                        <th>Stav</th>
                        <th class="actions">Akce</th>
                    </tr>
                </thead>

                <tbody>

                </tbody>
            </table>

            <div>
                <div class=" load_more_records save<?php if ($this->params['can_load_more']) echo ' visible'; ?>">Načíst další záznamy</div>
            </div>

        </div>
    </div>

</div>

<script>
    const pagetype = 'bookings index';
    const status_ajax_url = <?php echo json_encode(\Yii::$app->urlManager->createUrl('/administrace/booking/save-status')) ?>;
    const load_ajax_url = <?php echo json_encode(\Yii::$app->urlManager->createUrl('/administrace/booking/ajax-load')) ?>;
</script>