<?php

use app\modules\common\components\CommonFunctions;
use app\modules\administrace\components\UserLevels;
use app\modules\administrace\models\Languages;
use app\assets\TinyMCEAsset;
use app\modules\administrace\models\Images;
use app\modules\car\models\ServiceInfo;
use app\modules\options\models\OptionsTable;

TinyMCEAsset::register($this);
?>

<?php
$currentLanguage = $this->params['current_language'];
$isPrimaryLanguage = $this->params['is_primary_language'];
?>

<div class="administrace-default-index">

    <div class="admin_top_wrap">

        <h1>Úprava vozidla "<?= $car['language_name']; ?>"</h1>
        <div class="cleaner">&nbsp;</div>

        <?php if (!empty($currentLanguage) && \Yii::$app->params['siteIsMultilingual']) : ?>

            <?php
            $icons = Languages::getIcons();
            $key = array_search($currentLanguage, array_column($icons, 'code'));
            $currentLanguageSet = $icons[$key];
            ?>

            <?php if (!empty($currentLanguageSet)) : ?>
                <div class="lang_info">
                    <div class="lang_info_inliner lang_info_name">Aktuální jazyk:</div>
                    <div class="lang_info_inliner lang_info_title"><?= $currentLanguageSet['title']; ?></div>
                    <img class="lang_info_inliner lang_info_flag" src="<?= $currentLanguageSet['icon']; ?>">
                </div>
            <?php endif; ?>

        <?php endif; ?>

    </div>

    <div class="admin_top_wrap cars_wrap">

        <div class="flex_wrapper topped_wrapper">

            <div class="cars_top_wrap_left">

                <?php if (UserLevels::isAdmin()) : ?>

                    <div class="admin_block no_pad" style="padding-bottom: 20px;">

                        <div class="this_element_name bigger">Texty vozidla:</div>
                        <div class="cleaner">&nbsp;</div>

                        <div class="elements_wrapper full_width">

                            <div class="elements_wrapper_left js_parent">
                                <div class="this_element_name">Název *</div>
                                <input type="text" name="language_name" id="language_name" class="wide_textfield required" value="<?= $car['language_name']; ?>">
                            </div>

                            <div class="elements_wrapper_right js_parent">
                                <div class="this_element_name">Slogan</div>
                                <input type="text" name="language_slogan" id="language_slogan" class="wide_textfield" value="<?= $car['language_slogan']; ?>">
                            </div>

                        </div>

                        <div class="this_element_name">Popis (vlastnosti vozidla)</div>
                        <textarea name="language_description" id="language_description"><?= $car['language_description']; ?></textarea>

                    </div>

                <?php endif; ?>

                <?php if ($isPrimaryLanguage) : ?>

                    <?php if (UserLevels::isAdmin()) : ?>

                        <div class="admin_block no_pad" style="padding-bottom: 20px;">

                            <div class="this_element_name bigger">Parametry vozidla:</div>
                            <div class="cleaner">&nbsp;</div>

                            <div class="elements_wrapper elements_wrapper_align_left">

                                <div class="elements_wrapper_third js_parent">

                                    <div class="this_element_name">Baterie *</div>
                                    <div class="cleaner">&nbsp;</div>

                                    <span class="no_whitespace">
                                        <input type="text" name="battery_capacity" id="battery_capacity" class="wide_textfield inline_field only_digits required" value="<?= $car['battery_capacity']; ?>"> kWh
                                    </span>

                                </div>

                                <div class="elements_wrapper_third js_parent">

                                    <div class="this_element_name">Pohon *</div>
                                    <div class="cleaner">&nbsp;</div>

                                    <select name="traction" id="traction" class="select_type required">
                                        <option value="">--vyberte--</option>
                                        <?php foreach ($this->params['tractions'] as $value => $name) : ?>
                                            <option value="<?= $value; ?>" <?php CommonFunctions::selected($value, $car['traction']); ?>><?= $name; ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                </div>

                                <div class="elements_wrapper_third js_parent">

                                    <div class="this_element_name">Perfomance paket? </div>
                                    <div class="cleaner">&nbsp;</div>

                                    <label class="checkbox_parent">
                                        <input type="checkbox" class="checkboxed" name="is_performance" id="is_performance" <?php CommonFunctions::checked(1, $car['is_performance']); ?>>
                                        <span class="checkbox_label">
                                            <span class="inner_checkbox_checkbox">&nbsp;</span>
                                        </span>
                                    </label>
                                </div>

                            </div>

                        </div>

                    <?php endif; ?>

                    <?php if (UserLevels::isAdmin()) : ?>

                        <div class="admin_block no_pad" style="padding-bottom: 20px;">

                            <div class="this_element_name bigger">Obrázek vozidla:</div>
                            <div class="cleaner">&nbsp;</div>

                            <div class="admin_help">Maximální velikost obrázku je 10 MB</div>

                            <?php
                            $image = Images::getImageUrl($car['image'], 'medium');
                            ?>
                            <div class="image_preview_bg">
                                <div class="image_preview" style="background-image: url(<?= $image; ?>)"></div>
                            </div>

                            <div class="ico_error"></div>

                            <label for="hidden_file_input">
                                <div class="ico_drop_load bigger">Klikněte zde pro výběr obrázku nebo přetáhněte obrázek myší</div>
                                <input type="file" class="hidden_file_input" id="hidden_file_input">
                            </label>

                        </div>

                    <?php endif; ?>

                    <?php if (UserLevels::isAdmin()) : ?>

                        <div class="admin_block no_pad" style="padding-bottom: 20px;">

                            <div class="this_element_name bigger">Další parametry vozidla:</div>
                            <div class="cleaner">&nbsp;</div>

                            <div class="elements_wrapper full_width">

                                <div class="elements_wrapper_left js_parent">
                                    <div class="this_element_name">SPZ *</div>
                                    <input type="text" name="spz" id="spz" class="wide_textfield required" value="<?= $car['spz'] ?>">
                                </div>

                                <div class="elements_wrapper_right js_parent">
                                    <div class="this_element_name">VIN</div>
                                    <input type="text" name="vin" id="vin" class="wide_textfield" value="<?= $car['vin']; ?>">
                                </div>

                            </div>

                            <div class="elements_wrapper full_width">

                                <div class="elements_wrapper_left js_parent">
                                    <div class="this_element_name">Barva</div>
                                    <input type="text" name="color" id="color" class="wide_textfield" value="<?= $car['color'] ?>">
                                </div>

                                <div class="elements_wrapper_right js_parent">
                                    <div class="this_element_name">Výrobce (zatím není používáno, můžete ponechat prázdné)</div>
                                    <input type="text" name="manufacturer" id="manufacturer" class="wide_textfield" value="<?= $car['manufacturer'] ?>">
                                </div>

                            </div>

                        </div>

                    <?php endif; ?>

                    <?php if (UserLevels::isOperator()) : ?>

                        <div class="admin_block no_pad" style="padding-bottom: 20px;">

                            <div class="this_element_name bigger">Aktuální stav vozidla:</div>
                            <div class="cleaner">&nbsp;</div>

                            <div class="admin_help">Propisuje se do předávacího protokolu</div>

                            <textarea name="current_condition" id="current_condition" class="wide_textfield auto_resize" rows="4"><?= $car['current_condition'] ?></textarea>

                            <?php if (!empty($this->params['last_service_info']['count'])) : ?>

                                <?php
                                $serviceInfo = $this->params['last_service_info']['result'];
                                $readableStatus = ServiceInfo::getServiceInfoStatuses()[$serviceInfo['status']];
                                ?>

                                <div class="last_service_info">

                                    <div class="last_service_info_heading">Poslední servisní info:</div>

                                    <div class="last_service_info_inliners">
                                        <div>Datum záznamu: <span><?= \Yii::$app->formatter->asDate($serviceInfo['date'], 'dd.MM.YYYY'); ?></span></div>
                                        <div>Autor záznamu: <span><?= $serviceInfo['owner_name']; ?></span></div>
                                        <div>Stav: <span><?= $readableStatus; ?></span></div>
                                    </div>

                                    <div class="last_service_info_description">
                                        <div class="last_service_info_description_element"><span>Název:</span> <?= $serviceInfo['title']; ?></div>
                                        <div class="last_service_info_description_element"><span>Popis:</span> <?= $serviceInfo['description']; ?></div>
                                    </div>

                                    <?php if ($this->params['last_service_info']['count'] > 1) : ?>

                                        <a target="_blank" href="<?= \Yii::$app->urlManager->createUrl('/administrace/service-info/index?filter=' . $car['id']); ?>" class="more_service_infos">Více servisních informací k vozidlu</a>

                                    <?php endif; ?>

                                </div>

                            <?php endif; ?>

                            <div class="this_element_name bigger" style="margin-top: 20px;">Stav tachometru:</div>
                            <div class="cleaner">&nbsp;</div>

                            <div class="admin_help">Propisuje se do předávacího protokolu</div>

                            <span class="no_whitespace">
                                <input type="text" class="wide_textfield only_digits inline_field" id="mileage_now" name="mileage_now" value="<?= $car['mileage_now'] ?>"> km
                            </span>

                            <div class="this_element_name bigger" style="margin-top: 20px;">Stav pneu:</div>
                            <div class="cleaner">&nbsp;</div>

                            <div class="admin_help">Propisuje se do předávacího protokolu</div>

                            <?php
                            $tireCondition = json_decode($car['tire_condition'], true);
                            ?>

                            <div class="elements_wrapper elements_wrapper_align_left">

                                <div class="elements_wrapper_third">
                                    <span class="no_whitespace">
                                        <div class="this_element_name">Pravá přední</div>
                                        <div class="cleaner">&nbsp;</div>
                                        <input type="text" class="wide_textfield inline_field only_digits tires" id="tires_front_right" name="tires_front_right" value="<?= $tireCondition['tires_front_right']; ?>"> %
                                    </span>
                                </div>

                                <div class="elements_wrapper_third">
                                    <span class="no_whitespace">
                                        <div class="this_element_name">Levá přední</div>
                                        <div class="cleaner">&nbsp;</div>
                                        <input type="text" class="wide_textfield inline_field only_digits tires" id="tires_front_left" name="tires_front_left" value="<?= $tireCondition['tires_front_left']; ?>"> %
                                    </span>
                                </div>

                                <div class="elements_wrapper_third">
                                    <span class="no_whitespace">
                                        <div class="this_element_name">Pravá zadní</div>
                                        <div class="cleaner">&nbsp;</div>
                                        <input type="text" class="wide_textfield inline_field only_digits tires" id="tires_back_right" name="tires_back_right" value="<?= $tireCondition['tires_back_right']; ?>"> %
                                    </span>
                                </div>

                                <div class="elements_wrapper_third">
                                    <span class="no_whitespace">
                                        <div class="this_element_name">Levá zadní</div>
                                        <div class="cleaner">&nbsp;</div>
                                        <input type="text" class="wide_textfield inline_field only_digits tires" id="tires_back_left" name="tires_back_left" value="<?= $tireCondition['tires_back_left']; ?>"> %
                                    </span>
                                </div>

                                <div class="elements_wrapper_third">
                                    <span class="no_whitespace">
                                        <div class="this_element_name">Rezerva</div>
                                        <div class="cleaner">&nbsp;</div>
                                        <input type="text" class="wide_textfield inline_field only_digits tires" id="tires_spare" name="tires_spare" value="<?= $tireCondition['tires_spare']; ?>"> %
                                    </span>
                                </div>

                            </div>

                        </div>

                    <?php endif; ?>


                    <?php if (UserLevels::isAdmin()) : ?>

                        <?php
                        if (empty($car['bail'])) $car['bail_with_rider'] =  OptionsTable::getOption('standard_bail');
                        ?>

                        <div class="admin_block no_pad">

                            <div class="this_element_name bigger">Základní cenotvorba:</div>
                            <div class="cleaner">&nbsp;</div>

                            <div class="elements_wrapper">

                                <div class="elements_wrapper_shrinkable js_parent">
                                    <div class="this_element_name">Standardní kauce *</div>
                                    <span class="no_whitespace">
                                        <input type="text" name="bail" id="bail" class="wide_textfield only_digits inline_field required" value="<?= $car['bail'] ?>"> Kč
                                    </span>
                                </div>

                                <div class="elements_wrapper_shrinkable js_parent">
                                    <div class="this_element_name">Standardní cena/den *</div>
                                    <span class="no_whitespace">
                                        <input type="text" name="standard_price" id="standard_price" class="wide_textfield only_digits inline_field required" value="<?= $car['standard_price'] ?>"> Kč
                                    </span>
                                </div>

                                <div class="elements_wrapper_shrinkable js_parent">
                                    <div class="this_element_name">Akční cena/den</div>
                                    <span class="no_whitespace">
                                        <input type="text" name="action_price" id="action_price" class="wide_textfield only_digits inline_field" value="<?= $car['action_price'] ?>"> Kč
                                    </span>
                                </div>

                                <div class="elements_wrapper_shrinkable js_parent">
                                    <div class="this_element_name">Použít akční cenu?</div>
                                    <div class="cleaner">&nbsp;</div>

                                    <label class="checkbox_parent">
                                        <input type="checkbox" class="checkboxed" name="use_action_price" id="use_action_price" <?php CommonFunctions::checked(1, $car['use_action_price']); ?>>
                                        <span class="checkbox_label">
                                            <span class="inner_checkbox_checkbox">&nbsp;</span>
                                        </span>
                                    </label>
                                </div>

                            </div>

                        </div>

                    <?php endif; ?>

                    <?php if (UserLevels::isAdmin()) : ?>

                        <?php
                        if (empty($car['bail_with_rider'])) $car['bail_with_rider'] =  OptionsTable::getOption('bail_with_rider');
                        if (empty($car['surcharge_for_rider'])) $car['surcharge_for_rider'] =  OptionsTable::getOption('surcharge_for_rider');
                        ?>

                        <div class="admin_block no_pad">

                            <div class="this_element_name bigger">Připojištění:</div>
                            <div class="cleaner">&nbsp;</div>

                            <div class="elements_wrapper">

                                <div class="elements_wrapper_shrinkable js_parent">
                                    <div class="this_element_name">Kauce</div>
                                    <span class="no_whitespace">
                                        <input type="text" name="bail_with_rider" id="bail_with_rider" class="wide_textfield only_digits inline_field" value="<?= $car['bail_with_rider'] ?>"> Kč
                                    </span>
                                </div>

                                <div class="elements_wrapper_shrinkable js_parent">
                                    <div class="this_element_name">Příplatek k ceně</div>
                                    <span class="no_whitespace">
                                        <input type="text" name="surcharge_for_rider" id="surcharge_for_rider" class="wide_textfield only_digits inline_field" value="<?= $car['surcharge_for_rider'] ?>"> %
                                    </span>
                                </div>

                            </div>

                        </div>

                    <?php endif; ?>

                    <?php if (UserLevels::isAdmin()) : ?>

                        <div class="admin_block no_pad">

                            <div class="this_element_name bigger">Slevy:</div>
                            <div class="cleaner">&nbsp;</div>

                            <div class="this_element_name medium">Kilometrové slevy (při délce pronájmu jeden den):
                                <div class="admin_help">Pro získání nápovědy přidržte kurzor myši nad popisem pole déle než vteřinu</div>
                            </div>

                            <div class="elements_wrapper">

                                <div class="elements_wrapper_shrinkable js_parent">
                                    <div class="this_element_name mileage_lower_limit with_help">Spodní limit kilometrů</div>
                                    <span class="no_whitespace">
                                        <input type="text" name="mileage_lower_limit" id="mileage_lower_limit" class="wide_textfield only_digits inline_field required" value="<?= $car['mileage_lower_limit']; ?>"> km
                                    </span>
                                </div>

                                <div class="elements_wrapper_shrinkable js_parent">
                                    <div class="this_element_name mileage_upper_limit with_help">Horní limit kilometrů</div>
                                    <span class="no_whitespace">
                                        <input type="text" name="mileage_upper_limit" id="mileage_upper_limit" class="wide_textfield only_digits inline_field required" value="<?= $car['mileage_upper_limit']; ?>"> km
                                    </span>
                                </div>

                                <div class="elements_wrapper_shrinkable js_parent">
                                    <div class="this_element_name mileage_max_discount with_help">Maximální sleva</div>
                                    <span class="no_whitespace">
                                        <input type="text" name="mileage_max_discount" id="mileage_max_discount" class="wide_textfield only_digits inline_field required" value="<?= $car['mileage_max_discount']; ?>"> %
                                    </span>
                                </div>

                                <div class="elements_wrapper_shrinkable js_parent">
                                    <div class="this_element_name mileage_coefficient with_help">Koeficient průběhu slevy</div>
                                    <span class="no_whitespace">
                                        <?php
                                        $coefficient = str_replace('.', ',', $car['mileage_coefficient']);
                                        ?>
                                        <input type="text" name="mileage_coefficient" id="mileage_coefficient" class="wide_textfield only_decimal_min inline_field required" value="<?= $coefficient; ?>">
                                    </span>
                                </div>

                                <div class="elements_wrapper_shrinkable js_parent">
                                    <div class="this_element_name">&nbsp;</div>
                                    <span class="no_whitespace">
                                        <div class="discount_test discount_mileage_test">Vyzkoušet</div>
                                    </span>
                                </div>

                            </div>

                            <div class="chart_container" id="chart_container_mileage"></div>

                            <div class="this_element_name medium">Slevy za dny (při nejnižším nájezdu):
                                <div class="admin_help">Pro získání nápovědy přidržte kurzor myši nad popisem pole déle než vteřinu</div>
                            </div>

                            <div class="elements_wrapper">

                                <div class="elements_wrapper_shrinkable js_parent">
                                    <div class="this_element_name days_lower_limit with_help">Spodní limit dnů</div>
                                    <span class="no_whitespace">
                                        <input type="text" name="days_lower_limit" id="days_lower_limit" class="wide_textfield only_digits inline_field required" value="<?= $car['days_lower_limit']; ?>"> dnů
                                    </span>
                                </div>

                                <div class="elements_wrapper_shrinkable js_parent">
                                    <div class="this_element_name days_upper_limit with_help">Horní limit dnů</div>
                                    <span class="no_whitespace">
                                        <input type="text" name="days_upper_limit" id="days_upper_limit" class="wide_textfield only_digits inline_field required" value="<?= $car['days_upper_limit']; ?>"> dnů
                                    </span>
                                </div>

                                <div class="elements_wrapper_shrinkable js_parent">
                                    <div class="this_element_name days_max_discount with_help">Maximální sleva</div>
                                    <span class="no_whitespace">
                                        <input type="text" name="days_max_discount" id="days_max_discount" class="wide_textfield only_digits inline_field required" value="<?= $car['days_max_discount']; ?>"> %
                                    </span>
                                </div>

                                <div class="elements_wrapper_shrinkable js_parent">
                                    <div class="this_element_name days_coefficient with_help">Koeficient průběhu slevy</div>
                                    <span class="no_whitespace">
                                        <?php
                                        $coefficient = str_replace('.', ',', $car['days_coefficient']);
                                        ?>
                                        <input type="text" name="days_coefficient" id="days_coefficient" class="wide_textfield only_decimal_min inline_field required" value="<?= $coefficient; ?>">
                                    </span>
                                </div>

                                <div class="elements_wrapper_shrinkable js_parent">
                                    <div class="this_element_name">&nbsp;</div>
                                    <span class="no_whitespace">
                                        <div class="discount_test discount_days_test">Vyzkoušet</div>
                                    </span>
                                </div>

                            </div>

                            <div class="chart_container" id="chart_container_days"></div>

                            <div class="this_element_name medium">Celková cena:</div>
                            <div class="admin_help" style="margin-bottom: 10px">Vyzkoušejte, jak bude vypadat finální cena po kombinaci slev za kilometry a dny</div>

                            <div class="elements_wrapper">

                                <div class="elements_wrapper_shrinkable js_parent">
                                    <div class="this_element_name">Počet dní pronájmu</div>
                                    <span class="no_whitespace">
                                        <input type="text" name="test_days" id="test_days" class="wide_textfield only_digits inline_field">
                                    </span>
                                </div>

                                <div class="elements_wrapper">
                                    <div class="elements_wrapper_shrinkable js_parent">
                                        <div class="this_element_name">Nájezd v km</div>
                                        <span class="no_whitespace">
                                            <input type="text" name="test_mileage" id="test_mileage" class="wide_textfield only_digits inline_field">
                                        </span>
                                    </div>
                                </div>

                                <div class="elements_wrapper_shrinkable">
                                    <div class="this_element_name">&nbsp;</div>
                                    <span class="no_whitespace">
                                        <div class="discount_test discount_price_test">Vypočítat</div>
                                    </span>
                                </div>

                            </div>

                            <div class="test_final_price"><span>0</span></div>

                            <div class="admin_block_divider">&nbsp;</div>


                        </div>

                    <?php endif; ?>

                <?php endif; ?>

            </div>

            <div class="cars_top_wrap_right">

                <div class="sticker">

                    <div class="sticker_element visibility_element">
                        <div class="sticker_element_head">
                            <div class="inliner_description">Zobrazit na webu:</div>
                            <div class="inliner status<?php if ($car['language_status'] != 'active') echo ' inactive'; ?>" data-language="<?= Yii::$app->params['currentLanguage']; ?>" data-language-id="<?= $car['language_id']; ?>">
                                &nbsp;
                            </div>
                        </div>
                    </div>

                    <div class="save">Uložit změny</div>

                </div>

            </div>

        </div>

    </div>

</div>

<script>
    const pagetype = 'cars edit';
    const mileage_discount_test_ajax_url = <?php echo json_encode(\Yii::$app->urlManager->createUrl('/administrace/car/mileage-chart')) ?>;
    const days_discount_test_ajax_url = <?php echo json_encode(\Yii::$app->urlManager->createUrl('/administrace/car/days-chart')) ?>;
    const price_discount_test_ajax_url = <?php echo json_encode(\Yii::$app->urlManager->createUrl('/administrace/car/test-price')) ?>;
    const save_car_ajax_url = <?php echo json_encode(\Yii::$app->urlManager->createUrl('/administrace/car/save-car')) ?>;
    const is_primary_language = <?php echo json_encode($isPrimaryLanguage) ?>;
    const car_id = <?php echo json_encode($car['id']) ?>;
</script>