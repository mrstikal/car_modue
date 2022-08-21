<?php

use app\modules\administrace\assets\order\OrderAsset;

OrderAsset::register($this);
?>

<div class="administrace-default-index">

	<div class="admin_top_wrap">

		<h1 class="admin_h1">Výchozí místa a časy pro převzetí a vrácení</h1>
		<div class="cleaner">&nbsp;</div>

		<div class="admin_block" style="margin-bottom: 30px;">

			<h2 class="admin_h2">Převzetí - výchozí místo</h2>
			<div class="admin_help">Lze upravit i pro každou objednávku zvlášť</div>
			<input class="wide_textfield" id="place_take_over" value="<?= $this->params['place_take_over']; ?>" style="display: inline-block;">

			<div class="admin_spacer">&nbsp;</div>

			<h2 class="admin_h2">Vrácení - výchozí místo</h2>
			<div class="admin_help">Lze upravit i pro každou objednávku zvlášť</div>
			<input class="wide_textfield" id="place_return" value="<?= $this->params['place_return']; ?>" style="display: inline-block;">
		</div>

		<div class="admin_block">

			<h2 class="admin_h2">Převzetí - výchozí čas</h2>
			<div class="admin_help">Lze upravit i pro každou objednávku zvlášť</div>
			<input class="wide_textfield" id="time_take_over" value="<?= $this->params['time_take_over']; ?>" style="display: inline-block;">

			<div class="admin_spacer">&nbsp;</div>

			<h2 class="admin_h2">Vrácení - výchozí čas</h2>
			<div class="admin_help">Lze upravit i pro každou objednávku zvlášť</div>
			<input class="wide_textfield" id="time_return" value="<?= $this->params['time_return']; ?>" style="display: inline-block;">
		</div>

		<div class="save">Uložit změny</div>

	</div>


	<script>
		const pagetype = "order places";
		const save_places_ajax_url = <?php echo json_encode(\Yii::$app->urlManager->createUrl('/administrace/order/save-places')) ?>;
	</script>