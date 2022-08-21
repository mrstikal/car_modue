import { on } from '/modules/common/base-js/base-functions.js';
import { show } from '/modules/common/base-js/base-functions.js';
import { hide } from '/modules/common/base-js/base-functions.js';
import { processInputs } from '/modules/common/base-js/base-functions.js';
import { tooltipHelper } from '/modules/common/base-js/base-functions.js';
import { createFormData } from '/modules/common/base-js/base-functions.js';
import { dataStorage } from '/modules/common/base-js/base-functions.js';
import { addClass } from '/modules/common/base-js/base-functions.js';
import { removeClass } from '/modules/common/base-js/base-functions.js';


document.addEventListener('DOMContentLoaded', function (event) {

    let pageCounter = 1;

    let tooltipMe;
    
    function ajaxLoad(append = false) {

        if (append) {
            pageCounter++;
        } else {
            pageCounter = 1;
        }

        let inputProcessor = new processInputs();
        inputProcessor.optionals = ['#car_id', '#owner', '#status'];

        let result = inputProcessor.process();

        result.append('page', pageCounter);

        show('.load_overlay');

        fetch(filter_ajax_url, {
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
                if (append) {
                    document.querySelector('.service_info_table tbody').insertAdjacentHTML('afterend', data.html);
                } else {
                    document.querySelector('.service_info_table tbody').innerHTML = data.html;
                }

                if (data.can_load_more) {
                    show('.load_more_records', 'inline-block');
                } else {
                    hide('.load_more_records');
                }

                document.querySelector('.records_found span').textContent = data.count;
                tooltipMe();
                hide('.load_overlay');
            })
            .catch(function (err) {
                console.log(err);
            });
    }

    function ajaxify(data, url, reload = false) {

        show('.load_overlay');

        let send = new createFormData(data);

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
                if (reload) {
                    ajaxLoad();
                } else {
                    show_success.play(0);
                }
                hide('.load_overlay');
            })
            .catch(function (err) {
                console.log(err);
            });
    }

    if (typeof pagetype != 'undefined' && pagetype == 'service infos index') {

        const delay = { show: 700, hide: 0 };
        const offset = '0, 1';

        const tooltipOptions = {
            placement: 'top',
            html: true,
            delay: delay,
            offset: offset,
        }

        const tooltipElements = new Map([
            ['.admin_ico.admin_edit', 'Upravit záznam'],
            ['.admin_ico.admin_delete', 'Smazat záznam'],
            ['.admin_ico.admin_solved', 'Vyřešeno.<br>Kliknutím změníte stav na "Zatím nevyřešeno"'],
            ['.admin_ico.admin_unsolved', 'Zatím nevyřešeno.<br>Kliknutím změníte stav na "Vyřešeno"'],
        ]);

        tooltipMe = function () {
            tooltipHelper(tooltipElements, tooltipOptions);
        }

        tooltipMe();

        const selects = Array.from(document.querySelectorAll('.select_type'));

        selects.forEach((element) => {
            let selectId = element.id;
            let selectBox = new vanillaSelectBox('#' + selectId, { maxSelect: 1 });
            selectBox.disableItems(['0']);
            dataStorage.put(element, 'vanillaSelectBox', selectBox);
        }
        )

        ajaxLoad();

        on(document, 'click', '.admin_ico.admin_delete', function (element) {
            document.querySelector('.car_deletion .deletion_id').value = element.dataset.id;
            show('.car_deletion');
        })

        on('.deletion_cancel', 'click', function () {
            document.querySelector('.car_deletion .deletion_id').value = '';
            hide('.car_deletion');
        })

        on('.deletion_confirm', 'click', function () {

            let id = document.querySelector('.car_deletion .deletion_id').value;

            let data = {
                id: document.querySelector('.car_deletion .deletion_id').value
            };

            document.querySelector('.car_deletion .deletion_id').value = '';
            hide('.car_deletion');

            document.querySelector('tr[data-id="' + id + '"]').remove();

            ajaxify(data, delete_ajax_url, true);
        })

        on(document, 'click', '.admin_ico.info_status', function (element) {

            let statuses = element.closest('tr').querySelectorAll('.status_indicator');
            let oppositeStatus = element.closest('tr').querySelector('.info_status:not(#' + element.id + ')').closest('.status_indicator');

            removeClass(statuses, 'visible');
            addClass(statuses, 'invisible');

            removeClass(oppositeStatus, 'invisible');
            addClass(oppositeStatus, 'visible');

            let data = {
                id: element.dataset.id,
                status: element.id
            };

            ajaxify(data, status_ajax_url);

        })

        on('.reset_filters', 'click', function () {
            let selects = Array.from(document.querySelectorAll('.select_type'));

            selects.forEach(function (element) {
                dataStorage.get(element, 'vanillaSelectBox').setValue('0');
            })
            ajaxLoad();
        })

        on('select.select_type', 'change', function () {
            ajaxLoad();
        })

        on('.load_more_records', 'click', function () {
            ajaxLoad(true);
        })
    }

    if (typeof pagetype != 'undefined' && (pagetype == 'service info new' || pagetype == 'service info edit')) {

        const selects = Array.from(document.querySelectorAll('.select_type'));

        selects.forEach((element) => {
            let selectId = element.id;
            let selectBox = new vanillaSelectBox('#' + selectId, { maxSelect: 1 });
            selectBox.disableItems(['0']);
            dataStorage.put(element, 'vanillaSelectBox', selectBox);
        }
        )

        const onlyDigits = Array.from(document.querySelectorAll('.only_digits_limited'));

        onlyDigits.forEach((element) => {
            IMask(element, {
                mask: Number,
                scale: 0,
                signed: false,
                thousandsSeparator: '',
            })
        }
        )

        init_text_editor('#description', 'undo redo | bold italic underline strikethrough subscript superscript removeformat numlist bullist');

        on('.save', 'click', function () {

            let inputProcessor = new processInputs();
            inputProcessor.mandatories = ['#title'];
            inputProcessor.optionals = ['#car_id', '#owner', '#status', '#amount'];

            let result = inputProcessor.process();

            if (result === false) {
                return;
            }

            
            result.append('description', tinymce.get('description').getContent());
            result.append('info_id', info_id);

            show('.load_overlay');

            fetch(save_info_ajax_url, {
                method: 'POST',
                body: result,
                cache: 'no-cache',
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(response.json());
                    }
                    if (response.redirected) {
                        window.location.href = response.url;
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
    }
})