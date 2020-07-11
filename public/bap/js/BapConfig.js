var BAP_Config = {

    init: function () {

        /**
         * Config Datatable daterange filters
         * @type {{}}
         */
        window.DATATABLE_DATERANGE_CONFIG = {
            showWeekNumbers: true,
            autoUpdateInput: false,
            showDropdowns: true,
            timePicker: false,
            opens: 'left',
            applyClass: "btn-primary",
            locale: {
                format: window.APPLICATION_USER_DATE_FORMAT,
                cancelLabel: $.i18n._('clear'),
                applyLabel:  $.i18n._('apply'),
                weekLabel: 'W',
                firstDay: 1,
                daysOfWeek: [
                    $.i18n._('calendar_su'),
                    $.i18n._('calendar_mo'),
                    $.i18n._('calendar_tu'),
                    $.i18n._('calendar_we'),
                    $.i18n._('calendar_th'),
                    $.i18n._('calendar_fr'),
                    $.i18n._('calendar_sa')
                ],
                monthNames: [
                    $.i18n._('january'),
                    $.i18n._('february'),
                    $.i18n._('march'),
                    $.i18n._('april'),
                    $.i18n._('may'),
                    $.i18n._('june'),
                    $.i18n._('july'),
                    $.i18n._('august'),
                    $.i18n._('september'),
                    $.i18n._('october'),
                    $.i18n._('november'),
                    $.i18n._('december')
                ],

            }
        }

        window.DATATABLE_SINGLEDATE_CONFIG = {
            showWeekNumbers: true,
            autoUpdateInput: false,
            showDropdowns: true,
            opens: 'left',
            applyClass: "btn-primary",
            singleDatePicker: true,
            timePicker: false,
            timePicker24Hour: window.APPLICATION_USER_TIME_FORMAT_24,
            locale: {
                format: window.APPLICATION_USER_DATE_FORMAT,
                cancelLabel: $.i18n._('clear'),
                applyLabel:  $.i18n._('apply'),
                weekLabel: 'W',
                firstDay: 1,
                daysOfWeek: [
                    $.i18n._('calendar_su'),
                    $.i18n._('calendar_mo'),
                    $.i18n._('calendar_tu'),
                    $.i18n._('calendar_we'),
                    $.i18n._('calendar_th'),
                    $.i18n._('calendar_fr'),
                    $.i18n._('calendar_sa')
                ],
                monthNames: [
                    $.i18n._('january'),
                    $.i18n._('february'),
                    $.i18n._('march'),
                    $.i18n._('april'),
                    $.i18n._('may'),
                    $.i18n._('june'),
                    $.i18n._('july'),
                    $.i18n._('august'),
                    $.i18n._('september'),
                    $.i18n._('october'),
                    $.i18n._('november'),
                    $.i18n._('december')
                ],

            }
        }

        window.DATATABLE_SINGLEDATETIME_CONFIG = {
            showWeekNumbers: true,
            autoUpdateInput: false,
            showDropdowns: true,
            timePicker: false,
            opens: 'left',
            applyClass: "btn-primary",
            singleDatePicker: true,
            timePicker: true,
            timePicker24Hour: window.APPLICATION_USER_TIME_FORMAT_24,
            locale: {
                format: window.APPLICATION_USER_DATE_FORMAT+' '+window.APPLICATION_USER_TIME_FORMAT,
                cancelLabel: $.i18n._('clear'),
                applyLabel:  $.i18n._('apply'),
                weekLabel: 'W',
                firstDay: 1,
                daysOfWeek: [
                    $.i18n._('calendar_su'),
                    $.i18n._('calendar_mo'),
                    $.i18n._('calendar_tu'),
                    $.i18n._('calendar_we'),
                    $.i18n._('calendar_th'),
                    $.i18n._('calendar_fr'),
                    $.i18n._('calendar_sa')
                ],
                monthNames: [
                    $.i18n._('january'),
                    $.i18n._('february'),
                    $.i18n._('march'),
                    $.i18n._('april'),
                    $.i18n._('may'),
                    $.i18n._('june'),
                    $.i18n._('july'),
                    $.i18n._('august'),
                    $.i18n._('september'),
                    $.i18n._('october'),
                    $.i18n._('november'),
                    $.i18n._('december')
                ],

            }
        }
    }

}

BAP_Config.init();


