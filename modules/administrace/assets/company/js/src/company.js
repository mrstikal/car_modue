import { on } from '/modules/common/base-js/base-functions.js';
import { show } from '/modules/common/base-js/base-functions.js';
import { hide } from '/modules/common/base-js/base-functions.js';
import { processInputs } from '/modules/common/base-js/base-functions.js';

document.addEventListener('DOMContentLoaded', function (event) {

    let masked = IMask(document.querySelector('#fin_dph_amount'), {
        mask: Number,
        scale: 0,
        signed: false,
        max: 99,
        min: 0,
    })

    on('.save', 'click', function () {

        let processor = new processInputs();

        processor.optionals = [
            '#fin_dph', '#fin_dph_amount', '#fin_account_number', '#fin_bank_code', '#fin_ico', '#fin_dic', '#fin_registration', '#place_company_name', '#place_company_street', '#place_company_town',
            '#place_zip', '#place_state', '#place_infoline', '#place_email', '#place_web', '#place_opening', '#branch_street', '#branch_town', '#branch_zip', '#branch_state',
        ];

        let result = processor.process();


        show('.load_overlay');

        fetch(save_company_ajax_url, {
            method: 'POST',
            body: result,
            cache: 'no-cache',
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response error');
                }
                return response.json();
            })
            .then(data => {
                hide('.load_overlay');
                show_success.play(0);
            })
            .catch(function (err) {
                console.log(err);
            });

    })

})