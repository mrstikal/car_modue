<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="utf-8">
    <title>VOP</title>
    {css_styles}
</head>

<body>

    <htmlpageheader name="firstpageheader" style="display:none;">

        <span class="header_wrapper">

            <div class="logo">&nbsp;</div>
            <div class="aligner">
                <span class="document_type orange_fill orange_border">Všeobecné obchodní podmínky</span>
            </div>
            <div class="header_divider orange_border"></div>

        </span>
        <div class="header_spacer">&nbsp;</div>

    </htmlpageheader>

    <htmlpagefooter name="firstpagefooter" style="display:none">

        <div class="footer_wrapper">

            <div class="invoice_divider orange_border"></div>

            <table width="100%" class="footer_table">
                <tr>
                    <td width="50%" class="orange_text"><strong>{company_name}</strong></td>
                    <td></td>
                </tr>

                <tr>
                    <td width="50%">{company_street}</td>
                    <td></td>
                </tr>

                <tr>
                    <td width="50%">{zip} {company_town}</td>
                    <td style="text-align: right;">Zákaznická linka: {infoline}</td>
                </tr>

                <tr>
                    <td width="50%">{state}</td>
                    <td style="text-align: right;">{web}</td>
                </tr>
            </table>

        </div>
    </htmlpagefooter>

    <htmlpageheader name="otherpageheader" style="display:none">
    </htmlpageheader>

    <htmlpagefooter name="otherpagesfooter" style="display:none">
    </htmlpagefooter>

    <div class="content">

        {vop_content}

    </div>

</body>