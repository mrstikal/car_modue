<?php

use app\modules\administrace\assets\cars\CarAsset;
use app\modules\common\components\CommonFunctions;

CarAsset::register($this);

$surcharge_for_rider = $this->params['surcharge_for_rider'];
$bail_with_rider = $this->params['bail_with_rider'];
$standard_bail = $this->params['standard_bail'];
$price_rounding = $this->params['price_rounding'];
?>

<div class="administrace-default-index">

	<div class="admin_top_wrap">

		<h1 class="admin_h1">Nastavení pro vozidla</h1>
		<div class="cleaner">&nbsp;</div>

		<div class="admin_block">

			<h2 class="admin_h2">Standardní výše vratné kauce</h2>
			<div class="admin_help">Lze upravit i pro každé vozidlo zvlášť</div>
			<input class="only_digits admin_medium_input narrow num_per_page" id="standard_bail" value="<?= $standard_bail; ?>" style="display: inline-block;">&nbsp;&nbsp;Kč

			<div class="admin_spacer">&nbsp;</div>

			<h2 class="admin_h2">Výše vratné kauce při připojištění</h2>
			<div class="admin_help">Lze upravit i pro každé vozidlo zvlášť</div>
			<input class="only_digits admin_medium_input narrow num_per_page" id="bail_with_rider" value="<?= $bail_with_rider; ?>" style="display: inline-block;">&nbsp;&nbsp;Kč

			<div class="admin_spacer">&nbsp;</div>

			<h2 class="admin_h2">Příplatek k ceně při připojištění</h2>
			<div class="admin_help">Lze upravit i pro každé vozidlo zvlášť</div>
			<input class="only_digits admin_medium_input narrow num_per_page" id="surcharge_for_rider" value="<?= $surcharge_for_rider; ?>" style="display: inline-block;">&nbsp;&nbsp;%

			<div class="admin_spacer">&nbsp;</div>

			<h2 class="admin_h2">Zaokrouhlování ceny na webu</h2>
			<div class="admin_help">Jak zaokrouhlenou cenu vidí uživatelé v rezervačním formuláři</div>
			<select class="select_type" name="price_rounding" id="price_rounding">
				<option value="-2" <?php CommonFunctions::selected($price_rounding, -2); ?>>Zaokrouhlit na stokoruny</option>
				<option value="-1" <?php CommonFunctions::selected($price_rounding, -1); ?>>Zaokrouhlit na desetikoruny</option>
				<option value="0" <?php CommonFunctions::selected($price_rounding, 0); ?>>Zaokrouhlit na koruny</option>
			</select>

			<div class="admin_spacer">&nbsp;</div>

		</div>

		<div class="save">Uložit změny</div>

	</div>


	<script>
		const pagetype = "car settings";
		const save_car_setting_ajax_url = <?php echo json_encode(\Yii::$app->urlManager->createUrl('/administrace/car/save-settings')) ?>;
	</script>