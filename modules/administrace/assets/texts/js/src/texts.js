import { on } from '/modules/common/base-js/base-functions.js';
import { show } from '/modules/common/base-js/base-functions.js';
import { hide } from '/modules/common/base-js/base-functions.js';

document.addEventListener('DOMContentLoaded', function (event) {

    function init_editor(target) {
        tinymce.init({
            selector: target,
            menubar: false,
            language: 'cs',
            toolbar: 'undo redo | formatselect | bold italic underline strikethrough subscript superscript | numlist bullist | removeformat | alignleft aligncenter alignright code',
            plugins: "code fullscreen textcolor autolink autoresize emoticons link paste lists table",
            textcolor_cols: 3,
            textcolor_rows: 1,
            textcolor_map: [
                'f41839', 'Červená DEX',
                '1c2540', 'Černá DEX',
            ],
            autoresize_bottom_margin: 10,
            autoresize_min_height: 50,
            paste_as_text: true,
            block_formats: 'Paragraph=p;Header 1=h1;Header 2=h2;',
            fontsize_formats: '8px 9px 10px 11px 12px 14px 16px 18px 22px 24px 28px 36px 42px',
            object_resizing: false,
            skin: "custom",
            elementpath: false,
            branding: false,
            preview_styles: false,
            entities: '160,nbsp,162,cent,8364,euro,163,pound',
            entity_encoding: "named",
            content_css: tmce_styles_url,
            extended_valid_elements: 'pagebreak',
            custom_elements: 'pagebreak',
            setup: function (editor) {
                editor.on('change', function () {
                    editor.save();
                });
            },
        });
    }

    function ajaxSave(send, url) {

        show(".load_overlay");

        fetch(url, {
            method: 'POST',
            body: send,
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
    }

    if (typeof pagetype != 'undefined' && pagetype == 'contract') {

        init_editor('#texts_contract');

        on('.save_contract', 'click', function () {
            let data = new FormData();
            data.append('texts_contract', tinymce.get("texts_contract").getContent());

            ajaxSave(data, save_contract_ajax_url);
        })
    }

    if (typeof pagetype != 'undefined' && pagetype == 'terms_and_conditions') {

        init_editor('#texts_terms_and_conditions');

        on('.save_terms_and_conditions', 'click', function () {
            let data = new FormData();
            data.append('texts_terms_and_conditions', tinymce.get("texts_terms_and_conditions").getContent());

            ajaxSave(data, save_terms_and_conditions_ajax_url);
        })

    }

    if (typeof pagetype != 'undefined' && pagetype == 'car check') {

        $('.save_car_check').on('click', function () {
            let data = new FormData();
            data.append('texts_basic_check', document.querySelector('#texts_basic_check').value.trim());
            data.append('texts_consumables_check', document.querySelector('#texts_consumables_check').value.trim());

            ajaxSave(data, save_car_check_ajax_url);
        })
    }

})