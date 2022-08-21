<?php

use app\modules\car\models\Car;
use app\modules\administrace\components\UserLevels;
use app\modules\administrace\models\Languages;
use app\assets\TinyMCEAsset;
use app\modules\administrace\assets\serviceinfos\ServiceInfoAsset;
use app\modules\common\components\CommonFunctions;
use app\modules\administrace\assets\order\OrderAsset;
use app\modules\administrace\assets\booking\BookingAsset;
use app\modules\options\models\OptionsTable;
use app\modules\order\models\Order;


BookingAsset::register($this);
TinyMCEAsset::register($this);
OrderAsset::register($this);

$cashRegisterInits = $this->params['numberings']['cashRegisterInits'];
$invoiceInits = $this->params['numberings']['invoiceInits'];
?>


<div class="administrace-default-index">

    <div class="admin_top_wrap">

        <h1>Nová obejdnávka</h1>
        <div class="cleaner">&nbsp;</div>

    </div>

    <div class="admin_top_wrap orders_wrap">

        <div class="flex_wrapper topped_wrapper">

            <div class="infos_top_wrap_left">

                <div class="admin_block no_pad" style="padding-bottom: 20px;">

                    <div class="this_element_name bigger">Operátor:</div>

                    <div class="elements_wrapper_shrinkable js_parent">
                        <div class="cleaner">&nbsp;</div>
                        <select name="operator_id" id="operator_id" class="select_type">
                            <?php foreach ($this->params['select_types']['operators'] as $operator) : ?>
                                <option value="<?= $operator['id']; ?>" <?php CommonFunctions::selected($operator['id'], Yii::$app->user->id); ?>><?= $operator['owner']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>

                <div class="admin_block no_pad" style="padding-bottom: 20px;">

                    <div class="this_element_name bigger">Typ nájemce:</div>

                    <div class="elements_wrapper_shrinkable js_parent">
                        <div class="cleaner">&nbsp;</div>
                        <select name="is_company" id="is_company" class="select_type">
                            <?php foreach ($this->params['select_types']['customer_types'] as $type => $name) : ?>
                                <option value="<?= $type; ?>"><?= $name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>

                <div class="admin_block no_pad" style="padding-bottom: 20px;">

                    <div class="this_element_name bigger for_person">Nájemce:</div>
                    <div class="this_element_name bigger for_business">Zástupce firmy:</div>

                    <div class="elements_wrapper">

                        <div class="elements_wrapper_third js_parent">
                            <div class="this_element_name">Jméno *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="wide_textfield medium" id="name" name="name" value="<?= $booking['name']; ?>">
                        </div>

                        <div class="elements_wrapper_third js_parent">
                            <div class="this_element_name">Telefon *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="wide_textfield medium" id="phone" name="phone" value="<?= $booking['phone']; ?>">
                        </div>

                        <div class="elements_wrapper_third js_parent">
                            <div class="this_element_name">E-mail *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="wide_textfield medium" id="email" name="email" value="<?= $booking['email']; ?>">
                        </div>

                        <div class="elements_wrapper_third js_parent">
                            <div class="this_element_name">Rodné číslo *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="wide_textfield medium" id="birth_number" name="birth_number">
                        </div>

                        <div class="elements_wrapper_third js_parent">
                            <div class="this_element_name">Číslo OP *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="wide_textfield medium" id="identity_card_number" name="identity_card_number">
                        </div>

                        <div class="elements_wrapper_third js_parent for_person">
                            <div class="this_element_name">Datum narození *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="wide_textfield medium" id="birth_date" name="birth_date">
                        </div>

                    </div>

                    <div class="elements_wrapper_full js_parent for_business">
                        <div class="this_element_name">Trvalý pobyt *</div>
                        <div class="cleaner">&nbsp;</div>
                        <input type="text" class="wide_textfield" id="permanent_residence" name="permanent_residence">
                    </div>

                    <div class="admin_spacer">&nbsp;</div>

                    <div class="elements_wrapper for_person">

                        <div class="elements_wrapper_third js_parent">
                            <div class="this_element_name">Ulice *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="wide_textfield medium" id="street" name="street">
                        </div>

                        <div class="elements_wrapper_third js_parent">
                            <div class="this_element_name">PSČ *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="wide_textfield medium" id="zip" name="zip">
                        </div>

                        <div class="elements_wrapper_third js_parent">
                            <div class="this_element_name">Město *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="wide_textfield medium" id="town" name="town">
                        </div>

                    </div>

                    <div class="elements_wrapper for_business">

                        <div class="this_element_name bigger for_business">Firma:</div>
                        <div class="cleaner">&nbsp;</div>

                        <div class="elements_wrapper_third js_parent">
                            <div class="this_element_name">Název firmy / jméno podnikatele *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="wide_textfield medium" id="company_name" name="company_name">
                        </div>

                        <div class="elements_wrapper_third js_parent">
                            <div class="this_element_name">Ulice *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="wide_textfield medium" id="company_street" name="company_street">
                        </div>

                        <div class="elements_wrapper_third js_parent">
                            <div class="this_element_name">PSČ *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="wide_textfield medium" id="company_zip" name="company_zip">
                        </div>

                        <div class="elements_wrapper_third js_parent">
                            <div class="this_element_name">Město *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="wide_textfield medium" id="company_town" name="company_town">
                        </div>

                        <div class="elements_wrapper_third js_parent">
                            <div class="this_element_name">IČO *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="wide_textfield medium" id="ico" name="ico">
                        </div>

                        <div class="elements_wrapper_third js_parent">
                            <div class="this_element_name">DIČ</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="wide_textfield medium" id="dic" name="dic">
                        </div>

                    </div>

                    <div class="elements_wrapper_shrinkable js_parent">
                        <div class="this_element_name">Jiná fakturační adresa?</div>
                        <div class="cleaner">&nbsp;</div>
                        <span class="no_whitespace">
                            <label class="checkbox_parent">
                                <input type="checkbox" class="checkboxed" name="different_bill_address" id="different_bill_address">
                                <span class="checkbox_label">
                                    <span class="inner_checkbox_checkbox">&nbsp;</span>
                                </span>
                            </label>
                        </span>
                    </div>

                    <div class="elements_wrapper for_different_bill_address hidden" style="margin-top: 15px;">

                        <div class="this_element_name bigger">Fakturační adresa:</div>
                        <div class="cleaner">&nbsp;</div>

                        <div class="elements_wrapper_third js_parent">
                            <div class="this_element_name">Název firmy / jméno *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="wide_textfield medium" id="billing_name" name="billing_name">
                        </div>

                        <div class="elements_wrapper_third js_parent">
                            <div class="this_element_name">Ulice *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="wide_textfield medium" id="billing_street" name="billing_street">
                        </div>

                        <div class="elements_wrapper_third js_parent">
                            <div class="this_element_name">PSČ *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="wide_textfield medium" id="billing_zip" name="billing_zip">
                        </div>

                        <div class="elements_wrapper_third js_parent">
                            <div class="this_element_name">Město *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="wide_textfield medium" id="billing_town" name="billing_town">
                        </div>

                        <div class="elements_wrapper_third js_parent">
                            <div class="this_element_name">IČO</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="wide_textfield medium" id="billing_ico" name="billing_ico">
                        </div>

                        <div class="elements_wrapper_third js_parent">
                            <div class="this_element_name">DIČ</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="wide_textfield medium" id="billing_dic" name="billing_dic">
                        </div>

                    </div>

                </div>

                <div class="admin_block no_pad" style="padding-bottom: 20px;">

                    <div class="this_element_name bigger">Vozidlo:</div>

                    <div class="elements_wrapper" style="padding-bottom: 12px;">

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Vybrané vozidlo *</div>
                            <div class="cleaner">&nbsp;</div>
                            <select name="car_id" id="car_id" class="select_type">
                                <?php foreach ($this->params['select_types']['car'] as $car) : ?>
                                    <option value="<?= $car['id']; ?>" <?php CommonFunctions::selected($car['id'], $booking['car_id']); ?>><?= $car['language_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Začátek pronájmu *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="from datetime_picker shortest wide_textfield nomargin" id="lease_date_from" name="lease_date_from" value="<?= Yii::$app->formatter->asDate($booking['date_from'], 'd. M. yyyy'); ?>" readonly>
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Konec pronájmu *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="to datetime_picker shortest wide_textfield nomargin" id="lease_date_to" name="lease_date_to" value="<?= Yii::$app->formatter->asDate($booking['date_to'], 'd. M. yyyy'); ?>" readonly>
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Nájezd *</div>
                            <div class="cleaner">&nbsp;</div>
                            <span class="no_whitespace">
                                <input type="text" class="shortest wide_textfield nomargin only_digits" id="mileage" name="mileage" value="<?= $booking['mileage']; ?>">&nbsp;&nbsp;km
                            </span>
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Cena za pronájem *</div>
                            <div class="cleaner">&nbsp;</div>
                            <span class="no_whitespace">
                                <input type="text" class="shortest wide_textfield nomargin only_digits" id="price" name="price" value="<?= $booking['price']; ?>">&nbsp;&nbsp;Kč
                            </span>
                        </div>

                    </div>

                    <div class="elements_wrapper">

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Smluvní pokuta</div>
                            <div class="cleaner">&nbsp;</div>
                            <span class="no_whitespace">
                                <input type="text" class="shortest wide_textfield nomargin only_digits" id="contractual_fine" name="contractual_fine">&nbsp;&nbsp;Kč
                            </span>
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Použít připojištění?</div>
                            <div class="cleaner">&nbsp;</div>
                            <select name="use_rider" id="use_rider" class="select_type">
                                <option value="0" <?php CommonFunctions::selected(0, $booking['use_rider']); ?>>Ne</option>
                                <option value="1" <?php CommonFunctions::selected(1, $booking['use_rider']); ?>>Ano</option>
                            </select>
                        </div>

                    </div>

                </div>

                <div class="admin_block no_pad" style="padding-bottom: 12px;">

                    <div class="this_element_name bigger">Předání / vrácení vozidla:</div>

                    <div class="elements_wrapper" style="padding-bottom: 12px;">

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Předání - datum *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="to datetime_picker shortest wide_textfield nomargin" id="vehicle_handover_date" name="vehicle_handover_date" value="<?= Yii::$app->formatter->asDate($booking['date_from'], 'd. M. yyyy'); ?>" readonly>
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Předání - čas *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="mikro wide_textfield nomargin" id="vehicle_handover_time" name="vehicle_handover_time" value="<?= $this->params['time_take_over']; ?>">
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Předání - místo *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="wide_textfield long" id="vehicle_handover_place" name="vehicle_handover_place" value="<?= $this->params['place_take_over']; ?>">
                        </div>

                    </div>

                    <div class="elements_wrapper">

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Vrácení - datum *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="to datetime_picker shortest wide_textfield nomargin" id="vehicle_return_date" name="vehicle_return_date" value="<?= Yii::$app->formatter->asDate($booking['date_to'], 'd. M. yyyy'); ?>" readonly>
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Vrácení - čas *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="mikro wide_textfield nomargin" id="vehicle_return_time" name="vehicle_return_time" value="<?= $this->params['time_return']; ?>">
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Vrácení - místo *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="wide_textfield long" id="vehicle_return_place" name="vehicle_return_place" value="<?= $this->params['place_return']; ?>">
                        </div>

                    </div>

                </div>

                <div class="admin_block no_pad" style="padding-bottom: 20px;">

                    <div class="this_element_name bigger">Kauce:</div>

                    <div class="elements_wrapper">

                        <div class="elements_wrapper_shrinkable js_parent" style="padding-bottom: 15px;">
                            <div class="this_element_name">Platební metoda</div>
                            <div class="cleaner">&nbsp;</div>
                            <select name="invoice_bail_payment_method" id="invoice_bail_payment_method" class="select_type payment_method">
                                <?php foreach ($this->params['select_types']['payment_methods'] as $method => $name) : ?>
                                    <option value="<?= $method; ?>"><?= $name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Výše kauce *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="shortest wide_textfield nomargin" id="bail_value" name="bail_value" value="<?= $this->params['bail_value']; ?>">&nbsp;&nbsp;Kč
                        </div>

                    </div>

                    <div class="elements_wrapper" style="padding-bottom: 15px; padding-top: 15px">

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Vystavit fakturu?</div>
                            <div class="cleaner">&nbsp;</div>
                            <span class="no_whitespace">
                                <label class="checkbox_parent">
                                    <input type="checkbox" class="checkboxed" name="create_bail_invoice" id="create_bail_invoice">
                                    <span class="checkbox_label">
                                        <span class="inner_checkbox_checkbox">&nbsp;</span>
                                    </span>
                                </label>
                            </span>
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Vytvořit příjmový doklad?</div>
                            <div class="cleaner">&nbsp;</div>
                            <span class="no_whitespace">
                                <label class="checkbox_parent">
                                    <input type="checkbox" class="checkboxed" name="create_bail_cash_register" id="create_bail_cash_register">
                                    <span class="checkbox_label">
                                        <span class="inner_checkbox_checkbox">&nbsp;</span>
                                    </span>
                                </label>
                            </span>
                        </div>

                    </div>

                    <div class="elements_wrapper for_bail_invoice hidden">

                        <div class="elements_wrapper_shrinkable js_parent hidden">
                            <div class="this_element_name">Prefix čísla faktury *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="shortest wide_textfield nomargin" id="invoice_bail_actual_prefix" name="invoice_bail_actual_prefix">
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent hidden">
                            <div class="this_element_name">Číslo faktury po prefixu *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="shortest wide_textfield nomargin" id="invoice_bail_actual_number" name="invoice_bail_actual_number">
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Datum vystavení *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="datetime_picker shortest wide_textfield nomargin issue_date" id="invoice_bail_issue_date" name="invoice_bail_issue_date" value="<?= Yii::$app->formatter->asDate(time(), 'd. M. yyyy'); ?>" readonly>
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Datum usk. daň. pln. *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="datetime_picker shortest wide_textfield nomargin supply_date" id="invoice_bail_supply_date" name="invoice_bail_supply_date" value="<?= Yii::$app->formatter->asDate(time(), 'd. M. yyyy'); ?>" readonly>
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Splatnost *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="datetime_picker shortest wide_textfield nomargin due_at" id="invoice_bail_due_at" name="invoice_bail_due_at" value="<?= Yii::$app->formatter->asDate(time(), 'd. M. yyyy'); ?>" readonly>
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Variabilní symbol *</div>
                            <div class="cleaner">&nbsp;</div>
                            <span class="no_whitespace">
                                <input type="text" name="invoice_bail_variable_symbol" id="invoice_bail_variable_symbol" class="shortest wide_textfield nomargin">
                            </span>
                        </div>

                    </div>

                    <div class="elements_wrapper for_bail_cash hidden">

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Prefix čísla pokladního bločku *</div>
                            <div class="cleaner">&nbsp;</div>
                            <span class="no_whitespace">
                                <input type="text" class="shortest wide_textfield nomargin" name="cash_register_bail_actual_prefix" id="cash_register_bail_actual_prefix">
                            </span>
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Číslo pokladního bločku po prefixu *</div>
                            <div class="cleaner">&nbsp;</div>
                            <span class="no_whitespace">
                                <input type="text" class="shortest wide_textfield nomargin" name="cash_register_bail_actual_number" id="cash_register_bail_actual_number">
                            </span>
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Datum platby pro pokladní bloček *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="datetime_picker shortest wide_textfield nomargin" id="cash_register_bail_payment_date" name="cash_register_bail_payment_date" value="<?= Yii::$app->formatter->asDate(time(), 'd. M. yyyy'); ?>" readonly>
                        </div>

                    </div>

                </div>

                <div class="admin_block no_pad" style="padding-bottom: 20px;">

                    <div class="this_element_name bigger">Pronájem:</div>

                    <div class="elements_wrapper">

                        <div class="elements_wrapper_shrinkable js_parent" style="padding-bottom: 15px;">
                            <div class="this_element_name">Platební metoda</div>
                            <div class="cleaner">&nbsp;</div>
                            <select name="invoice_rent_payment_method" id="invoice_rent_payment_method" class="select_type payment_method">
                                <?php foreach ($this->params['select_types']['payment_methods'] as $method => $name) : ?>
                                    <option value="<?= $method; ?>"><?= $name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Cena celkem *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="shortest wide_textfield nomargin" id="invoice_price" name="invoice_price" value="<?= $booking['price']; ?>">&nbsp;&nbsp;Kč
                        </div>

                    </div>

                    <div class="elements_wrapper" style="padding-bottom: 15px; padding-top: 15px">

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Vystavit fakturu?</div>
                            <div class="cleaner">&nbsp;</div>
                            <span class="no_whitespace">
                                <label class="checkbox_parent">
                                    <input type="checkbox" class="checkboxed" name="create_invoice" id="create_invoice">
                                    <span class="checkbox_label">
                                        <span class="inner_checkbox_checkbox">&nbsp;</span>
                                    </span>
                                </label>
                            </span>
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Vytvořit příjmový doklad?</div>
                            <div class="cleaner">&nbsp;</div>
                            <span class="no_whitespace">
                                <label class="checkbox_parent">
                                    <input type="checkbox" class="checkboxed" name="create_cash_register" id="create_cash_register">
                                    <span class="checkbox_label">
                                        <span class="inner_checkbox_checkbox">&nbsp;</span>
                                    </span>
                                </label>
                            </span>
                        </div>

                    </div>

                    <div class="elements_wrapper for_rent_invoice hidden">

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Prefix čísla faktury *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="shortest wide_textfield nomargin" id="invoice_rent_actual_prefix" name="invoice_rent_actual_prefix">
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Číslo faktury po prefixu *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="shortest wide_textfield nomargin" id="invoice_rent_actual_number" name="invoice_rent_actual_number">
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Datum vystavení *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="datetime_picker shortest wide_textfield nomargin issue_date" id="invoice_rent_issue_date" name="invoice_rent_issue_date" value="<?= Yii::$app->formatter->asDate(time(), 'd. M. yyyy'); ?>" readonly>
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Datum usk. daň. pln. *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="datetime_picker shortest wide_textfield nomargin supply_date" id="invoice_rent_supply_date" name="invoice_rent_supply_date" value="<?= Yii::$app->formatter->asDate(time(), 'd. M. yyyy'); ?>" readonly>
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Splatnost *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="datetime_picker shortest wide_textfield nomargin due_at" id="invoice_rent_due_at" name="invoice_rent_due_at" value="<?= Yii::$app->formatter->asDate(time(), 'd. M. yyyy'); ?>" readonly>
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Variabilní symbol *</div>
                            <div class="cleaner">&nbsp;</div>
                            <span class="no_whitespace">
                                <input type="text" name="invoice_rent_variable_symbol" id="invoice_rent_variable_symbol" class="shortest wide_textfield nomargin">
                            </span>
                        </div>

                    </div>

                    <div class="elements_wrapper for_rent_cash hidden">

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Prefix čísla pokladního bločku *</div>
                            <div class="cleaner">&nbsp;</div>
                            <span class="no_whitespace">
                                <input type="text" class="shortest wide_textfield nomargin" name="cash_register_rent_actual_prefix" id="cash_register_rent_actual_prefix">
                            </span>
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Číslo pokladního bločku po prefixu *</div>
                            <div class="cleaner">&nbsp;</div>
                            <span class="no_whitespace">
                                <input type="text" class="shortest wide_textfield nomargin" name="cash_register_rent_actual_number" id="cash_register_rent_actual_number">
                            </span>
                        </div>

                        <div class="elements_wrapper_shrinkable js_parent">
                            <div class="this_element_name">Datum platby pro pokladní bloček *</div>
                            <div class="cleaner">&nbsp;</div>
                            <input type="text" class="datetime_picker shortest wide_textfield nomargin" id="cash_register_rent_payment_date" name="cash_register_rent_payment_date" value="<?= Yii::$app->formatter->asDate(time(), 'd. M. yyyy'); ?>" readonly>
                        </div>

                    </div>

                </div>

                <div class="admin_block no_pad" style="padding-bottom: 20px;">

                    <div class="this_element_name bigger">Poznámka:</div>

                    <textarea id="note" name="note" class="auto_resize wide_textfield" rows="3"></textarea>

                </div>

            </div>

            <div class="infos_top_wrap_right">

                <div class="sticker">
                    <div class="save">Uložit změny</div>
                </div>

            </div>

        </div>

    </div>

</div>

<script>
    const pagetype = 'order new';
    const save_order_ajax_url = <?php echo json_encode(\Yii::$app->urlManager->createUrl('/administrace/order/save')) ?>;
    const calculate_price_ajax_url = <?php echo json_encode(\Yii::$app->urlManager->createUrl('/administrace/order/calculate-price')) ?>;
    const bail_value_ajax_url = <?php echo json_encode(\Yii::$app->urlManager->createUrl('/administrace/order/bail-value')) ?>;
    const id = 0;
    const cashRegisterInits = <?php echo json_encode($cashRegisterInits); ?>;
    const invoiceInits = <?php echo json_encode($invoiceInits); ?>;
    const bookingId = <?php echo json_encode($booking['id']); ?>;
    const variableSymbolBase = <?php echo json_encode($this->params['variable_symbol_base']); ?>;
</script>