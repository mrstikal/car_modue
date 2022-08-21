<?php

use app\modules\administrace\assets\order\OrderAsset;
use app\modules\administrace\assets\booking\BookingAsset;

BookingAsset::register($this);
OrderAsset::register($this);
?>

<div class="administrace-default-index">

	<div class="admin_top_wrap">

		<h1 class="admin_h1">Číslování faktur a pokladních bločků, variabilní symboly</h1>
		<div class="cleaner">&nbsp;</div>

		<div class="admin_block" style="margin-bottom: 30px;">

			<h2 class="admin_h2" style="padding-bottom: 8px;">Faktura</h2>
			<div class="admin_help" style="font-style:normal;"><strong>Prefix:</strong> je připojen na začátek čísla faktury, může obsahovat písmena i čísla. Maximálně 10 znaků.</div>
			<div class="admin_help" style="padding-bottom: 15px;font-style:normal;"><strong>Číslo:</strong> je připojeno za prefix. Pouze číslice. Před číslo jsou doplněny 0 do řádu desítek tisíc.</div>

			<div class="elements_wrapper">

				<div class="elements_wrapper_shrinkable">
					<div class="this_element_name bigger">Prefix</div>
					<input type="text" class="shortest wide_textfield nomargin prefix" id="invoice_numbering_prefix" name="invoice_numbering_prefix" value="<?= $this->params['invoice_numbering_prefix']; ?>">
				</div>

				<div class="elements_wrapper_shrinkable">
					<div class="this_element_name bigger">Číslo</div>
					<input type="text" class="shortest wide_textfield nomargin only_digits_limited" id="invoice_numbering_number" name="invoice_numbering_number" value="<?= $this->params['invoice_numbering_number']; ?>">
				</div>

				<div class="elements_wrapper_shrinkable">
					<div class="invoice_numbering_example">Ukázka výsledného čísla faktury: <span><?= $this->params['invoice_numbering_prefix'] . str_pad($this->params['invoice_numbering_number'], 5, '0', STR_PAD_LEFT); ?></span></div>
				</div>

			</div>

		</div>

		<div class="admin_block" style="margin-bottom: 30px;">

			<h2 class="admin_h2" style="padding-bottom: 8px;">Pokladní bloček</h2>
			<div class="admin_help" style="font-style:normal;"><strong>Prefix:</strong> je připojen na začátek čísla bločku, může obsahovat písmena i čísla. Maximálně 10 znaků.</div>
			<div class="admin_help" style="padding-bottom: 15px;font-style:normal;"><strong>Číslo:</strong> je připojeno za prefix. Pouze číslice. Před číslo jsou doplněny 0 do řádu desítek tisíc.</div>

			<div class="elements_wrapper">

				<div class="elements_wrapper_shrinkable">
					<div class="this_element_name bigger">Prefix</div>
					<input type="text" class="shortest wide_textfield nomargin prefix" id="cash_register_numbering_prefix" name="cash_register_numbering_prefix" value="<?= $this->params['cash_register_numbering_prefix']; ?>">
				</div>

				<div class="elements_wrapper_shrinkable">
					<div class="this_element_name bigger">Číslo</div>
					<input type="text" class="shortest wide_textfield nomargin only_digits_limited" id="cash_register_numbering_number" name="cash_register_numbering_number" value="<?= $this->params['cash_register_numbering_number']; ?>">
				</div>

				<div class="elements_wrapper_shrinkable">
					<div class="cash_register_numbering_example">Ukázka výsledného čísla bločku: <span><?= $this->params['cash_register_numbering_prefix'] . str_pad($this->params['cash_register_numbering_number'], 5, '0', STR_PAD_LEFT); ?></span></div>
				</div>

			</div>

		</div>

		<div class="admin_block hidden">

			<h2 class="admin_h2" style="padding-bottom: 8px;">Přičíst k variabilnímu symbolu</h2>
			<div class="admin_help" style="font-style:normal; padding-bottom: 15px;">
				Tento pararmetr slouží v případě, že nechcete, aby zákazník viděl velmi nízké hodnoty variabilního symbolu.<br>
				Variabilní symbol se vytváří z ID rezervace. Toto ID začíná na číslu 1. Pokud zde zadáte jakoukoliv číselnou hodnotu, tato se přičte k variablnímu symbolu.<br>
				Příklad: ID rezervace je 8, zde zadaná hodnota např. 100. Základ variabilního symbolu bude 108.<br>
				Poslední číslice variabilního symbolu udává, zdali je faktura za kauci (0) nebo pronájem (1).
			</div>

			<div class="elements_wrapper">
				<div class="elements_wrapper_shrinkable">
					<div class="this_element_name bigger">Přičíst</div>
					<input type="text" class="shortest wide_textfield nomargin only_digits_limited" id="variable_symbol_base" name="variable_symbol_base" value="<?= $this->params['variable_symbol_base']; ?>">
				</div>
			</div>

		</div>



		<div class="admin_spacer">&nbsp;</div>
		<div class="save">Uložit změny</div>

	</div>


	<script>
		const pagetype = "order numbering";
		const save_places_ajax_url = <?php echo json_encode(\Yii::$app->urlManager->createUrl('/administrace/order/save-places')) ?>;
		const save_numberings_ajax_url = <?php echo json_encode(\Yii::$app->urlManager->createUrl('/administrace/order/save-papers-numbering')) ?>;
	</script>