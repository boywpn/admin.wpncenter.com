var BAP_Calendar = {

    init: function () {
        this.createEventSetup();
        this.createCalendarSetup();
        this.fullScreen();
        this.calendarSwitch();

        $(window).resize(function() {
            $('#fullcalendar').fullCalendar( "rerenderEvents" );
        });

    },

    calendarSwitch: function(){

        $('#switch-calendar').select2({
            theme: "bootstrap",
            width: '100%',
            ajax: {
                url: '/calendar/accessible-calendars'
            }
        });
        $('#switch-calendar').on('select2:select', function (e) {
            window.location.href = "/calendar?calid="+e.params.data.id;
        });

    },


    fullScreen: function () {

        $(document).on('click','#calendar-full-screen',function(e){

            e.preventDefault();

            var calendarContainer =  $('#calendar-container');

            if(calendarContainer.hasClass('full-screen')){
                calendarContainer.removeClass('full-screen');
            }else{
                calendarContainer.addClass('full-screen');
            }

        });

    },


    refreshEvents: function(){
        $('#fullcalendar').fullCalendar('refetchEvents');
    },

    createCalendarSetup : function(){

        $(document).on('click', "#create-new-calendar", function (e) {
            e.preventDefault();

            var me = $(this);

            var modal = $('#calendar-event-modal');

            modal.find('.modal-body').load(me.attr('data-create-route'), function (result) {
                modal.modal('toggle');
                BAP_Common.initComponents();
                $.AdminBSB.input.activate();
            });
        });

        $(document).on("submit", ".calendarModalForm", function (e) {

            e.preventDefault();

            var form = $(e.target);

            $.post(form.attr('action'), form.serialize(), function (result) {

                $(form).parents('.modal').modal('toggle'); // close parent

                if (result.action == 'refresh_datatable') {

                    BAP_Common.showNotification('bg-green', result.message);

                    BAP_Calendar.calendarSwitch();
                    BAP_Calendar.refreshEvents();
                }

                if (result.action == 'show_message') {

                    BAP_Common.showNotification('bg-red', result.message);

                    BAP_Calendar.calendarSwitch();
                    BAP_Calendar.refreshEvents();
                }

            });

        });

    },


    createEventSetup: function () {
        // open popup

        $(document).on('click', "#create-calendar-event", function (e) {
            e.preventDefault();

            var me = $(this);

            var modal = $('#calendar-event-modal');

            modal.find('.modal-body').load(me.attr('data-create-route'), function (result) {
                modal.modal('toggle');
                BAP_Common.initComponents();
                $.AdminBSB.input.activate();
            });
        });

        $(document).on("submit", ".eventModalForm", function (e) {

            e.preventDefault();

            var form = $(e.target);

            $.post(form.attr('action'), form.serialize(), function (result) {

                $(form).parents('.modal').modal('toggle'); // close parent

                if (result.action == 'refresh_datatable') {

                    BAP_Common.showNotification('bg-green', result.message);

                    BAP_Calendar.refreshEvents();
                }

                if (result.action == 'show_message') {

                    BAP_Common.showNotification('bg-red', result.message);

                    BAP_Calendar.refreshEvents();
                }

            });

        });
    },

    calendarEventDrop: function (event, delta, revertFunction) {

        var start = event.start;
        var end   = event.end;

        if(event.allDay){
            start = event.start.format("DD-MM-YYYY");
            end   = event.start.format("DD-MM-YYYY");
        }else{
            start = event.start.format("DD-MM-YYYY HH:mm");
            if(end != null) {
                end = event.end.format("DD-MM-YYYY HH:mm");
            }else{
                end = event.start.add(2, 'hours').format("DD-MM-YYYY HH:mm");
            }
        }

        $.ajax({
            type: "POST",
            url: '/calendar/event-manage',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                'action' : 'drop',
                'eventId': event.id,
                'startDate' : start,
                'endDate' : end,
                'fullDay' : event.allDay
            },
            dataType: 'json',
            success: function (result) {
                if(result.action === 'refresh'){
                    BAP_Calendar.refreshEvents();
                    BAP_Common.showNotification('bg-green', result.message);
                }
                if(result.action === 'show_message'){
                    BAP_Common.showNotification('bg-red', result.message);
                    BAP_Calendar.refreshEvents();
                }

            }
        });
    },

    calendarEventClick: function (event, jsEvent, view) {
        window.location.href = "/calendar/events/"+event.id;
    },

    calendarEventResize: function (event, delta, revertFunction) {
        $.ajax({
            type: "POST",
            url: '/calendar/event-manage',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                'action' : 'resize',
                'eventId': event.id,
                'endDate' : event.end.format("DD-MM-YYYY HH:mm"),
            },
            dataType: 'json',
            success: function (result) {
                if(result.action === 'refresh'){
                    BAP_Calendar.refreshEvents();
                    BAP_Common.showNotification('bg-green', result.message);
                }
                if(result.action === 'show_message'){
                    BAP_Common.showNotification('bg-red', result.message);
                    BAP_Calendar.refreshEvents();
                }

            }
        });
    }

}

BAP_Calendar.init();