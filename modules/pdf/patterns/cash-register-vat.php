<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="utf-8">
    <title>Příjmový pokladní doklad</title>
    {css_styles}
</head>

<body>

    <div class="content">

        <div class="cash_register_heading">

            <span class="cash_register_name orange_fill orange_border">Příjmový pokladní doklad</span>

            <div class="cash_register_heading_props">
                {cash_register_number}
                <span class="cash_register_heading_prop">ze dne: <strong>{cash_register_date}</strong></span>
            </div>

            <div class="cash_register_divider_underline orange_border"></div>

            <div class="cleaner">&nbsp;</div>

        </div>

        <div class="cash_register_we">

            <div class="logo">&nbsp;</div>

            <div class="cash_register_we_props_wrapper">

                <table class="cash_register_we_props">

                    <tr>
                        <td class="right">{we_company_name}</td>
                        <td>{we_ico}</td>
                    </tr>

                    <tr>
                        <td class="right">{we_company_street}</td>
                        <td>{we_dic}</td>
                    </tr>

                    <tr>
                        <td class="right">{we_zip} {we_company_town}</td>
                        <td>tel: {we_infoline}</td>
                    </tr>

                    <tr>
                        <td class="right">{we_state}</td>
                        <td>{we_web}</td>
                    </tr>

                </table>

            </div>

            <div class="cleaner">&nbsp;</div>

        </div>

        <div class="cash_register_divider_underline orange_border"></div>

        <table class="cash_register_props">

            <tr>
                <td colspan=2 class="inner left">
                    <table class="cash_register_props_inner">
                        <tr>
                            <td class="left">Cena bez DPH:</td>
                            <td class="right">{order_dph_base}</td>
                            <td class="left">% DPH:</td>
                            <td class="right">{order_dph_amount}</td>
                            <td class="left">DPH:</td>
                            <td class="right">{order_dph}</td>
                            <td class="left">Celkem:</td>
                            <td class="right">{cash_register_price}</td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td class="left">Slovy</td>
                <td class="right">{cash_register_price_words}</td>
            </tr>

            <tr>
                <td class="left">Přijato&nbsp;od</td>
                <td class="right">{customer_name}, {customer_street}, {customer_city}, {customer_state}</td>
            </tr>

            <tr>
                <td class="left">DIČ</td>
                <td class="right">{customer_dic}</td>
            </tr>

            <tr>
                <td class="left">Účel&nbsp;platby</td>
                <td class="right">{cash_register_reason}</td>
            </tr>

        </table>

        <div class="cleaner">&nbsp;</div>

    </div>

</body>

</html>