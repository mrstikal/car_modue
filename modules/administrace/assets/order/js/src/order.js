import { on } from '/modules/common/base-js/base-functions.js';
import { show } from '/modules/common/base-js/base-functions.js';
import { hide } from '/modules/common/base-js/base-functions.js';
import { processInputs } from '/modules/common/base-js/base-functions.js';
import { tooltipHelper } from '/modules/common/base-js/base-functions.js';
import { createFormData } from '/modules/common/base-js/base-functions.js';
import { dataStorage } from '/modules/common/base-js/base-functions.js';
import { addClass } from '/modules/common/base-js/base-functions.js';
import { removeClass } from '/modules/common/base-js/base-functions.js';

let elementClicked;

function setDueAt(parent) {

    let selectorVal = parent.querySelector('.payment_method').value;
    let sourceField = parent.querySelector('.issue_date');
    let targetField = parent.querySelector('.due_at');

    let val = 0;

    val = new Date(dataStorage.get(sourceField, 'Datepick').getDate('mm/dd/yyyy'));
    val.setHours(0, 0, 0, 0);

    if (selectorVal == 'transfer') {
        val.setDate(val.getDate() + 7);
    }

    dataStorage.get(targetField, 'Datepick').setDate(val);
}

function setSupplyDate(parent) {

    let sourceField = parent.querySelector('.issue_date');
    let targetField = parent.querySelector('.supply_date');

    let val = 0;

    val = new Date(dataStorage.get(sourceField, 'Datepick').getDate('mm/dd/yyyy'));
    val.setHours(0, 0, 0, 0);

    dataStorage.get(targetField, 'Datepick').setDate(val);
}

function setCashRegisterNumbers() {

    if (!document.querySelector('#create_bail_cash_register').checked && !document.querySelector('#create_cash_register').checked) {

        document.querySelector('#cash_register_rent_actual_prefix').value = '';
        document.querySelector('#cash_register_rent_actual_number').value = '';

        document.querySelector('#cash_register_bail_actual_prefix').value = '';
        document.querySelector('#cash_register_bail_actual_number').value = '';

        addClass('.generate_rent_cash_register_novat', 'inactive');
        addClass('.generate_bail_cash_register_novat', 'inactive');

    }

    if (document.querySelector('#create_bail_cash_register').checked && !document.querySelector('#create_cash_register').checked) {

        document.querySelector('#cash_register_rent_actual_prefix').value = '';
        document.querySelector('#cash_register_rent_actual_number').value = '';

        let val = 0;

        val = parseInt(cashRegisterInits.next_number);
        val = String(val).padStart(5, '0');
        document.querySelector('#cash_register_bail_actual_number').value = val;
        document.querySelector('#cash_register_bail_actual_prefix').value = cashRegisterInits.prefix;

        addClass('.generate_rent_cash_register_novat', 'inactive');
        removeClass('.generate_bail_cash_register_novat', 'inactive');

    }

    if (!document.querySelector('#create_bail_cash_register').checked && document.querySelector('#create_cash_register').checked) {

        let val = 0;

        val = parseInt(cashRegisterInits.next_number) + 1;
        val = String(val).padStart(5, '0');
        document.querySelector('#cash_register_rent_actual_number').value = val;
        val = cashRegisterInits.prefix + val;
        document.querySelector('#cash_register_rent_actual_prefix').value = cashRegisterInits.prefix;

        removeClass('.generate_rent_cash_register_novat', 'inactive');
        addClass('.generate_bail_cash_register_novat', 'inactive');

    }

    if (document.querySelector('#create_bail_cash_register').checked && document.querySelector('#create_cash_register').checked) {

        let val = 0;

        val = parseInt(cashRegisterInits.next_number) + 1;
        val = String(val).padStart(5, '0');
        document.querySelector('#cash_register_bail_actual_number').value = val;
        document.querySelector('#cash_register_bail_actual_prefix').value = cashRegisterInits.prefix;

        val = parseInt(cashRegisterInits.next_number) + 1;
        val = String(val).padStart(5, '0');
        document.querySelector('#cash_register_rent_actual_number').value = val;
        val = cashRegisterInits.prefix + val;
        document.querySelector('#cash_register_rent_actual_prefix').value = cashRegisterInits.prefix;

        removeClass('.generate_rent_cash_register_novat', 'inactive');
        removeClass('.generate_bail_cash_register_novat', 'inactive');

    }
}

function setInvoiceNumbers() {

    if (!document.querySelector('#create_bail_invoice').checked && !document.querySelector('#create_invoice').checked) {

        document.querySelector('#invoice_bail_actual_prefix').value = '';
        document.querySelector('#invoice_bail_actual_number').value = '';

        document.querySelector('#invoice_rent_actual_prefix').value = '';
        document.querySelector('#invoice_rent_actual_number').value = '';

        addClass('.generate_rent_invoice_novat', 'inactive');
        addClass('.generate_bail_invoice_novat', 'inactive');

    }

    if (document.querySelector('#create_bail_invoice').checked && !document.querySelector('#create_invoice').checked) {

        document.querySelector('#invoice_rent_actual_prefix').value = '';
        document.querySelector('#invoice_rent_actual_number').value = '';

        let val = 0;

        val = parseInt(invoiceInits.next_number) + 1;
        val = String(val).padStart(5, '0');
        document.querySelector('#invoice_bail_actual_number').value = val;
        document.querySelector('#invoice_bail_actual_prefix').value = invoiceInits.prefix;

        addClass('.generate_rent_invoice_novat', 'inactive');
        removeClass('.generate_bail_invoice_novat', 'inactive');

    }

    if (!document.querySelector('#create_bail_invoice').checked && document.querySelector('#create_invoice').checked) {

        document.querySelector('#invoice_bail_actual_prefix').value = '';
        document.querySelector('#invoice_bail_actual_number').value = '';

        let val = 0;

        val = parseInt(invoiceInits.next_number) + 1;
        val = String(val).padStart(5, '0');
        document.querySelector('#invoice_rent_actual_number').value = val;
        document.querySelector('#invoice_rent_actual_prefix').value = invoiceInits.prefix;

        removeClass('.generate_rent_invoice_novat', 'inactive');
        addClass('.generate_bail_invoice_novat', 'inactive');

    }

    if (document.querySelector('#create_bail_invoice').checked && document.querySelector('#create_invoice').checked) {

        let val = 0;

        val = parseInt(invoiceInits.next_number) + 1;
        val = String(val).padStart(5, '0');
        document.querySelector('#invoice_bail_actual_number').value = val;
        document.querySelector('#invoice_bail_actual_prefix').value = invoiceInits.prefix;

        val = parseInt(invoiceInits.next_number) + 2;
        val = String(val).padStart(5, '0');
        document.querySelector('#invoice_rent_actual_number').value = val;
        document.querySelector('#invoice_bail_actual_prefix').value = invoiceInits.prefix;

        removeClass('.generate_rent_invoice_novat', 'inactive');
        removeClass('.generate_bail_invoice_novat', 'inactive');

    }
}

function setVariableSymbols() {

    let val = 0;

    val = document.querySelector('#invoice_rent_actual_number').value;
    document.querySelector('#invoice_rent_variable_symbol').value = val;

    val = document.querySelector('#invoice_bail_actual_number').value;
    document.querySelector('#invoice_bail_variable_symbol').value = val;

    /* original approach, will not be used for now
    if ($('#create_bail_invoice').not(':checked')) {

        $('#invoice_bail_variable_symbol').val('');

        val = parseInt(bookingId) + parseInt(variableSymbolBase);
        val = val.toString() + '1';
        $('#invoice_rent_variable_symbol').val(val);
    }

    if ($('#create_bail_invoice').is(':checked')) {

        val = parseInt(bookingId) + parseInt(variableSymbolBase);
        val = val.toString() + '0';
        $('#invoice_bail_variable_symbol').val(val);

        val = parseInt(bookingId) + parseInt(variableSymbolBase);
        val = val.toString() + '1';
        $('#invoice_rent_variable_symbol').val(val);
    }*/
}

class PageFunctions {
    constructor() {
        this.generatePapers = function () {
            if (typeof (elementClicked) != 'undefined' && elementClicked != null) {
                document.querySelector('#pattern').value = elementClicked.dataset.pattern;
                document.querySelector('#orderId').value = elementClicked.dataset.orderId;
                document.querySelector('#type').value = elementClicked.dataset.type;
                document.querySelector('.inv').submit();
            }
        };
    }
}

document.addEventListener('DOMContentLoaded', function (event) {

    if (typeof pagetype != 'undefined' && (pagetype == 'order new' || pagetype == 'order edit')) {

        autosize(document.querySelectorAll('.auto_resize'));

        const selects = Array.from(document.querySelectorAll('.select_type'));

        selects.forEach((element) => {
            let selectId = element.id;
            let selectBox = new vanillaSelectBox('#' + selectId, { maxSelect: 1 });
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

        const mikros = Array.from(document.querySelectorAll('.mikro'));

        mikros.forEach((element) => {
            IMask(element, {
                mask: /^[\d:]+$/
            })
        }
        )

        on('#invoice_rent_payment_method', 'change', function (element) {
            if (element.value == 'cash') {
                document.querySelector('#create_cash_register').checked = true;
            }
        });

        document.querySelector('#invoice_rent_payment_method').dispatchEvent(new Event('change'));

        on('#invoice_bail_payment_method', 'change', function (element) {
            if (element.value == 'cash') {
                document.querySelector('#create_bail_cash_register').checked = true;
            }
        });

        document.querySelector('#invoice_bail_payment_method').dispatchEvent(new Event('change'));

        on('#is_company', 'change', function (element) {

            if (element.value == 0) {
                addClass('.for_business', 'hidden');
                removeClass('.for_person', 'hidden');
            } else {
                addClass('.for_person', 'hidden');
                removeClass('.for_business', 'hidden');
            }

        })

        document.querySelector('#is_company').dispatchEvent(new Event('change'));

        on('#different_bill_address', 'change', function (element) {
            if (element.checked) {
                removeClass('.for_different_bill_address', 'hidden');
            } else {
                addClass('.for_different_bill_address', 'hidden');
            }
        })

        on('#create_bail_invoice', 'change', function (element) {
            if (element.checked) {
                removeClass('.for_bail_invoice', 'hidden');
                removeClass('#invoice_novat_bail', 'inactive');
            } else {
                addClass('.for_bail_invoice', 'hidden');
                addClass('#invoice_novat_bail', 'inactive');
            }
            setCashRegisterNumbers();
            setInvoiceNumbers();
            setVariableSymbols();
            if (pagetype == 'order edit') saveOrder(true);
        })

        on('#create_bail_cash_register', 'change', function (element) {
            if (element.checked) {
                removeClass('.for_bail_cash', 'hidden');
                removeClass('#cash_register_novat_bail', 'inactive');
            } else {
                addClass('.for_bail_cash', 'hidden');
                addClass('#cash_register_novat_bail', 'inactive');
            }
            setCashRegisterNumbers();
            setInvoiceNumbers();
            setVariableSymbols();
            if (pagetype == 'order edit') saveOrder(true);
        })

        on('#create_invoice', 'change', function (element) {
            if (element.checked) {
                removeClass('.for_rent_invoice', 'hidden');
                removeClass('#invoice_novat_rent', 'inactive');
            } else {
                addClass('.for_rent_invoice', 'hidden');
                addClass('#invoice_novat_rent', 'inactive');
            }
            setCashRegisterNumbers();
            setInvoiceNumbers();
            setVariableSymbols();
            if (pagetype == 'order edit') saveOrder(true);
        })

        on('#create_cash_register', 'change', function (element) {

            if (element.checked) {
                removeClass('.for_rent_cash', 'hidden');
                removeClass('#cash_register_novat_rent', 'inactive');
            } else {
                addClass('.for_rent_cash', 'hidden');
                addClass('#cash_register_novat_rent', 'inactive');
            }
            setCashRegisterNumbers();
            setInvoiceNumbers();
            setVariableSymbols();
            if (pagetype == 'order edit') saveOrder(true);
        })

        on('#invoice_rent_actual_number, #invoice_bail_actual_number', 'input', function () {
            setVariableSymbols();
        })

        on('.payment_method', 'input change', function (element) {
            setDueAt(element.closest('.admin_block'));
        })

        on('.issue_date', 'input', function (element) {
            setDueAt(element.closest('.admin_block'));
            setSupplyDate(element.closest('.admin_block'));
        })

        on('#price, #contractual_fine', 'input change', function () {
            let price = parseInt(document.querySelector('#price').value);
            if (Number.isNaN(price)) price = 0;
            let fine = parseInt(document.querySelector('#contractual_fine').value);
            if (Number.isNaN(fine)) fine = 0;
            document.querySelector('#invoice_price').value = price + fine;
        })

        document.querySelector('#price').dispatchEvent(new Event('change'));

        on(document, 'input change', '.red_error', function (element) {
            element.classList.remove('red_error');
            element.closest('.js_parent').querySelector('.this_element_name').classList.remove('red_error');
        })

        function saveOrder(endAfterSave, triggerFunction) {

            let mandatories = [
                "#name", "#phone", "#email", "#birth_number", "#identity_card_number",
                '#lease_date_from', '#lease_date_to', '#mileage', '#price',
                '#vehicle_handover_date', '#vehicle_handover_place', '#vehicle_return_date', '#vehicle_return_place', '#vehicle_handover_time', '#vehicle_return_time',
                '#bail_value'
            ];

            if (document.querySelector('#is_company').value == 0) {
                mandatories = mandatories.concat(['#birth_date', '#street', '#zip', '#town']);
            }

            if (document.querySelector('#is_company').value == 1) {
                mandatories = mandatories.concat(['#permanent_residence', '#company_name', '#company_street', '#company_zip', '#company_town', '#ico']);
            }

            if (document.querySelector('#create_bail_cash_register').checked) {
                mandatories = mandatories.concat(['#cash_register_bail_actual_prefix', '#cash_register_bail_actual_number', '#cash_register_bail_payment_date', '#cash_register_bail_full_number']);
            }

            if (document.querySelector('#create_bail_invoice').checked) {
                mandatories = mandatories.concat(['#invoice_bail_actual_prefix', '#invoice_bail_actual_number', '#invoice_bail_issue_date', '#invoice_bail_supply_date', '#invoice_bail_due_at', '#invoice_bail_variable_symbol']);
            }

            if (document.querySelector('#create_cash_register').checked) {
                mandatories = mandatories.concat(['#cash_register_rent_actual_prefix', '#cash_register_rent_actual_number', '#cash_register_rent_payment_date']);
            }

            if (document.querySelector('#create_invoice').checked) {
                mandatories = mandatories.concat(['#invoice_rent_actual_prefix', '#invoice_rent_actual_number', '#invoice_rent_issue_date', '#invoice_rent_supply_date', '#invoice_rent_due_at', '#invoice_rent_variable_symbol']);
            }

            if (document.querySelector('#different_bill_address').checked) {
                mandatories = mandatories.concat(['#billing_name', '#billing_street', '#billing_zip', '#billing_town']);
            }

            let optionals = [
                '#is_company', '#dic', '#contractual_fine', '#use_rider',
                '#invoice_bail_payment_method', '#create_bail_invoice', '#create_bail_cash_register',
                '#invoice_rent_payment_method', '#create_invoice', '#create_cash_register',
                '#operator_id', '#car_id', '#contractual_fine', '#note', '#status',
                '#different_bill_address', '#billing_ico', '#billing_dic'
            ];

            let processor = new processInputs();

            processor.mandatories = mandatories;
            processor.optionals = optionals;

            let result = processor.process();

            if (result === false) {
                return;
            }

            result.append('invoice_base_number', invoiceInits.number);
            result.append('invoice_base_prefix', invoiceInits.prefix);

            result.append('cash_register_base_number', cashRegisterInits.number);
            result.append('cash_register_base_prefix', cashRegisterInits.prefix);

            result.append('cash_register_rent_full_number', '' + document.querySelector('#cash_register_rent_actual_prefix').value.trim() + document.querySelector('#cash_register_rent_actual_number').value.trim());
            result.append('cash_register_bail_full_number', '' + document.querySelector('#cash_register_bail_actual_prefix').value.trim() + document.querySelector('#cash_register_bail_actual_number').value.trim());

            result.append('invoice_rent_full_number', '' + document.querySelector('#invoice_rent_actual_prefix').value.trim() + document.querySelector('#invoice_rent_actual_number').value.trim());
            result.append('invoice_bail_full_number', '' + document.querySelector('#invoice_bail_actual_prefix').value.trim() + document.querySelector('#invoice_bail_actual_number').value.trim());

            result.append('booking_id', bookingId);

            result.append('id', id);

            show('.load_overlay');

            fetch(save_order_ajax_url, {
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
                    if (endAfterSave) {
                        show_success.play(0);
                    } else {
                        show('.download_overlay');
                        let pf = new PageFunctions();
                        pf['generatePapers']();
                    }

                    hide('.load_overlay');
                })
                .catch(function (err) {
                    console.log(err);
                });
        }

        elementClicked = '';

        on('.generate_paper', 'click', function (element) {
            elementClicked = element;
            saveOrder(false, 'generatePapers');
        })

        on('.save', 'click', function () {
            saveOrder(true, '');
        })

        on('.download_overlay_button', 'click', function () {
            hide('.download_overlay');
        })
    }

    if (typeof pagetype != 'undefined' && pagetype == 'order places') {

        on('.save', 'click', function () {

            let data = new FormData;

            let inputs = Array.from(document.querySelectorAll('input'));

            inputs.forEach((element) => {
                data.append(element.id, element.value.trim())
            });

            show('.load_overlay');

            fetch(save_places_ajax_url, {
                method: 'POST',
                body: data,
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
    }

    if (typeof pagetype != 'undefined' && pagetype == 'order index') {

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

                    document.querySelector('.records_found span').textContent = data.count;
                    tooltipMe();
                    hide('.load_overlay');
                })
                .catch(function (err) {
                    console.log(err);
                });
        }

        let pageCounter = 1;

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
            ['.admin_ico.admin_cancel', 'Stornovat objednávku'],
            ['.admin_ico.admin_finish', 'Ukončit vyřízenou objednávku'],
        ]);

        let tooltipMe = function () {
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
            true
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
                TweenMax.set(processorContent, { height: 'auto' });
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

        on(document, 'click', '.admin_cancel', function (element) {

            let data = {
                id: element.dataset.id,
                new_status: 'canceled'
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

        on(document, 'click', '.admin_finish', function (element) {

            let data = {
                id: element.dataset.id,
                new_status: 'canceled'
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

    if (typeof pagetype != 'undefined' && pagetype == 'order numbering') {

        const prefixes = Array.from(document.querySelectorAll('.prefix'));

        prefixes.forEach((element) => {
            IMask(element, {
                mask: /^[\dA-Za-z0-9]{0,10}$/
            })
        }
        )

        const onlyDigits = Array.from(document.querySelectorAll('.only_digits_limited'));

        onlyDigits.forEach((element) => {
            IMask(element, {
                mask: Number,
                scale: 0,
                signed: false,
                thousandsSeparator: '',
                max: 99999
            })
        }
        )

        let invoiceNumberingChanged = false;
        let cashRegisterNumberingChanged = false;

        const oldInvoicePrefix = document.querySelector('#invoice_numbering_prefix').value;
        const oldInvoiceNumber = document.querySelector('#invoice_numbering_number').value;

        const oldCashRegisterPrefix = document.querySelector('#cash_register_numbering_prefix').value;
        const oldCashRegisterNumber = document.querySelector('#cash_register_numbering_number').value;

        on('#invoice_numbering_prefix, #invoice_numbering_number', 'input', function () {
            let val = document.querySelector('#invoice_numbering_prefix').value + document.querySelector('#invoice_numbering_number').value.padStart(5, '0');
            document.querySelector('.invoice_numbering_example span').textContent = val;
        })

        on('#cash_register_numbering_prefix, #cash_register_numbering_number', 'input', function () {
            let val = document.querySelector('#cash_register_numbering_prefix').value + document.querySelector('#cash_register_numbering_number').value.padStart(5, '0');
            document.querySelector('.cash_register_numbering_example span').textContent = val;
        })

        on('.save', 'click', function () {

            invoiceNumberingChanged = document.querySelector('#invoice_numbering_prefix').value != oldInvoicePrefix || document.querySelector('#invoice_numbering_number').value != oldInvoiceNumber;
            cashRegisterNumberingChanged = document.querySelector('#cash_register_numbering_prefix').value != oldCashRegisterPrefix || document.querySelector('#cash_register_numbering_number').value != oldCashRegisterNumber;

            let data = {
                invoiceNumberingChanged: Number(invoiceNumberingChanged),
                cashRegisterNumberingChanged: Number(cashRegisterNumberingChanged),

                invoice_numbering_prefix: document.querySelector('#invoice_numbering_prefix').value,
                invoice_numbering_number: document.querySelector('#invoice_numbering_number').value,

                cash_register_numbering_prefix: document.querySelector('#cash_register_numbering_prefix').value,
                cash_register_numbering_number: document.querySelector('#cash_register_numbering_number').value,
            };

            let send = new createFormData(data);

            show('.load_overlay');

            fetch(save_numberings_ajax_url, {
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
        })
    }

})