import { on } from '/modules/common/base-js/base-functions.js';
import { show } from '/modules/common/base-js/base-functions.js';
import { hide } from '/modules/common/base-js/base-functions.js';
import { processInputs } from '/modules/common/base-js/base-functions.js';
import { DateHelper } from '/modules/common/base-js/base-functions.js';
import { dataStorage } from '/modules/common/base-js/base-functions.js';

document.addEventListener('DOMContentLoaded', function (event) {

    const datepickers = Array.from(document.querySelectorAll('.datetime_picker'));

    datepickers.forEach((element) => {
        let picker = new Datepick(element, {
            language: 'cs',
            autohide: true
        })
        dataStorage.put(element, 'Datepick', picker);
    }
    )

    dataStorage.get(document.querySelector('#date_from'), 'Datepick').setDate(
        new Date(defaultStart * 1000)
    )

    dataStorage.get(document.querySelector('#date_to'), 'Datepick').setDate(
        new Date(defaultEnd * 1000)
    )

    on('#date_from', 'changeDate', function (element) {
        dataStorage.get(document.querySelector('#date_to'), 'Datepick').setOptions({
            minDate: new DateHelper(dataStorage.get(document.querySelector('#date_from'), 'Datepick').getDate('mm/dd/yyyy')),
        })
        var evt = new Event('input', {
            bubbles: true,
            defaultPrevented: true
        });
        element.dispatchEvent(evt);
    })

    on('#date_to', 'changeDate', function (element) {
        dataStorage.get(document.querySelector('#date_from'), 'Datepick').setOptions({
            maxDate: new DateHelper(dataStorage.get(document.querySelector('#date_to'), 'Datepick').getDate('mm/dd/yyyy')),
        })
        var evt = new Event('input', {
            bubbles: true,
            defaultPrevented: true
        });
        element.dispatchEvent(evt);
    })

    function ajaxLoad() {

        show('.load_overlay');

        let processor = new processInputs();

        processor.optionals = ['#date_from', '#date_to'];

        let send = processor.process();

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
                document.querySelector('.cashbook_table tbody').innerHTML = data.tableRows;
                document.querySelector('.invoices_found span').textContent = data.count;
                document.querySelector('.total_sum span').textContent = data.totalSum;
                hide('.load_overlay');
            })
            .catch(function (err) {
                console.log(err);
            });
    }

    ajaxLoad();

    on('.pickers_run', 'click', ajaxLoad);

})