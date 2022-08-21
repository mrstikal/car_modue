<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="utf-8">
    <title>Předávací protokol</title>
    {css_styles}
</head>

<body>

    <div class="content">

        <table class="basic_props">

            <tr>
                <td class="first name">Vozidlo:</td>
                <td class="second value">{car_name}</td>
                <td class="third name">Nájemce/zástupce spol.:</td>
                <td class="fourth value">{customer_name}</td>
            </tr>

            <tr>
                <td class="first name">RZ vozidla:</td>
                <td class="second value">{car_spz}</td>
                <td class="third name">RČ nájemce/zástupce spol.:</td>
                <td class="fourth value">{birth_number}</td>
            </tr>

        </table>

        <table class="mini_table">

            <tr>
                <td class="first name">Trvalý pobyt nájemce/zástupce spol:</td>
                <td class="value">{permanent_residence}</td>
            </tr>

        </table>

        <div class="inner_spacer">&nbsp;</div>

        <span class="chapter_wrapper">

            <div class="chapter_aligner">
                <span class="chapter_type orange_fill orange_border">Technický stav předávaného vozidla</span>
            </div>
            <div class="chapter_divider orange_border"></div>

        </span>

        <div class="header_spacer">&nbsp;</div>

        <table class="mini_table">

            <tr>
                <td class="first name">Stav nabití baterie:</td>
                <td class="outerlined">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%</td>
                <td class="second name">Ovládací panel funkční:</td>
                <td class="value">ANO / NE *</td>
                <td class="second name">Stav kilometrů:</td>
                <td class="value">{mileage_now}</td>
            </tr>

        </table>

        <div class="header_spacer">&nbsp;</div>

        <div class="tires_descr">Pneu vzorky:</div>

        <table class="mini_table">

            <tr>
                <td class="first name">Přední pravá:</td>
                <td class="value">{tires_front_right} %</td>
                <td class="first name">Přední levá:</td>
                <td class="value">{tires_front_left} %</td>
                <td class="first name">Zadní pravá:</td>
                <td class="value">{tires_back_right} %</td>
                <td class="first name">Zadní levá:</td>
                <td class="value">{tires_back_left} %</td>
                <td class="first name">Rezerva:</td>
                <td class="value">{tires_spare} %</td>
            </tr>

        </table>

        <div class="smaller_spacer">&nbsp;</div>

        <table class="mini_table">

            <tr>
                <td class="first name">Doklady od vozidla:</td>
                <td class="value">ANO / NE *</td>
                <td class="first name">Panorama funkční:</td>
                <td class="value">ANO / NE *</td>
            </tr>

            <tr>
                <td class="first name">Čelní sklo:</td>
                <td class="value">OK / POŠKOZENO *</td>
                <td class="first name">Koberečky:</td>
                <td class="value">ANO / NE *</td>
            </tr>

            <tr>
                <td class="first name">Dobíjecí kabely:</td>
                <td class="value">ANO / NE *</td>
                <td class="first name">Stav sedaček:</td>
                <td class="value">OK / ZNEČIŠTĚNO / POŠKOZENO *</td>
            </tr>

        </table>

        <table class="mini_table">



        </table>

        <div class="smaller_spacer">&nbsp;</div>

        <div class="general_descr">* NEHODÍCÍ SE ŠKRTNĚTE</div>

        <div class="smaller_spacer">&nbsp;</div>

        <table class="mini_table top_align_table">

            <tr>
                <td class="first name">Závady a poškození:</td>
                <td class="value">{current_condition}</td>
            </tr>

        </table>

        <div class="header_spacer">&nbsp;</div>

        <div class="payment_conditions">
            <div class="payment_conditions_head">Platební podmínky</div>
            <div class="payment_conditions_text">Nájemce přebírající vozidlo svým podpisem stvrzuje správnost protokolu a zavazuje se při vzniku jakéhokoli poškození, které vznikne na vozidle v době pronájmu uhradit v plné výši (vyjma pojistných událostí, kde se úhrada vzniklého poškození řídí dle smlouvy o pronájmu vozidla, příp.půjčovního řádu).</div>
        </div>

        <div class="medium_spacer">&nbsp;</div>

        <table class="mini_table">

            <tr>
                <td class="first name">Předáno dne:</td>
                <td class="value">{vehicle_handover_date}</td>
                <td class="first name">Předpokládaný návrat dne:</td>
                <td class="value">{vehicle_return_date}</td>
            </tr>

        </table>

        <div class="header_spacer">&nbsp;</div>

        <div class="signatures_handover">
            <div class="signatures_handover_left">Za {company_name}: <div>&nbsp;</div>
            </div>
            <div class="signatures_handover_right">Nájemce: <div>&nbsp;</div>
            </div>
        </div>

        <div class="header_spacer">&nbsp;</div>

        <span class="chapter_wrapper">

            <div class="chapter_aligner">
                <span class="chapter_type orange_fill orange_border">Stav vozidla pri převzetí</span>
            </div>
            <div class="chapter_divider orange_border"></div>

        </span>

        <div class="medium_spacer">&nbsp;</div>

        <table class="mini_table full_width_table">

            <tr>
                <td class="first name">Poškození:</td>
                <td class="underlined">&nbsp;</td>
            </tr>

            <tr>
                <td class="first name">&nbsp;</td>
                <td class="underlined">&nbsp;</td>
            </tr>

        </table>

        <div class="header_spacer">&nbsp;</div>

        <table class="mini_table">

            <tr>
                <td class="first name align_middle">K doplacení nájemcem:</td>
                <td class="outerlined">{inner_space}Kč</td>
                <td class="second name align_middle">Stav tachometru:</td>
                <td class="outerlined">{inner_space}km</td>
            </tr>

        </table>

        <div class="header_spacer">&nbsp;</div>

        <table class="mini_table">

            <tr>
                <td class="first name align_middle">Vráceno dne:</td>
                <td class="outerlined">{inner_space_more}</td>
            </tr>

        </table>

        <div class="header_spacer">&nbsp;</div>
        <div class="header_spacer">&nbsp;</div>

        <div class="signatures_return">
            <div class="signatures_return_left">Za {company_name} vozidlo převzal: <div>&nbsp;</div>
            </div>
            <div class="signatures_return_right">Nájemce: <div>&nbsp;</div>
            </div>
        </div>

    </div>

</body>