<?php

use app\modules\car\models\Car;
use app\modules\administrace\components\UserLevels;
use app\modules\administrace\models\Languages;
use app\assets\TinyMCEAsset;
use app\modules\administrace\assets\serviceinfos\ServiceInfoAsset;
use app\modules\common\components\CommonFunctions;

TinyMCEAsset::register($this);
ServiceInfoAsset::register($this);

?>


<div class="administrace-default-index">

    <div class="admin_top_wrap">

        <h1>Nové servisní info</h1>
        <div class="cleaner">&nbsp;</div>
    </div>

    <div class="admin_top_wrap infos_wrap">

        <div class="flex_wrapper topped_wrapper">

            <div class="infos_top_wrap_left">

                <div class="admin_block no_pad" style="padding-bottom: 20px;">

                    <div class="elements_wrapper">

                        <div class="elements_wrapper_shrinkable">
                            <div class="this_element_name">Vozidlo</div>
                            <div class="cleaner">&nbsp;</div>
                            <select name="car_id" id="car_id" class="select_type">
                                <?php foreach ($this->params['select_types']['car'] as $car) : ?>
                                    <option value="<?= $car['id']; ?>"><?= $car['language_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="flex_half_divider bigger">&nbsp;</div>

                        <div class="elements_wrapper_shrinkable">
                            <div class="this_element_name">Zapsal</div>
                            <div class="cleaner">&nbsp;</div>
                            <select name="owner" id="owner" class="select_type">
                                <?php foreach ($this->params['select_types']['operators'] as $operator) : ?>
                                    <option value="<?= $operator['id']; ?>" <?php CommonFunctions::selected($operator['id'], \Yii::$app->user->id); ?>><?= $operator['owner']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="flex_half_divider bigger">&nbsp;</div>

                        <div class="elements_wrapper_shrinkable">
                            <div class="this_element_name">Stav</div>
                            <div class="cleaner">&nbsp;</div>
                            <select name="status" id="status" class="select_type">
                                <?php foreach ($this->params['select_types']['statuses'] as $key => $value) : ?>
                                    <option value="<?= $key; ?>" <?php CommonFunctions::selected($key, 'unsolved'); ?>><?= $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>

                </div>

                <div class="admin_block no_pad" style="padding-bottom: 20px;">

                    <div class="this_element_name">Titulek (název) *</div>
                    <input type="text" name="title" id="title" class="wide_textfield required">

                    <div class="admin_horizontal_spacer">&nbsp;</div>

                    <div class="this_element_name">Text</div>
                    <textarea name="description" id="description"></textarea>

                </div>

                <div class="admin_block no_pad" style="padding-bottom: 20px;">
                    <div class="this_element_name">Cena za vyřešení</div>
                    <span class="no_whitespace">
                        <input type="text" name="amount" id="amount" class="wide_textfield shorter only_digits"> Kč
                    </span>
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
    const pagetype = 'service info new';
    const save_info_ajax_url = <?php echo json_encode(\Yii::$app->urlManager->createUrl('/administrace/service-info/save')) ?>;
    const info_id = 0;
</script>