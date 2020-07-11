/**
 *
 * @type {{init: BAP_MenuManager.init, initEdit: BAP_MenuManager.initEdit, populateValues: BAP_MenuManager.populateValues, initMenuManager: BAP_MenuManager.initMenuManager}}
 */
var BAP_MenuManager = {

    /**
     * Init rest of functions
     */
    init: function () {

        this.initMenuManager();

        this.initEdit();


    },

    /**
     * Init Element Edit form
     */
    initEdit: function () {

        $('.delete-menu').on('click', function (event) {
            event.preventDefault();

            var element = $(this);

            var r = confirm($.i18n._('are_you_sure'));
            if (r == true) {

                var id = $(this).attr('data-id');
                if (id != null && id > 0) {
                    $.ajax({
                        type: "GET",
                        url: '/settings/menu_manager/delete-element/' + id,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },

                        dataType: 'json',
                        success: function (result) {
                            BAP_Common.showNotification(result.color, result.message);
                            element.parent().parent().remove();
                        }
                    });
                }

            }

        });

        /**
         * Load data into form
         */
        $('.edit-menu').on('click', function (event) {

            event.preventDefault();

            $("#save_menu_element_form #visibility").prop("checked", false).trigger('change');
            $("#save_menu_element_form #dont_translate").prop("checked", false).trigger('change');

            var id = $(this).attr('data-id');
            if (id != null && id > 0) {

                $.ajax({
                    type: "GET",
                    url: '/settings/menu_manager/get_menu_element/' + id,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },

                    dataType: 'json',
                    success: function (result) {

                        BAP_MenuManager.populateValues('#save_menu_element_form', result.menu_element);

                        if (result.menu_element.visibility != null && result.menu_element.visibility > 0) {
                            $("#save_menu_element_form #visibility").prop("checked", true).trigger('change');
                        }
                        if (result.menu_element.dont_translate != null && result.menu_element.dont_translate > 0) {
                            $("#save_menu_element_form #dont_translate").prop("checked", true).trigger('change');
                        }

                    }

                });

            }
        });

        /**
         * Save on form submit
         */
        $('#save_menu_element_form').on('submit', function (event) {
            event.preventDefault();

            var form = $(this);

            $.ajax({
                type: "POST",
                url: '/settings/menu_manager/save_menu_element',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: form.serialize(),

                dataType: 'json',
                success: function (result) {
                    BAP_Common.showNotification(result.color, result.message);

                    $("#menu-label-" + result.id).html(result.label);

                }

            });
        });

    },

    /**
     * Populate data from server skip visibility and dont_translate
     * @param form
     * @param data
     * @returns {boolean}
     */
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

                        if (key != 'visibility' && key != 'dont_translate') {
                            $ctrl.val(value).trigger('change');
                        }

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

        return true;
    },

    /**
     * Init nestable menu and preform actions
     */
    initMenuManager: function () {

        // init nestable
        $('.dd').nestable();

        // Save menu after reorder
        $('.dd').on('change', function () {
            var $this = $(this);
            var serializedData = window.JSON.stringify($($this).nestable('serialize'));

            $.ajax({
                type: "POST",
                url: '/settings/menu_manager/update_order',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'order': serializedData
                },
                dataType: 'json',
                success: function (result) {
                    BAP_Common.showNotification(result.color, result.message);
                }

            });
        });

    },

}

BAP_MenuManager.init();
