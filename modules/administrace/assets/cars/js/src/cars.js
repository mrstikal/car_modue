import { on } from '/modules/common/base-js/base-functions.js';
import { show } from '/modules/common/base-js/base-functions.js';
import { hide } from '/modules/common/base-js/base-functions.js';
import { processInputs } from '/modules/common/base-js/base-functions.js';
import { tooltipHelper } from '/modules/common/base-js/base-functions.js';
import { createFormData } from '/modules/common/base-js/base-functions.js';
import { dataStorage } from '/modules/common/base-js/base-functions.js';
import { addClass } from '/modules/common/base-js/base-functions.js';
import { removeClass } from '/modules/common/base-js/base-functions.js';
import { hasClass } from '/modules/common/base-js/base-functions.js';
import { onWithData } from '/modules/common/base-js/base-functions.js';

function ajaxify(data, url) {

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
            show_success.play(0);
            hide('.load_overlay');
        })
        .catch(function (err) {
            console.log(err);
        });
}


document.addEventListener('DOMContentLoaded', function (event) {

    if (typeof pagetype != 'undefined' && pagetype == 'cars index') {

        const delay = { show: 700, hide: 0 };
        const offset = '0, 1';

        const tooltipOptions = {
            placement: 'top',
            html: true,
            delay: delay,
            offset: offset,
        }

        const tooltipElements = new Map([
            ['.inliner.status', 'Skryje/zobrazí<br>vozidlo na webu'],
            ['.inliner.edit', 'Úprava vozidla'],
            ['.inliner.delete', 'Trvale smaže toto vozidlo'],
            ['.inliner.move', 'Tažením seřadíte vozidla pro<br>pořadí zobrazení na webu'],
            ['.inliner.active_language', 'Aktuální jazyk úprav'],
            ['.inliner.edit_translation', 'Upravit překlad vozidla'],
            ['.inliner.add_translation', 'Přidat překlad vozidla'],
        ]);

        let tooltipMe = function () {
            tooltipHelper(tooltipElements, tooltipOptions);
        }

        tooltipMe();

        Array.from(document.querySelectorAll('.records_table tr')).forEach(function (element, index) {
            element.style.width = element.offsetWidth + 'px';
        })

        new Sortable(document.querySelector('.records_table tbody'), {
            animation: 150,
            fallbackOnBody: false,
            forceFallback: true,
            handle: '.inliner.move',
            swapThreshold: 1.5,
            onUpdate: savePositions,
            direction: 'vertical',
        })

        function savePositions() {

            show('.load_overlay');

            let data = {};

            Array.from(document.querySelectorAll('.records_table tr')).forEach(function (element, index) {
                data[element.dataset.id] = index;
            })

            ajaxify(data, position_ajax_url);
        }

        on('.inliner.status', 'click', function (element) {

            let status = '';

            if (hasClass(element, 'inactive')) {
                removeClass(element, 'inactive');
                status = 'active';
            } else {
                addClass(element, 'inactive');
                status = 'draft';
            }

            let data = {};

            data['language'] = element.dataset.language;
            data['language_id'] = element.dataset.languageIid;
            data['status'] = status;

            ajaxify(data, status_ajax_url);
        })
    }

    on('.inliner.delete', 'click', function (element) {
        document.querySelector('.car_deletion').querySelector('.deletion_id').value = element.dataset.id;
        show('.car_deletion');
    })

    on('.car_deletion .deletion_cancel', 'click', function () {
        document.querySelector('.car_deletion').querySelector('.deletion_id').value = '';
        hide('.car_deletion');
    })

    on('.car_deletion .deletion_confirm', 'click', function () {

        hide('.car_deletion');

        let car_id = document.querySelector('.car_deletion .deletion_id').value;

        document.querySelector('.records_table').querySelector('tr[data-id="' + car_id + '"]').remove();

        let data = {};
        data['car_id'] = car_id;
        show('.load_overlay');

        ajaxify(data, delete_ajax_url);
    })

    if (typeof pagetype != 'undefined' && (pagetype == 'cars new' || pagetype == 'cars edit')) {

        const delay = { show: 1200, hide: 0 };
        const offset = '0, 1';

        const tooltipOptions = {
            placement: 'top',
            html: true,
            delay: delay,
            offset: offset,
        }

        const tooltipElements = new Map([
            ['.mileage_lower_limit', 'Při nájezdu menším než tato hodnota zůstává cena bez slevy za kilometry'],
            ['.mileage_upper_limit', 'Při nájezdu větším než tato hodnota již sleva neklesá'],
            ['.mileage_max_discount', 'Sleva s rostoucími kilometry stále roste.<br>Zde zadejte jaká může být nejvyšší dosažitelná sleva'],
            ['.mileage_coefficient', 'Vyšší číslo = sleva rychleji roste blízko spodního limitu kilometrů a více zpomaluje blízko horního limitu kilometrů.<br>Můžete zadat desetinné číslo'],
            ['.days_lower_limit', 'Při pronájmu kratším než tato hodnota zůstává cena bez slevy za dny'],
            ['.days_upper_limit', 'Při pronájmu delším než tato hodnota již sleva neklesá'],
            ['.days_max_discount', 'Sleva s rostoucími dny stále roste.<br>Zde zadejte jaká může být nejvyšší dosažitelná sleva'],
            ['.days_coefficient', 'Vyšší číslo = sleva rychleji roste blízko spodního limitu dnů a více zpomaluje blízko horního limitu dnů.<br>Můžete zadat desetinné číslo.'],
        ]);

        let tooltipMe = function () {
            tooltipHelper(tooltipElements, tooltipOptions);
        }

        tooltipMe();

        const onlyDigits = Array.from(document.querySelectorAll('.only_digits'));

        onlyDigits.forEach((element) => {
            IMask(element, {
                mask: Number,
                scale: 0,
                signed: false,
                thousandsSeparator: '',
            })
        }
        )

        const onlyDecimals = Array.from(document.querySelectorAll('.only_decimal_min'));

        onlyDecimals.forEach((element) => {
            IMask(element, {
                mask: Number,
                scale: 2,
                signed: false,
                thousandsSeparator: '',
                radix: '.',
                mapToRadix: [','],
                min: 0,
            })
        }
        )

        init_text_editor('#language_description', 'undo redo | bold italic underline strikethrough subscript superscript removeformat numlist bullist');

        const selects = Array.from(document.querySelectorAll('.select_type'));

        selects.forEach((element) => {
            let selectId = element.id;
            let selectBox = new vanillaSelectBox('#' + selectId, { maxSelect: 1 });
            selectBox.disableItems(['']);
            dataStorage.put(element, 'vanillaSelectBox', selectBox);
        }
        )

        autosize(document.querySelectorAll('.auto_resize'));

        var chartMileage;
        if (is_primary_language && document.querySelector('#chart_container_mileage')) {
            //mileage chart
            chartMileage = Highcharts.chart('chart_container_mileage', {
                chart: {
                    type: 'spline',
                    styledMode: true
                },
                title: {
                    text: 'Průběh ceny s rostoucími kilometry'
                },
                xAxis: {
                    title: {
                        text: 'Kilometry'
                    },
                    categories: []
                },
                legend: {
                    enabled: false
                },
                credits: {
                    enabled: false
                },
                yAxis: {
                    title: {
                        text: 'Cena za km (Kč)'
                    }
                },
                plotOptions: {
                    spline: {
                        dataLabels: {
                            enabled: true,
                            formatter: function () {
                                return Highcharts.numberFormat(this.y, 2, ',')
                            }
                        },
                        enableMouseTracking: false
                    }
                },
                series:
                    [
                        {
                            data: []
                        }
                    ]
            });

            let categories = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'];
            let data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

            chartMileage.xAxis[0].setCategories(categories);
            chartMileage.series[0].setData(data);
        }

        on('.discount_mileage_test', 'click', function () {

            let inputs = ['#standard_price', '#mileage_lower_limit', '#mileage_upper_limit', '#mileage_max_discount', '#mileage_coefficient'];

            let inputProcessor = new processInputs();
            inputProcessor.mandatories = ['#standard_price', '#mileage_lower_limit', '#mileage_upper_limit', '#mileage_max_discount', '#mileage_coefficient'];

            let result = inputProcessor.process();

            if (result === false) {
                return;
            }

            show('.load_overlay');

            fetch(mileage_discount_test_ajax_url, {
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
                    chartMileage.xAxis[0].setCategories(data[0]);
                    chartMileage.series[0].setData(data[1]);
                    hide('.load_overlay');
                })
                .catch(function (err) {
                    console.log(err);
                });
        })

        //days chart
        var chartDays;

        if (is_primary_language && document.querySelector('#chart_container_days')) {
            chartDays = Highcharts.chart('chart_container_days', {
                chart: {
                    type: 'spline',
                    styledMode: true
                },
                title: {
                    text: 'Průběh ceny s rostoucími dny'
                },
                xAxis: {
                    title: {
                        text: 'Dny'
                    },
                    categories: []
                },
                legend: {
                    enabled: false
                },
                credits: {
                    enabled: false
                },
                yAxis: {
                    title: {
                        text: 'Cena za den (Kč)'
                    }
                },
                plotOptions: {
                    spline: {
                        dataLabels: {
                            enabled: true,
                            formatter: function () {
                                return Highcharts.numberFormat(this.y, 0, ',')
                            }
                        },
                        enableMouseTracking: false
                    }
                },
                series:
                    [
                        {
                            data: []
                        }
                    ]
            });

            let categories = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'];
            let data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

            chartDays.xAxis[0].setCategories(categories);
            chartDays.series[0].setData(data);
        }

        on('.discount_days_test', 'click', function () {

            let inputProcessor = new processInputs();
            inputProcessor.mandatories = ['#standard_price', '#days_lower_limit', '#days_upper_limit', '#days_max_discount', '#days_coefficient'];

            let result = inputProcessor.process();

            if (result === false) {
                return;
            }

            show('.load_overlay');

            fetch(days_discount_test_ajax_url, {
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
                    chartDays.xAxis[0].setCategories(data[0]);
                    chartDays.series[0].setData(data[1]);
                    hide('.load_overlay');
                })
                .catch(function (err) {
                    console.log(err);
                });
        })

        //price test

        on('.discount_price_test', 'click', function () {

            let inputProcessor = new processInputs();
            inputProcessor.mandatories = [
                '#standard_price', '#test_days', '#test_mileage',
                '#days_lower_limit', '#days_upper_limit', '#days_max_discount', '#days_coefficient',
                '#mileage_lower_limit', '#mileage_upper_limit', '#mileage_max_discount', '#mileage_coefficient'
            ];

            let result = inputProcessor.process();

            if (result === false) {
                return;
            }

            fetch(price_discount_test_ajax_url, {
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
                    document.querySelector('.test_final_price span').textContent = data;
                    hide('.load_overlay');
                })
                .catch(function (err) {
                    console.log(err);
                });

        })

        on(document, 'input change', '.red_error', function (element) {
            removeClass(element, 'red_error');
            removeClass(element.closest('.js_parent').querySelector('.this_element_name'), 'red_error');
        })

        //image upload params
        var element = ".ico_drop_load";

        on(document.body, "drop dragover dragstart dragenter", function(element, event) {event.preventDefault()} );
        on(document.querySelector(element), "drop dragover dragstart dragenter", function(element, event) {event.preventDefault()});

        var drop_config =
        {
            action: 'drop',
            secondary_target: ".image_preview",
            error_class: ".ico_error",
            max_size: "10 MB",
        };

        var click_config = JSON.parse(JSON.stringify(drop_config));
        click_config.action = 'click';

        onWithData(element, "drop", upload_one_file_with_data, drop_config);

        onWithData(".hidden_file_input", "change", upload_one_file_with_data, click_config);


        //web visibility
        on('.inliner.status', 'click', function (element) {

            if (hasClass(element, 'inactive')) {
                removeClass(element, 'inactive');
            } else {
                addClass(element, 'inactive');
            }
        })

        //saving
        on('.save', 'click', function () {

            let mandatories = ['#language_name'];

            let optionals = ['#language_slogan'];

            if (is_primary_language) {
                mandatories = [
                    '#language_name',
                    '#battery_capacity', '#traction', '#spz', '#bail',
                    '#standard_price',
                    '#days_lower_limit', '#days_upper_limit', '#days_max_discount', '#days_coefficient',
                    '#mileage_lower_limit', '#mileage_upper_limit', '#mileage_max_discount', '#mileage_coefficient'
                ];
                optionals = [
                    '#language_slogan', '#manufacturer', '#current_condition', '#action_price',
                    '#bail_with_rider', '#surcharge_for_rider', '#vin', '#color', '#mileage_now',
                    '#is_performance', '#use_action_price',
                ];
            }

            if (document.querySelector('#use_action_price').checked) {
                mandatories.push('#action_price');
            }

            let inputProcessor = new processInputs();
            inputProcessor.mandatories = mandatories;
            inputProcessor.optionals = optionals;

            let result = inputProcessor.process();

            if (result === false) {
                return;
            }

            //editor
            result.append('language_description', tinymce.get('language_description').getContent());

            //web visibility
            result.append('language_status', hasClass('.inliner.status', 'inactive') ? 'draft' : 'active');

            let tire_condition = {};

            Array.from(document.querySelectorAll('input.tires')).forEach(function (element) {
                tire_condition[element.id] = element.value.trim();
            })

            result.append('tire_condition', JSON.stringify(tire_condition));

            if (is_primary_language && file_buffer != '') {
                result.append('image', file_buffer);
            }

            result.append('car_id', car_id);

            //checking form entries
            /*for (var pair of form.entries()) {
                console.log(pair[0]+ ', ' + pair[1]); 
            }*/
            show(".load_overlay");

            fetch(save_car_ajax_url, {
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
                    if (data != '') {
                        document.querySelector(".error_is").innerHTML(data);
                        show(".general_error");
                    } else {
                        show_success.play(0);
                        file_buffer = '';
                    }
                    hide('.load_overlay');
                })
                .catch(function (err) {
                    console.log(err);
                });
        })
    }

    if (typeof pagetype != 'undefined' && pagetype == 'car settings') {

        const selects = Array.from(document.querySelectorAll('.select_type'));

        selects.forEach((element) => {
            let selectId = element.id;
            let selectBox = new vanillaSelectBox('#' + selectId, { maxSelect: 1 });
            selectBox.disableItems(['']);
            dataStorage.put(element, 'vanillaSelectBox', selectBox);
        }
        )

        on('.save', 'click', function () {

            let data = {};

            Array.from(document.querySelectorAll('input, select')).forEach((element) => data[element.id] = element.value.trim())

            show('.load_overlay');

            ajaxify(data, save_car_setting_ajax_url);

        })

    }

})