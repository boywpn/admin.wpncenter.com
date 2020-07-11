var BAP_Temp = {

    relationDisplay: null,
    relationField: null,
    relatedRecordId: null,
}

String.prototype.replaceAll = function (search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};

/**
 *
 * @type {{init: BAP_Common.init, showNotification: BAP_Common.showNotification, activateTab: BAP_Common.activateTab, minifySidebar: BAP_Common.minifySidebar, iconSelect2Template: BAP_Common.iconSelect2Template, initComponents: BAP_Common.initComponents, buttonRedirect: BAP_Common.buttonRedirect, flashModal: BAP_Common.flashModal, skinChanger: BAP_Common.skinChanger, activateNotificationAndTasksScroll: BAP_Common.activateNotificationAndTasksScroll, setSkinListHeightAndScroll: BAP_Common.setSkinListHeightAndScroll, setSettingListHeightAndScroll: BAP_Common.setSettingListHeightAndScroll}}
 */
var BAP_Common = {


        /**
         * Init all fuctions
         */
        init: function () {


            this.skinChanger();
            this.activateNotificationAndTasksScroll();
            this.setSkinListHeightAndScroll(true);
            this.setSettingListHeightAndScroll(true);
            this.flashModal();

            this.buttonRedirect();
            this.initComponents();
            this.minifySidebar();
            this.activateTab();
            this.modalInModal();
            this.fileInput();
            this.tags();

            $(window).resize(function () {

                BAP_Common.setSkinListHeightAndScroll(false);
                BAP_Common.setSettingListHeightAndScroll(false);

                $.AdminBSB.leftSideBar.setMenuHeight();

            });

            $('.count-to').countTo();


        },

        tags: function () {

            var _tags = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                prefetch: {
                    url: '/core/extensions/tags',
                    cache:false,
                    filter: function(list) {
                        return $.map(list, function(tagName) {
                            return { name: tagName }; });
                    }
                }
            });
            _tags.initialize();

            $('.tags-input input').tagsinput({
                typeaheadjs: {
                    name: '_tags',
                    displayKey: 'name',
                    valueKey: 'name',
                    source: _tags.ttAdapter(),
                }
            });

            $(document).on('keypress', '.tags-input input', function (e) {
                if (e.which == 13) {
                    e.preventDefault();
                }
            });
        },

        /**
         *
         */
        modalInModal: function () {
            $(document).on('hidden.bs.modal', function (event) {
                if ($('.modal:visible').length) {
                    $('body').addClass('modal-open');
                }
            });
        },

        populateValues: function (form, data) {
            $.each(data, function (key, value) {
                var $ctrl = $('[name=' + key + ']', form);

                $ctrl.parent().addClass('focused');

                if ($ctrl.is('select')) {
                    $("option", $ctrl).each(function () {
                        if (this.value == value) {
                            this.selected = true;
                            $($ctrl).val(value).trigger("change");
                        }
                    });

                }
                else {
                    switch ($ctrl.attr("type")) {
                        case "text" :
                        case "textarea":
                        case "hidden":
                        case "email" :
                            $ctrl.val(value).trigger('change');
                            break;
                        case "radio" :
                        case "checkbox":
                            $ctrl.each(function () {
                                if ($(this).attr('value') == value) {
                                    $(this).attr("checked", value).trigger('change');
                                }
                            });
                            break;
                    }
                }
            });
        },

        /**
         * Setup manyToOne
         */
        manyToOneRelation: function () {

            // Load Related datatable into modal
            $(document).on('click', '.search-relation-button', function (e) {
                e.preventDefault();

                var current = $(this);

                $('#genericModal .modal-dialog').removeClass('modal-lg').addClass('modal-xl');
                $('#genericModal .modal-title').html($.i18n._(current.attr('data-modal-title')));

                BAP_Temp.relationDisplay = current.attr('data-display');
                BAP_Temp.relationField = current.attr('data-related-field');

                $('#genericModal .modal-body').load(current.attr('data-route'), function (result) {


                    $('#genericModal').modal('show');

                    return true;
                });

            });

            // Clear relation field
            $(document).on('click', '.clear-relation-button', function (e) {
                e.preventDefault();

                var current = $(this);

                current.parent().parent().removeClass('focused');

                $('#' + current.attr('data-related-field')).val('');
                $('#' + current.attr('data-related-field') + '_display').val('');

                return true;
            });

            // Bind data from popup to field
            $(document).on('click', '#RelatedModalTable tbody a', function (e) {
                e.preventDefault();

                var record = $(this);
                var row = record.parent().parent();


                $('#' + BAP_Temp.relationField).val(row.attr('record-id'));

                BAP_Temp.relatedRecordId = row.attr('record-id');

                var displayValue = row.find("a[data-column='" + BAP_Temp.relationDisplay + "']").attr('title');

                var relatedDisplayField = $('#' + BAP_Temp.relationField + '_display');

                relatedDisplayField.parent().addClass('focused');
                relatedDisplayField.val(displayValue);

                //Cleanup

                BAP_Temp.relationDisplay = null;
                BAP_Temp.relationField = null;

                $('#genericModal').modal('hide');

                $('#genericModal .modal-dialog').removeClass('modal-xl').addClass('modal-lg');
                $('#genericModal .modal-title').html('');
                $('#genericModal .modal-body').html('');
            });

        },


        /**
         *Show Notification
         * @param colorName - background bg-green, bg-red | /bap/pages/ui/colors.html
         * @param text
         * @param placementFrom - default bottom
         * @param placementAlign - default center
         */
        showNotification: function (colorName, text, placementFrom, placementAlign) {

            if (colorName === null || colorName === '') {
                colorName = 'bg-black';
            }
            if (text === null || text === '') {
                text = 'Turning standard Bootstrap alerts';
            }

            var animateEnter = 'animated fadeInDown';

            var animateExit = 'animated fadeOutUp';

            placementFrom = typeof placementFrom !== 'undefined' ? placementFrom : 'top';
            placementAlign = typeof placementAlign !== 'undefined' ? placementAlign : 'center';

            var allowDismiss = true;

            $.notify({
                    message: text,
                },
                {
                    type: colorName,
                    allow_dismiss: allowDismiss,
                    newest_on_top: true,
                    timer: 1000,
                    placement: {
                        from: placementFrom,
                        align: placementAlign
                    },
                    animate: {
                        enter: animateEnter,
                        exit: animateExit
                    },
                    template: '<div data-notify="container" class="bootstrap-notify-container alert alert-dismissible {0} ' + (allowDismiss ? "p-r-35" : "") + '" role="alert">' +
                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                    '<span data-notify="icon"></span> ' +
                    '<span data-notify="title">{1}</span> ' +
                    '<span data-notify="message">{2}</span>' +
                    '<div class="progress" data-notify="progressbar">' +
                    '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                    '</div>' +
                    '<a href="{3}" target="{4}" data-notify="url"></a>' +
                    '</div>'
                });

        },

        /**
         * Activate Tab base on url
         */
        activateTab: function () {

            // Javascript to enable link to tab
            var url = document.location.toString();
            if (url.match('#')) {
                $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');

            }
            // Change hash for page-reload
            $('.nav-tabs a').on('shown.bs.tab', function (e) {
                window.location.hash = e.target.hash;
                window.scrollTo(0, 0);
            })

        },

        /**
         * Minify / Normal Sidebar saved in cookies
         */
        minifySidebar: function () {

            // Get Config form cookie
            if (Cookies.get('sidebar-small') == 1) {
                $('body').addClass('sidebar-small');
                $('#minify-sidebar').html('keyboard_arrow_right');
            }
            $('#minify-sidebar').click(function () {
                if ($('body').hasClass('sidebar-small')) {
                    $('body').removeClass('sidebar-small');
                    Cookies.set('sidebar-small', 0);
                    $('#minify-sidebar').html('keyboard_arrow_left');
                } else {
                    $('body').addClass('sidebar-small');
                    Cookies.set('sidebar-small', 1);
                    $('#minify-sidebar').html('keyboard_arrow_right');
                }
            });

        },

        /**
         * Displays icon in select 2
         * @param icon
         * @returns {*|jQuery|HTMLElement}
         */
        iconSelect2Template: function (icon) {
            if (!icon.id) {
                return icon.text;
            }
            var $temp = $(
                '<div class="google-material-icon-select2"><i class="material-icons">' + icon.text + '</i><span class="icon-name">' + icon.text + '</span></div>'
            );
            return $temp;
        },


        /**
         * Init various components
         */
        initComponents: function () {

            this.manyToOneRelation();
            this.tags();


            $('.gravatar_type').fileinput({
                dropZoneEnabled: false,
                uploadAsync: false,
                showUpload: false,
                showRemove: false,
                showCaption: true,
                maxFileCount: 1,
                showBrowse: true,
                browseOnZoneClick: true,
            });


            $('select.select2').select2({
                theme: "bootstrap",
                width: '100%',

            });

            $('.color-picker').colorpicker({
                format: 'hex',
                extensions: [
                    {
                        name: 'swatches',
                        colors: {
                            'blue': '#2196F3',
                            'red': '#F44336',
                            'purple': '#9C27B0',
                            'orange': '#FF9800',
                            'light-green': '#8BC34A',
                            'deep-orange': '#FF5722',
                            'gray': '#9E9E9E'
                        },
                        namesAsValues: false
                    }
                ]
            });

            //Not used
            $('select.icon-select').select2({
                templateResult: BAP_Common.iconSelect2Template,
                theme: "bootstrap",
                width: '100%'
            });


            // Only Date Picker
            $('.datepicker').datetimepicker({

                locale: window.APPLICATION_USER_LANGUAGE,
                calendarWeeks: true,
                showClear: true,
                showTodayButton: true,
                showClose: true,
                format: window.APPLICATION_USER_DATE_FORMAT

            });

            // Date and Time Picker
            // TODO Implement in field
            $('.datetimepicker').datetimepicker({
                locale: window.APPLICATION_USER_LANGUAGE,
                calendarWeeks: true,
                showClear: true,
                showTodayButton: true,
                showClose: true,

                sideBySide: true,
                format: window.APPLICATION_USER_DATE_FORMAT + ' ' + window.APPLICATION_USER_TIME_FORMAT,
            });

            // Date Range Picket
            $('.daterange').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: window.APPLICATION_USER_DATE_FORMAT + ' ' + window.APPLICATION_USER_TIME_FORMAT,
                    cancelLabel: $.i18n._('clear'),
                    applyLabel: $.i18n._('apply'),
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
                    ]
                }
            });

            $('.daterange').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format(window.APPLICATION_USER_DATE_FORMAT) + ' - ' + picker.endDate.format(window.APPLICATION_USER_DATE_FORMAT));
            });

            $('.daterange').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });

        }
        ,

        /**
         * Common function to redirect on button click
         */
        buttonRedirect: function () {
            $('*[btn-click="1"]').on('click', function () {
                window.location.href = $(this).attr('data-url');
            })
        }
        ,

        /**
         * Show laravel flash in modal
         */
        flashModal: function () {
            $('#flash-overlay-modal').modal();
        }
        ,


        fileInput: function () {
            $('.file_input').fileinput({
                dropZoneEnabled: false,
                uploadAsync: false,
                showUpload: false,
                showRemove: false,
                showCaption: true,
                maxFileCount: 1,
                showBrowse: true,
                browseOnZoneClick: true,
            });
        },

        /**
         * Skin Changer
         */
        skinChanger: function () {

            $('.right-sidebar .demo-choose-skin li').on('click', function () {
                var $body = $('body');
                var $this = $(this);

                var existTheme = $('.right-sidebar .demo-choose-skin li.active').data('theme');
                $('.right-sidebar .demo-choose-skin li').removeClass('active');
                $body.removeClass(existTheme);
                $this.addClass('active');

                $body.addClass($this.data('theme'));

                $.ajax({
                    type: "POST",
                    url: '/account/ajax_update_account_settings',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'theme': $this.data('theme')
                    },
                    dataType: 'json',
                    success: function (result) {
                    }
                });
            });
        }
        ,


        /**
         * Activate notification and task dropdown on top right menu
         */
        activateNotificationAndTasksScroll: function () {
            $('.navbar-right .dropdown-menu .body .menu').slimscroll({
                height: '254px',
                color: 'rgba(0,0,0,0.5)',
                size: '4px',
                alwaysVisible: false,
                borderRadius: '0',
                railBorderRadius: '0'
            });
        }
        ,

        /**
         * Skin tab content set height and show scroll
         * @param isFirstTime
         */
        setSkinListHeightAndScroll: function (isFirstTime) {
            var height = $(window).height() - ($('.navbar').innerHeight() + $('.right-sidebar .nav-tabs').outerHeight());
            var $el = $('.demo-choose-skin');

            if (!isFirstTime) {
                $el.slimScroll({destroy: true}).height('auto');
                $el.parent().find('.slimScrollBar, .slimScrollRail').remove();
            }

            $el.slimscroll({
                height: height + 'px',
                color: 'rgba(0,0,0,0.5)',
                size: '6px',
                alwaysVisible: false,
                borderRadius: '0',
                railBorderRadius: '0'
            });
        }
        ,

        /**
         * Setting tab content set height and show scroll
         * @param isFirstTime
         */
        setSettingListHeightAndScroll: function (isFirstTime) {
            var height = $(window).height() - ($('.navbar').innerHeight() + $('.right-sidebar .nav-tabs').outerHeight());
            var $el = $('.right-sidebar .demo-settings');

            if (!isFirstTime) {
                $el.slimScroll({destroy: true}).height('auto');
                $el.parent().find('.slimScrollBar, .slimScrollRail').remove();
            }

            $el.slimscroll({
                height: height + 'px',
                color: 'rgba(0,0,0,0.5)',
                size: '6px',
                alwaysVisible: false,
                borderRadius: '0',
                railBorderRadius: '0'
            });
        }

    }
;


BAP_Common.init();


