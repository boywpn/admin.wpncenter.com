var BAP_Notifications = {

    init: function () {

        this.loadTopBarNotifications();
        this.loadMoreNotifications();
        this.markAsReadNotifications();
        this.deleteNotifications();
        this.pusherNotificationListener();
    },

    /**
     * Set new notifications count in top-bar
     */
    recountNewNotifications: function () {

        $.ajax({
            type: "GET",
            url: '/notifications/count-new',
            dataType: 'json',
            success: function (data) {

                var topBar = $("#top_bar_notifications_count");

                topBar.html(data.unreadNotifications);

            },
            error: function (data) {
                //
            }
        });

    },

    /**
     * Load top-bar notification on click
     */
    loadTopBarNotifications: function () {

        $(document).on('click', '#top-bar-notifications', function () {

            $.ajax({
                type: "POST",
                url: '/notifications/top-bar-notifications',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function (data) {

                    var topBar = $("#top-bar-notifications-menu");

                    topBar.html(data.content);

                },
                error: function (data) {
                    //
                }
            });
        });

    },

    loadMoreNotifications: function () {
        $('.notifications-pagination ul.pagination').hide();
        $('#notification-scroll').jscroll({
            autoTrigger: true,
            loadingHtml: '<b>' + $.i18n._('please_wait') + '</b>',
            padding: 0,
            nextSelector: '.pagination li.active + li a',
            contentSelector: '#notification-scroll',
            callback: function () {
                $('ul.pagination').remove();
            }
        });
    },

    markAsReadNotifications: function () {
        $(document).on('click', '.notification-mark', function (e) {

            e.preventDefault();

            var row = $(this).parent();

            $.ajax({
                type: "POST",
                url: '/notifications/mark-notification',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: $(this).attr('data-id')
                },
                dataType: 'json',
                success: function (data) {

                    if (data.status == 'error') {
                        BAP_Common.showNotification('bg-red', result.content);
                    } else {
                        if (data.content == 'read') {
                            row.find('.badge').hide();
                        } else {
                            row.find('.badge').show();
                        }
                    }

                },
                error: function (data) {
                    //
                }
            });
        });
    },

    deleteNotifications: function () {
        $(document).on('click', '.notification-delete', function (e) {

            e.preventDefault();

            var row = $(this).parent();

            $.ajax({
                type: "POST",
                url: '/notifications/delete',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: $(this).attr('data-id')
                },
                dataType: 'json',
                success: function (data) {

                    if (data.status == 'error') {
                        BAP_Common.showNotification('bg-red', result.content);
                    } else {
                        if (data.content == 'deleted') {
                            row.parent().remove();
                        }
                    }

                },
                error: function (data) {
                    //
                }
            });
        });
    },

    pusherNotificationListener: function () {
        if (window.PUSHER_ACIVE > 0) {

            var channel = window.PUSHER.subscribe('user-notification-' + window.UID);

            channel.bind('Illuminate\\Notifications\\Events\\BroadcastNotificationCreated', function (data) {

                BAP_Notifications.recountNewNotifications();
                BAP_Common.showNotification(data.color, data.content, 'top', 'right');
            });
            channel.bind('Modules\\Notifications\\Events\\NotificationEvent', function (data) {

                BAP_Notifications.recountNewNotifications();
            });
        }

    }
}

BAP_Notifications.init();