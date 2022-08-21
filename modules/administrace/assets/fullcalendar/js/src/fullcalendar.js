import { on } from '/modules/common/base-js/base-functions.js';
import { dataStorage } from '/modules/common/base-js/base-functions.js';
import { show } from '/modules/common/base-js/base-functions.js';
import { hide } from '/modules/common/base-js/base-functions.js';

document.addEventListener('DOMContentLoaded', function (event) {

    const selects = Array.from(document.querySelectorAll('.select_type'));

    selects.forEach((element) => {
        let selectId = element.id;
        let selectBox = new vanillaSelectBox('#' + selectId, { maxSelect: 1 });
        dataStorage.put(element, 'vanillaSelectBox', selectBox);
    }
    )

    var calendar = new FullCalendar.Calendar(document.querySelector('.callendar_wrapper'), {
        height: 'auto',
        contentHeight: 10,
        headerToolbar: {
            start: 'title',
            center: 'dayGridMonth,dayGridWeek',
            end: 'today prev,next'
        },
        initialView: 'dayGridMonth',
        locale: 'cs',
        eventSources: [
            {
                url: bookings_ajax_url,
                method: 'POST',
                extraParams: function () {
                    return {
                        car_id: $('#select_car').val(),
                        record_type: $('#select_type').val(),
                    }
                },
                color: '#4d8439',
                textColor: 'white',
            },
            {
                url: orders_ajax_url,
                method: 'POST',
                extraParams: function () {
                    return {
                        car_id: $('#select_car').val(),
                        record_type: $('#select_type').val(),
                    }
                },
                color: '#2e56bb',
                textColor: 'white',
            }
        ],
        eventContent: function (eventInfo) {
            return { html: eventInfo.event.extendedProps.customHtml }
        },
        eventDidMount: function (info) {
            var tooltip = new Tooltip(info.el, {
                title: info.event.extendedProps.tooltipContent,
                placement: 'top-start',
                trigger: 'hover',
                container: 'body',
                html: true,
            });
        },
        loading: function( isLoading, view ) {
            if(isLoading) {
                show('.load_overlay');
            } else {
                hide('.load_overlay');
            }
        }
    });

    calendar.render();


    on('#select_car, #select_type', 'change', function () {
        calendar.refetchEvents();
    })

});