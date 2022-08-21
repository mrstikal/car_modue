import { on } from '/modules/common/base-js/base-functions.js';
import { show } from '/modules/common/base-js/base-functions.js';
import { hide } from '/modules/common/base-js/base-functions.js';
import { processInputs } from '/modules/common/base-js/base-functions.js';
import { DateHelper } from '/modules/common/base-js/base-functions.js';
import { tooltipHelper } from '/modules/common/base-js/base-functions.js';
import { createFormData } from '/modules/common/base-js/base-functions.js';
import { dataStorage } from '/modules/common/base-js/base-functions.js';

let pageCounter = 1;
let tooltipMe;

function calculatePrice(showErrors, showOverlay) {

    const inputs = ['#car_id', '#date_from', '#date_to', '#mileage', '#use_rider', '#lease_date_from', '#lease_date_to'];

    let processor = new processInputs();

    processor.mandatories = inputs;
    processor.throwErrors = showErrors;

    let result = processor.process();

    if (result === false) {
        return;
    }

    if (showOverlay) {
        show('.load_overlay');
    }

    fetch(calculate_price_ajax_url, {
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
            document.querySelector('#price_visible').value = data.formatedPrice;
            document.querySelector('#price').value = data.roundePrice;
        })
        .catch(function (err) {
            console.log(err);
        });
}

function getBailValue() {

    const inputs = ['#car_id', '#use_rider'];

    let processor = new processInputs();

    processor.mandatories = inputs;

    let result = processor.process();

    show('.load_overlay');

    fetch(bail_value_ajax_url, {
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
            document.querySelector('.bail_value span').textContent = data.formated_price;
        })
        .catch(function (err) {
            console.log(err);
        });
}

function saveBooking(redirect = false) {

    const mandatories = ['#car_id', '#date_from', '#date_to', '#mileage', '#email',];
    const optionals = ['#use_rider', '#name', '#phone', '#phone', '#status', '#note', '#price'];

    let processor = new processInputs();
    processor.mandatories = mandatories;
    processor.optionals = optionals;

    let result = processor.process();

    if (result === false) {
        return;
    }

    result.append('id', id);

    result.append('redirect', Number(redirect));

    show('.load_overlay');

    fetch(save_booking_ajax_url, {
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
            if (!redirect) {
                hide('.load_overlay');
                show_success.play(0);
            }
        })
        .catch(function (err) {
            console.log(err);
        });
}

function ajaxLoad(data, append = false) {

    let send = new createFormData(data);

    if (append) {
        pageCounter++;
    } else {
        pageCounter = 1;
    }

    show('.load_overlay');

    fetch(load_ajax_url, {
        method: 'POST',
        body: send,
        cache: 'no-cache',
    })
        .then(response => {
            if (!response.ok) {
                console.log( response.json());
                throw new Error('Network response error');
            }
            return response.json();
        })
        .then(data => {
            if (append) {
                document.querySelector('.booking_table tbody').insertAdjacentHTML('afterend', data.html);
            } else {
                document.querySelector('.booking_table tbody').innerHTML = data.html;
            }

            if (data.can_load_more) {
                show('.load_more_records', 'inline-block');
            } else {
                hide('.load_more_records');
            }

            hide('.load_overlay');
            tooltipMe();
        })
        .catch(function (err) {
            console.log(err);
        });
}

document.addEventListener('DOMContentLoaded', function (event) {

    if (typeof pagetype != 'undefined' && pagetype == 'bookings index') {

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
            ['.admin_ico.admin_order_exists', 'Objednávku nelze vytvořit.<br>Objednávka již existuje nebo rezervace byla stornována'],
            ['.admin_ico.admin_add_order', 'Vytvořit objednávku z této rezevace'],
            ['.admin_ico.admin_decline', 'Stornovat rezervaci'],
            ['.admin_ico.admin_refresh', 'Obnovit stornovanou rezervaci'],
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

        const datepickers = Array.from(document.querySelectorAll('.datetime_picker'));


        datepickers.forEach((element) => {
            let picker = new Datepick(element, {
                language: 'cs',
                autohide: true
            })
            dataStorage.put(element, 'Datepick', picker);
        }
        )


        ajaxLoad(
            {
                filters: {},
                page: 1
            },
            false
        );

        on('.reset_filters', 'click', function (element) {

            let parent = element.closest('.data_processors_content');

            let inputs = Array.from(parent.querySelectorAll('input, select'));

            inputs.forEach(function (element) {
                element.value = '';
                element.dispatchEvent(new Event('change'));
            });

            let selects = Array.from(parent.querySelectorAll('.select_type'));

            selects.forEach(function (element) {
                dataStorage.get(element, 'vanillaSelectBox').setValue('0');
            })

            document.querySelector('.use_filters').dispatchEvent(new Event('click'));

        })

        on('.dpc, .close_dpc_group', 'click', function (element) {
            if (element.classList.contains('opened')) {
                document.querySelector('.dpc[data-type="' + element.dataset.type + '"]').classList.remove('opened');
                document.querySelector('.close_dpc_group[data-type="' + element.dataset.type + '"]').classList.remove('opened');
                let processorContent = document.querySelector('.data_processors_content[data-type="' + element.dataset.type + '"]');
                TweenMax.to(processorContent, 0.5, { height: 0 });
            } else {
                document.querySelector('.dpc[data-type="' + element.dataset.type + '"]').classList.add('opened');
                document.querySelector('.close_dpc_group[data-type="' + element.dataset.type + '"]').classList.add('opened');
                let processorContent = document.querySelector('.data_processors_content[data-type="' + element.dataset.type + '"]');
                TweenMax.set(processorContent, { height: 'auto', display: 'block' });
                TweenMax.from(processorContent, 0.5, { height: 0 });
            }
        });



        on('.data_processors_content input, .data_processors_content select', 'input change', function (element) {
            let groupChanged = false;
            let parent = element.closest('.data_processors_content');

            let inputs = Array.from(parent.querySelectorAll('input, select'));

            inputs.forEach(function (element) {
                if (element.value && element.value.trim() != '' && element.value != 0) {
                    groupChanged = true;
                }
            });

            if (groupChanged) {
                document.querySelector('.dpc[data-type="' + parent.dataset.type + '"]').classList.add('is_activated');
            } else {
                document.querySelector('.dpc[data-type="' + parent.dataset.type + '"]').classList.remove('is_activated');
            }
        })

        on('.use_filters', 'click', function (element) {

            let filters = {};
            let inputs = Array.from(document.querySelectorAll('.data_processors input, .data_processors select'));

            inputs.forEach(function (element) {

                if (element.value && element.value.trim() != '' && element.value != 0) {

                    let val = element.value.trim();

                    if (dataStorage.has(element, 'Datepick')) {

                        let date = new Date((dataStorage.get(element, 'Datepick').getDate('mm/dd/yyyy')));

                        if (element.classList.contains('from')) {
                            date.setHours(0, 0, 0, 0);
                            val = Math.ceil(date.getTime() / 1000);
                        } else {
                            date.setHours(23, 59, 59, 999);
                            val = Math.floor(date.getTime() / 1000);
                        }
                    }

                    filters[element.id] = {
                        value: val,
                        type: element.dataset.filterType,
                        operator: element.dataset.operator
                    }
                }
            })

            ajaxLoad(
                {
                    filters: filters,
                    page: 1
                },
                false
            );

        })

        on('.load_more_records', 'click', function () {
            ajaxLoad(
                {
                    filters: {},
                    page: pageCounter
                },
                true
            );
        })

        on(document, 'click', '.admin_decline', function (element) {

            let data = {
                id: element.dataset.id,
                new_status: 'declined'
            };

            let send = new createFormData(data);

            show('.load_overlay');

            fetch(status_ajax_url, {
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
                    document.querySelector('.use_filters').dispatchEvent(new Event('click'));
                })
                .catch(function (err) {
                    console.log(err);
                });
        })

        on(document, 'click', '.admin_refresh', function (element) {

            let data = {
                id: element.dataset.id,
                new_status: 'active'
            };

            let send = new createFormData(data);

            show('.load_overlay');

            fetch(status_ajax_url, {
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
                    document.querySelector('.use_filters').dispatchEvent(new Event('click'));
                })
                .catch(function (err) {
                    console.log(err);
                });
        })
    }

    if (typeof pagetype != 'undefined' && (pagetype == 'booking new' || pagetype == 'booking edit')) {

        getBailValue();

        const selects = ['#car_id', '#use_rider', '#status'];

        selects.forEach((value) => new vanillaSelectBox(value, {
            maxSelect: 1
        }))

        const datepickers = Array.from(document.querySelectorAll('.datetime_picker'));


        datepickers.forEach((element) => {
            let picker = new Datepick(element, {
                language: 'cs',
                autohide: true
            })
            dataStorage.put(element, 'Datepick', picker);
        }
        )

        on('#date_from', 'changeDate', function (element) {
            dataStorage.get(document.querySelector('#date_to'), 'Datepick').setOptions({
                minDate: new DateHelper(dataStorage.get(document.querySelector('#date_from'), 'Datepick').getDate('mm/dd/yyyy')).addDays(1),
            })
            var evt = new Event('input', {
                bubbles: true,
                defaultPrevented: true
            });
            element.dispatchEvent(evt);
        })

        on('#date_to', 'changeDate', function (element) {
            dataStorage.get(document.querySelector('#date_from'), 'Datepick').setOptions({
                maxDate: new DateHelper(dataStorage.get(document.querySelector('#date_to'), 'Datepick').getDate('mm/dd/yyyy')).addDays(-1),
            })
            var evt = new Event('input', {
                bubbles: true,
                defaultPrevented: true
            });
            element.dispatchEvent(evt);
        })

        on('#use_rider, #car_id', 'change', function () {
            getBailValue();
            if (typeof pagetype != 'undefined' && pagetype == 'booking new') {
                calculatePrice(false, true);
            } else {
                calculatePrice(true, true);
            }
        })

        on('.calculate_price', 'click', function () {
            calculatePrice(true, true);
        })

        on('#mileage, #date_from, #date_to', 'changeDate input', function () {

            if (typeof pagetype != 'undefined' && pagetype == 'booking new') {
                calculatePrice(false, true);
            } else {
                calculatePrice(true, true);
            }
        })

        on(document, 'input change', '.red_error', function (element) {
            element.classList.remove('red_error');
            element.closest('.js_parent').querySelector('.this_element_name').classList.remove('red_error');
        })

        on('.create_order', 'click', function () {
            saveBooking(true);
        })

        on('.save_booking', 'click', function () {
            saveBooking(false);
        })
    }
})