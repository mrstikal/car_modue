<?php

use app\modules\administrace\assets\texts\TextsAsset;
use app\assets\TinyMCEAsset;

TextsAsset::register($this);
TinyMCEAsset::register($this);
?>

<div class="administrace-default-index">

	<div class="admin_top_wrap">

		<h1 class="admin_h1">Úprava textů VOP</h1>
		<div class="cleaner">&nbsp;</div>

		<div class="admin_block">

			<div style="margin-bottom: 10px;">Pokud chcete vložit pevný konec stránky, na dané místo umístěte text {pagebreak}</div>

			<textarea name="texts_terms_and_conditions" id="texts_terms_and_conditions"><?= $terms_and_conditions; ?></textarea>

			<div class="admin_spacer">&nbsp;</div>

		</div>

		<div class="save save_terms_and_conditions">Uložit změny</div>

	</div>


	<script>
		const pagetype = "terms_and_conditions";
		const save_terms_and_conditions_ajax_url = <?php echo json_encode(\Yii::$app->urlManager->createUrl('/administrace/texts/save-terms-and-conditions')) ?>;
		const tmce_styles_url = <?php echo json_encode(\Yii::$app->assetManager->getPublishedUrl('@app/modules/administrace/assets/texts') . '/css/editor-styles.css') ?>;
	</script>