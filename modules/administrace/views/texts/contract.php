<?php

use app\modules\administrace\assets\texts\TextsAsset;
use app\assets\TinyMCEAsset;

TextsAsset::register($this);
TinyMCEAsset::register($this);
?>

<div class="administrace-default-index">

	<div class="admin_top_wrap">

		<h1 class="admin_h1">Úprava textů smlouvy</h1>
		<div class="cleaner">&nbsp;</div>

		<div class="admin_block">

			<h2 class="admin_h2">Text začíná až kapitolou "II. Předmět"</h2>
			<div style="margin-bottom: 10px;">Pokud chcete vložit pevný konec stránky, na dané místo umístěte text {pagebreak}</div>

			<textarea name="texts_contract" id="texts_contract"><?= $contract; ?></textarea>

			<div class="admin_spacer">&nbsp;</div>

		</div>

		<div class="save save_contract">Uložit změny</div>

	</div>


	<script>
		const pagetype = "contract";
		const save_contract_ajax_url = <?php echo json_encode(\Yii::$app->urlManager->createUrl('/administrace/texts/save-contract')) ?>;
		const tmce_styles_url = <?php echo json_encode(\Yii::$app->assetManager->getPublishedUrl('@app/modules/administrace/assets/texts') . '/css/editor-styles.css') ?>;
	</script>