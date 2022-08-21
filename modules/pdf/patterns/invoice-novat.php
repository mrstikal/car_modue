<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="utf-8">
    <title>Faktura</title>
    {css_styles}
</head>

<body>

    <div class="content">

        <div class="logo">&nbsp;</div>
        <div class="fa_number">Faktura číslo: <strong>{invoice_number}</strong></div>
        <div class="cleaner">&nbsp;</div>

        <div class="invoice_participants">

            <div class="invoice_participants_one invoice_participants_we">

                <span class="heading orange_fill orange_border">Dodavatel</span>
                <div class="invoice_participants_underline orange_border"></div>

                <div class="company our_company">
                    <div><strong>{we_company_name}</strong></div>
                    <div>{we_company_street}</div>
                    <div>{we_zip} {we_company_town}</div>
                    <div>{we_state}</div>
                    <div>&nbsp;</div>
                    <div>{we_ico}</div>
                    <div>{we_dic}</div>
                </div>

            </div>

            <div class="invoice_participants_one invoice_participants_customer">

                <span class="heading orange_fill orange_border">Odběratel</span>
                <div class="invoice_participants_underline orange_border"></div>

                <div class="company customer">
                    <div><strong>{customer_name}</strong></div>
                    <div>{customer_street}</div>
                    <div>{customer_city}</div>
                    <div>{customer_state}</div>
                    <div>&nbsp;</div>
                    <div>{customer_ico}</div>
                    <div>{customer_dic}</div>
                </div>

            </div>

            <div class="cleaner">&nbsp;</div>

        </div>

        <div class="invoice_divider orange_border"></div>

        <div class="invoice_props">

            <div class="invoice_one_prop invoice_props_left">
                <table class="invoice_one_prop_table">
                    <tr>
                        <td>Forma úhrady: </td>
                        <td>{payment_method}</td>
                    </tr>
                    <tr>
                        <td>Číslo úctu: </td>
                        <td>{we_account_number}</td>
                    </tr>
                    <tr>
                        <td>Kód banky: </td>
                        <td>{we_bank_code}</td>
                    </tr>
                    <tr>
                        <td>Variabilní symbol: </td>
                        <td>{variable_symbol}</td>
                    </tr>
                </table>
            </div>

            <div class="invoice_one_prop invoice_props_right">

                <table class="invoice_one_prop_table">
                    <tr>
                        <td>Datum vystavení: </td>
                        <td>{issue_date}</td>
                    </tr>
                    <tr>
                        <td>Datum splatnosti: </td>
                        <td>{due_at}</td>
                    </tr>
                    <tr>
                        <td>Datum zdan. plnění: </td>
                        <td>{supply_date}</td>
                    </tr>
                </table>

            </div>

            <div class="cleaner">&nbsp;</div>

        </div>

        <div class="invoice_divider orange_border"></div>

        <div class="invoice_numbers">

            <table class="invoice_numbers_table">
                <tr>
                    <td class="strong">Název položky</td>
                    <td class="strong right">Množství</td>
                    <td class="strong right">Jedn. cena</td>
                    <td class="strong right last">Celkem</td>
                </tr>
                <tr>
                    <td>{car_sentence}</td>
                    <td class="right">1</td>
                    <td class="right">{order_price}</td>
                    <td class="right last">{order_price}</td>
                </tr>
            </table>

            <div class="overal_price orange_fill">Celkem k úhradě:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{order_price}</div>
        </div>

        <div class="invoice_divider orange_border"></div>

    </div>

</body>

</html>