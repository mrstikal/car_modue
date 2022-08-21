<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="utf-8">
    <title>Smlouva</title>
    {css_styles}
</head>

<body>

    <div class="content">

        <h1>I. Účastníci</h1>

        <div class="we_wrapper">

            <table class="participants we">

                <tr>
                    <td class="left">Společnost:</td>
                    <td class="right">{we_company_name}</td>
                </tr>

                <tr>
                    <td class="left">Se sídlem:</td>
                    <td class="right">{we_company_address}</td>
                </tr>

                <tr>
                    <td class="left">Tel.:</td>
                    <td class="right">{we_infoline}</td>
                </tr>

                <tr>
                    <td class="left">IČO:</td>
                    <td class="right">{we_ico}</td>
                </tr>

                {we_dic_row}

            </table>

            <div class="top_space">(dále jen <strong>pronajímatel</strong>)</div>

            <div class="top_space">a</div>

        </div>

        <div class="customer_wrapper">

            <table class="participants customer">

                {customer_table}

            </table>

            <div class="top_space">(dále jen <strong>nájemce</strong>)</div>

        </div>

        {contrac_text}

        <div class="signatures_wrapper">

            <div class="one_signature left_signature">
                <div class="signature_header">Pronajímatel</div>
                <div class="signature_line">&nbsp;</div>
                <div class="signature_text">razítko, datum a místo podpisu</div>
            </div>

            <div class="one_signature right_signature">
                <div class="signature_header">Nájemce</div>
                <div class="signature_line">&nbsp;</div>
                <div class="signature_text">(razítko), datum a místo podpisu</div>
                <div class="signature_small">Souhlasím s VOP, které jsou přílohou této smlouvy</div>
            </div>

        </div>

    </div>

</body>