<?php

use yii\helpers\Url;
use app\modules\car\models\CarLanguage;

?>

<div class="administrace-default-index">

    <div class="admin_top_wrap">

        <h1>Přehled vozidel</h1>

    </div>

    <div class="cleaner">&nbsp;</div>
    <div class="admin_help">Pokud si nejste jisti funkcí dané ikony (tlačítka), přidržte nad ní kurzor myši déle než 1 vteřinu</div>

    <div class="list_cars_related list_records">

        <div class="list_records_inner">

            <table class="records_table">
                <tbody>
                    <?php foreach ($cars as $key => $car) : ?>

                        <tr data-id="<?= $car['id']; ?>" data-sort="<?= $key; ?>">

                            <td><?= $car['language_name']; ?></td>

                            <td>
                                <div class="inliners">

                                    <div class="inliner status<?php if ($car['language_status'] != 'active') echo ' inactive'; ?>" data-language="<?= Yii::$app->params['currentLanguage']; ?>" data-language-id="<?= $car['language_id']; ?>">
                                        &nbsp;
                                    </div>

                                    <?php
                                    $editLink = Url::base(true) . '/administrace/car/edit/' . $car['id'] . '?lang=' . strtolower(Yii::$app->params['currentLanguage']);
                                    ?>
                                    <a href="<?= $editLink; ?>" class="inliner edit">&nbsp;</a>

                                    <?php if ($this->params['is_primary_language']) : ?>

                                        <div class="inliner delete" data-id="<?= $car['id']; ?>">&nbsp;</div>
                                        <div class="inliner move">&nbsp;</div>

                                    <?php endif; ?>

                                    <div class="inliner inline_flag active_language">
                                        <img class="inline_flag_inner" src="<?= $this->params['actual_language_icon']; ?>">
                                    </div>

                                    <?php foreach ($this->params['other_languages'] as $language) : ?>

                                        <?php
                                        $link = $editLink = Url::base(true) . '/administrace/car/edit/' . $car['id'] . '?lang=' . strtolower($language['code']);
                                        if (CarLanguage::getTraslationExists($car['id'], $language['code'])) {
                                            $cssClass = ' edit_translation';
                                        } else {
                                            $cssClass = ' add_translation';
                                        }
                                        ?>

                                        <a href="<?= $link; ?>" class="inliner inline_flag<?= $cssClass; ?>">
                                            <img class="inline_flag_inner" src="<?= $language['icon']; ?>">
                                        </a>

                                    <?php endforeach; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<div class="overlay car_deletion">

    <div class="ovelay_inner">

        <div class="overlay_inner_head dangerous_action">
            <p>Opravdu chcete smazat<br>toto vozidlo?</p>
        </div>
        <div class="overlay_confirm deletion_confirm">Ano</div>
        <div class="overlay_deny deletion_cancel">Ne</div>

        <input type="hidden" class="deletion_id">

    </div>

</div>

<script>
    const pagetype = 'cars index';
    const position_ajax_url = <?php echo json_encode(\Yii::$app->urlManager->createUrl('/administrace/car/save-positions')) ?>;
    const status_ajax_url = <?php echo json_encode(\Yii::$app->urlManager->createUrl('/administrace/car/save-status')) ?>;
    const delete_ajax_url = <?php echo json_encode(\Yii::$app->urlManager->createUrl('/administrace/car/delete')) ?>;
</script>