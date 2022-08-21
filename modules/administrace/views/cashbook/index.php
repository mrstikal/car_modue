<?php

use app\modules\administrace\assets\cashbook\CashbookAsset;

CashbookAsset::register($this);
?>

<div class="administrace-default-index">

    <div class="admin_top_wrap">

        <h1>Přehled příjmů</h1>

    </div>

    <div class="cleaner">&nbsp;</div>

    <div class="list_records">

        <div class="list_records_inner">

            <div class="pickers">

                <div class="one_picker">
                    <div>Období OD</div>
                    <input type="text" class="datetime_picker from" id="date_from" readonly>
                </div>


                <div class="one_picker">
                    <div>Období DO</div>
                    <input type="text" class="datetime_picker to" id="date_to" readonly>
                </div>

                <div class="save pickers_run">Zobrazit</div>

            </div>

            <div class="sums">
                <div class="invoices_found overals">Počet faktur: <span></span></div>
                <div class="total_sum overals">Příjmy celkem: <span></span></div>
            </div>

            <table class="admin_table cashbook_table">
                <thead>
                    <tr>
                        <th>Vozidlo</th>
                        <th>Částka</th>
                        <th>Zákazník</th>
                        <th>Datum vystavení FA</th>
                        <th>Číslo FA</th>
                        <th>Variabilní symbol</th>
                        <th>Datum vystavení PD</th>
                        <th>Číslo PD</th>
                    </tr>
                </thead>

                <tbody></tbody>
            </table>

        </div>
    </div>

</div>

<script>
    const pagetype = 'cashbook';
    const load_ajax_url = <?php echo json_encode(\Yii::$app->urlManager->createUrl('/administrace/cashbook/get-cashbook')) ?>;
    const defaultStart = <?php echo json_encode($this->params['defaultStart']) ?>;
    const defaultEnd = <?php echo json_encode($this->params['defaultEnd']) ?>;
</script>