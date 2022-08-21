<?php

use app\modules\administrace\assets\texts\TextsAsset;

TextsAsset::register($this);
?>

<div class="administrace-default-index">

	<div class="admin_top_wrap">

		<h1 class="admin_h1">Nastavení pro formulář "Kontrola vozidla"</h1>
		<div class="cleaner">&nbsp;</div>

		<div class="admin_block" style="margin-bottom: 20px;">

			<h2 class="admin_h2">Základní kontrola</h2>
			<div style="margin-bottom: 10px;">Seznam položek ke kontrole, jednotlivé položky oddělte středníkem</div>

			<textarea class="wide_textfield" name="texts_basic_check" id="texts_basic_check" rows=4><?= $basicCheck; ?></textarea>

			<div class="admin_spacer">&nbsp;</div>

		</div>

		<div class="admin_block">

			<h2 class="admin_h2">Spotřební materiál</h2>
			<div style="margin-bottom: 10px;">Seznam položek ke kontrole, jednotlivé položky oddělte středníkem</div>

			<textarea class="wide_textfield" name="texts_consumables_check" id="texts_consumables_check" rows=4><?= $consumablesCheck; ?></textarea>

			<div class="admin_spacer">&nbsp;</div>

		</div>

		<div class="save save_car_check">Uložit změny</div>

	</div>


	<script>
		const pagetype = "car check";
		const save_car_check_ajax_url = <?php echo json_encode(\Yii::$app->urlManager->createUrl('/administrace/texts/save-car-check')) ?>;
	</script>