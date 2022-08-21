<?php

use app\modules\common\components\CommonFunctions;
use app\modules\administrace\assets\company\CompanyAsset;


CompanyAsset::register($this);

$fin = $this->params['fin'];
$place = $this->params['place'];
$branch = $this->params['branch'];
?>

<div class="administrace-default-index">

	<div class="admin_top_wrap">

		<h1 class="admin_h1">Nastavení pro firmu</h1>
		<div class="cleaner">&nbsp;</div>

		<div class="admin_block" style="margin-bottom: 30px;">

			<h2 class="admin_h2">Fin. údaje</h2>
			<div class="admin_spacer">&nbsp;</div>

			<label class="show_id">
				Plátce DPH?
				<label class="checkbox_parent">
					<input type="checkbox" class="checkboxed" value="1" name="fin_dph" id="fin_dph" <?php CommonFunctions::checked(1, $fin['dph']); ?>>
					<span class="checkbox_label">
						<span class="inner_checkbox_checkbox">&nbsp;</span>
					</span>
				</label>
			</label>

			<div class="admin_spacer">&nbsp;</div>

			<div class="show_id">Výše DPH</div>
			<div class="cleaner">&nbsp;</div>
			<input type="text" style="display: inline-block" class="admin_short_input cookies_bar_bg_color dph_amount" name="fin_dph_amount" id="fin_dph_amount" value="<?= $fin['dph_amount']; ?>">&nbsp;%

			<div class="admin_spacer">&nbsp;</div>

			<div class="batch_inliner bottomed">
				<div class="show_id">Číslo účtu</div>
				<input type="text" class="admin_short_input cookies_bar_bg_color account_number" name="fin_account_number" id="fin_account_number" value="<?= $fin['account_number']; ?>">
			</div>

			<div class="batch_inliner bottomed">
				<div class="show_id">Kód banky</div>
				<input type="text" class="admin_short_input cookies_bar_bg_color bank_code" name="fin_bank_code" id="fin_bank_code" value="<?= $fin['bank_code']; ?>">
			</div>

			<div class="batch_inliner bottomed">
				<div class="show_id">IČO</div>
				<input type="text" class="admin_short_input cookies_bar_bg_color ico" name="fin_ico" id="fin_ico" value="<?= $fin['ico']; ?>">
			</div>

			<div class="batch_inliner bottomed">
				<div class="show_id">DIČ</div>
				<input type="text" class="admin_short_input cookies_bar_bg_color dic" name="fin_dic" id="fin_dic" value="<?= $fin['dic']; ?>">
			</div>

			<div class="cleaner">&nbsp;</div>

			<div class="show_id">Zápis v OR</div>
			<input type="text" class="admin_medium_input full cookies_bar_bg_color registration" name="fin_registration" id="fin_registration" value="<?= $fin['registration']; ?>">

		</div>

		<div class="admin_block" style="margin-bottom: 30px;">

			<h2 class="admin_h2">Sídlo</h2>
			<div class="admin_spacer">&nbsp;</div>

			<div class="show_id">Název firmy</div>
			<input type="text" class="admin_medium_input full cookies_bar_bg_color company_name" name="place_company_name" id="place_company_name" value="<?= $place['company_name']; ?>">
			<div class="admin_spacer">&nbsp;</div>

			<div class="show_id">Ulice, ČP</div>
			<input type="text" class="admin_medium_input full cookies_bar_bg_color company_street" name="place_company_street" id="place_company_street" value="<?= $place['company_street']; ?>">
			<div class="admin_spacer">&nbsp;</div>

			<div class="show_id">PSČ</div>
			<input type="text" class="admin_medium_input full cookies_bar_bg_color zip" name="place_zip" id="place_zip" value="<?= $place['zip']; ?>">
			<div class="admin_spacer">&nbsp;</div>

			<div class="show_id">Město</div>
			<input type="text" class="admin_medium_input full cookies_bar_bg_color company_town" name="place_company_town" id="place_company_town" value="<?= $place['company_town']; ?>">
			<div class="admin_spacer">&nbsp;</div>

			<div class="show_id">Stát</div>
			<input type="text" class="admin_medium_input full cookies_bar_bg_color state" name="place_state" id="place_state" value="<?= $place['state']; ?>">
			<div class="admin_spacer">&nbsp;</div>

			<div class="show_id">Infolinka</div>
			<input type="text" class="admin_medium_input full cookies_bar_bg_color infoline" name="place_infoline" id="place_infoline" value="<?= $place['infoline']; ?>">
			<div class="admin_spacer">&nbsp;</div>

			<div class="show_id">Email</div>
			<input type="text" class="admin_medium_input full cookies_bar_bg_color email" name="place_email" id="place_email" value="<?= $place['email']; ?>">
			<div class="admin_spacer">&nbsp;</div>

			<div class="show_id">Web</div>
			<input type="text" class="admin_medium_input full cookies_bar_bg_color web" name="place_web" id="place_web" value="<?= $place['web']; ?>">
			<div class="admin_spacer">&nbsp;</div>

			<div class="show_id">Provozní doba</div>
			<input type="text" class="admin_medium_input full cookies_bar_bg_color opening" name="place_opening" id="place_opening" value="<?= $place['opening']; ?>">

		</div>

		<div class="admin_block">

			<h2 class="admin_h2">Provozovna</h2>
			<div class="admin_spacer">&nbsp;</div>

			<div class="show_id">Ulice, ČP</div>
			<input type="text" class="admin_medium_input full cookies_bar_bg_color branch_street" name="branch_street" id="branch_street" value="<?= $branch['branch_street']; ?>">
			<div class="admin_spacer">&nbsp;</div>

			<div class="show_id">PSČ</div>
			<input type="text" class="admin_medium_input full cookies_bar_bg_color branch_zip" name="branch_zip" id="branch_zip" value="<?= $branch['branch_zip']; ?>">
			<div class="admin_spacer">&nbsp;</div>

			<div class="show_id">Město</div>
			<input type="text" class="admin_medium_input full cookies_bar_bg_color branch_town" name="branch_town" id="branch_town" value="<?= $branch['branch_town']; ?>">
			<div class="admin_spacer">&nbsp;</div>

			<div class="show_id">Stát</div>
			<input type="text" class="admin_medium_input full cookies_bar_bg_color branch_state" name="branch_state" id="branch_state" value="<?= $branch['branch_state']; ?>">
			<div class="admin_spacer">&nbsp;</div>

		</div>

		<div class="admin_spacer">&nbsp;</div>

	</div>

	<div class="save">Uložit změny</div>

</div>


<script>
	const pagetype = "company";
	const save_company_ajax_url = <?php echo json_encode(\Yii::$app->urlManager->createUrl('/administrace/company/save-settings')) ?>;
</script>