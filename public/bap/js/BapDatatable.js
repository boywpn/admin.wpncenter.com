$.fn.dataTable.ext.buttons.reset = {
    className: 'buttons-reset', text: function (dt) {
        return '<i class="fa fa-undo"></i> ';
    }, action: function (e, dt, button, config) {

        dt.search('').draw();
        yadcf.exResetAllFilters(dt);

    }
};

$.extend(true, jQuery.fn.dataTable.defaults, {
    // Global datatable settings

});
/***
 * Lots of help here
 * datetimepicker - https://github.com/mistic100/jQuery-QueryBuilder/issues/176
 */
/**
 * @type {{init: BAP_Datatable.init, newRelation: BAP_Datatable.newRelation, addSelected: BAP_Datatable.addSelected, linkRelation: BAP_Datatable.linkRelation, unlinkRelation: BAP_Datatable.unlinkRelation}}
 */
var BAP_Datatable = {


    init: function () {
        this.unlinkRelation();
        this.deleteRelation();
        this.linkRelation();
        this.addSelected();
        this.newRelation();
        this.quickCreare();
        this.quickEdit();
        this.csvImport();

        this.queryBuilder();
        this.advancedViews();

    },

    advancedViewsComponents: function () {

        $('.advanced_views_select').on('select2:select', function (e) {
            console.log($(this).attr('related-table'));
            console.log(e.params.data.id);

            window.location = '?advView=' + e.params.data.id;

        });

        $('.advanced_views_settings').on('click', function (e) {

            e.preventDefault();

            var id = $(this).attr('data-id');
            var dataTableType = $(this).attr('data-table-type');
            var tableId = $(this).attr('table-id');
            var moduleName = $(this).attr('module-name');

            e.preventDefault();

            var modal = $('#advanced_filters_config_modal');

            modal.find('.modal-body').load('/core/extensions/advanced-view/get/' + id + '?dataTableType=' + dataTableType + '&tableId=' + tableId + '&mode=create&moduleName=' + moduleName, function (result) {

                modal.modal('show');
                BAP_Common.initComponents();
                $.AdminBSB.input.activate();
                BAP_Datatable.advancedViewsComponents();

            });

        });

        $('#all_module_fileds option').dblclick(function (e) {
            e.preventDefault();
            $('select').moveToListAndDelete('#all_module_fileds', '#selected_fileds');
        });

        $('#adv-btn-right').click(function (e) {
            e.preventDefault();
            $('select').moveToListAndDelete('#all_module_fileds', '#selected_fileds');
        });

        $('#adv-btn-left').click(function (e) {
            e.preventDefault();
            $('select').moveToListAndDelete('#selected_fileds', '#all_module_fileds');
        });

        $('#selected_fileds option').dblclick(function (e) {
            e.preventDefault();
            $('select').moveToListAndDelete('#selected_fileds', '#all_module_fileds');
        });

        $('#adv-btn-up').click(function (e) {
            e.preventDefault();
            $('select').moveUpDown('#selected_fileds', true, false);
        });

        $('#adv-btn-down').click(function (e) {
            e.preventDefault();
            $('select').moveUpDown('#selected_fileds', false, true);
        });

    },

    /**
     * Advanced views
     */
    advancedViews: function () {

        BAP_Datatable.advancedViewsComponents();


        $(document).on('click', '.delete-list-view', function (e) {

            e.preventDefault();
            var id = $(this).attr('data-id');

            $.ajax({
                type: "POST",
                url: '/core/extensions/advanced-view/delete',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'id': id
                },
                dataType: 'json',
                success: function (result) {
                    if (result.action == 'show_message') {
                        if (result.type == 'success') {
                            BAP_Common.showNotification('bg-green', result.message);
                            location.reload();

                        }
                        if (result.type == 'error') {
                            BAP_Common.showNotification('bg-red', result.message);
                        }

                    }
                }
            });

        });

        $(document).on('click', '.edit-list-view', function (e) {

            e.preventDefault();

            var id = $(this).attr('data-id');
            var dataTableType = $(this).attr('data-table-type');
            var tableId = $(this).attr('table-id');

            e.preventDefault();

            var modal = $('#advanced_filters_config_modal');

            modal.find('.modal-body').load('/core/extensions/advanced-view/get/' + id + '?dataTableType=' + dataTableType + '&tableId=' + tableId, function (result) {

                modal.modal('toggle');
                BAP_Common.initComponents();
                $.AdminBSB.input.activate();
                BAP_Datatable.advancedViewsComponents();

            });

        });

        $(document).on("submit", "#advanced_view_settings_form", function (e) {

            e.preventDefault();

            var _rules = $('#advanced_settings_filters_creator').queryBuilder('getRules');

            if (!$.isEmptyObject(_rules)) {

                var _json = JSON.stringify(_rules);

                $('#module_rules').val(_json);

            }

            var form = $(e.target);

            $('#selected_fileds option').each(function () {
                $(this).prop('selected', true);
            });

            var serializedValues = form.serialize();

            $.post(form.attr('action'), serializedValues, function (result) {

                if (result.action == 'show_message') {
                    if (result.type == 'success') {
                        BAP_Common.showNotification('bg-green', result.message);

                        $(form).parents('.modal').modal('toggle'); // close parent

                        location.reload();

                    }
                    if (result.type == 'error') {
                        BAP_Common.showNotification('bg-red', result.message);
                    }

                }

            });


        });


    },


    /**
     * Query builder
     */
    queryBuilder: function () {

        $('.btn-hide').on('click', function () {
            $(this).parent().hide();
        });
        $('.btn-show-filters').on('click', function () {
            var btn = $(this);

            var filters = $('#' + btn.attr('data-filter-id'));

            if (filters.is(":visible")) {
                filters.hide();
            } else {
                filters.show();
            }
        });
        $('.btn-get').on('click', function () {

            var tableIdentifier = $(this).parent().attr('data-table-id');

            var _rules = $(this).parent().queryBuilder('getRules');

            if (!$.isEmptyObject(_rules)) {

                var _json = JSON.stringify(_rules);

                var datatablesRequest = {rules: _json};

                var _table = $('#' + tableIdentifier).DataTable();

                _table.on('preXhr.dt', function (e, settings, data) {
                    $.each(datatablesRequest, function (k, v) {
                        data[k] = v;
                    });
                });

                _table.ajax.reload(null, false);

            }
            else {
                console.log("invalid object :");
            }

        });

        $('.btn-reset').on('click', function () {

            var tableIdentifier = $(this).parent().attr('data-table-id');

            var _table = $('#' + tableIdentifier).DataTable();
            _table.on('preXhr.dt', function (e, settings, data) {
                data['rules'] = '';
            });
            _table.ajax.reload();
            $(this).parent().queryBuilder('reset');
        });


    },

    csvImport: function () {

        $(document).on("click", ".records_import", function (e) {

            e.preventDefault();

            $('#modal_records_import').modal('toggle');

        });

    },

    quickCreare: function () {

        $(document).on("click", ".quick_create", function (e) {

            e.preventDefault();

            var modal = $('#modal_quick_create');

            modal.find('.modal-body').load(modal.attr('data-create-route'), function (result) {
                modal.modal('toggle');
                BAP_Common.initComponents();
                $.AdminBSB.input.activate();

            });

        });


    },

    tempTable: null,

    quickEdit: function () {

        // Quick Edit
        $(document).on("click", ".quick_edit", function (e) {

            e.preventDefault();

            BAP_Datatable.tempTable = $(this).parents('.related_module_wrapper').find('.linked-records').find('table').dataTable();

            var editUrl = $(this).attr('edit-url');

            var modal = $('#modal_quick_edit');

            modal.find('.modal-body').load(editUrl, function (result) {
                modal.modal('toggle');
                BAP_Common.initComponents();
                $.AdminBSB.input.activate();
            });

        });

        $(document).on("submit", ".update-related-modal-form", function (e) {

            e.preventDefault();

            var form = $(e.target);

            var formData = new FormData(form[0]);

            if (form.valid()) {

                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (result) {

                        $(form).parents('.modal').modal('toggle'); // close parent

                        if (result.action == 'refresh_datatable') {

                            BAP_Common.showNotification('bg-green', result.message);

                            var linkedDataTable = BAP_Datatable.tempTable;
                            linkedDataTable.DataTable().ajax.reload();

                            if (window.LaravelDataTables["dataTableBuilder"] != '') {
                                $("#dataTableBuilder").DataTable().ajax.reload();
                            }
                        }

                        if (result.action == 'show_message') {
                            BAP_Common.showNotification('bg-red', result.message);
                        }

                    }
                });

            } else {
                return false;
            }

        });

    },


    /**
     * 1. Show create form
     * 2. Post create form data
     * 3. Refresh linked datatable
     */
    newRelation: function () {

        $(document).on("click", ".modal-new-relation", function (e) {

            e.preventDefault();

            var modal = $(this).parent().find('.modal');

            modal.find('.modal-body').load(modal.attr('data-create-route'), function (result) {
                modal.modal('toggle');
                BAP_Common.initComponents();
                $.AdminBSB.input.activate();

            });

        });

        $(document).on("submit", ".related-modal-form", function (e) {

            e.preventDefault();

            var form = $(e.target);

            if (form.valid()) {

                var formData = new FormData(form[0]);

                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (result) {

                        $(form).parents('.modal').modal('toggle'); // close parent

                        if (result.action == 'refresh_datatable') {

                            BAP_Common.showNotification('bg-green', result.message);

                            var linkedDataTable = $(form).parents('.related_module_wrapper').find('.linked-records').find('table').dataTable();
                            linkedDataTable.DataTable().ajax.reload();

                            if (window.LaravelDataTables["dataTableBuilder"] != '') {
                                $("#dataTableBuilder").DataTable().ajax.reload();
                            }
                        }

                        if (result.action == 'show_message') {
                            BAP_Common.showNotification('bg-red', result.message);
                        }

                    }
                });
            } else {
                return false;
            }

        });
    },

    /**
     * Add selected to relation
     */
    addSelected: function () {

        $(document).on("submit", ".link-selected", function (e) {
            e.preventDefault();

            var form = $(e.target);

            var modalTableName = $(form).find('input[name=modalName]');
            var modalDataTable = $($('#' + modalTableName.val()).find('table').first()).dataTable();

            var checkedElements = [];

            var rowcollection = modalDataTable.$(".call-checkbox:checked", {"page": "all"});

            rowcollection.each(function (index, elem) {
                checkedElements.push($(elem).val());
            });

            $(form).find('input[name=relationEntityIds]').val(JSON.stringify(checkedElements));

            $.post(form.attr('action'), form.serialize(), function (result) {

                if (result.action == 'refresh_datatable') {

                    BAP_Common.showNotification('bg-green', result.message);

                    // DataTable in modal popup
                    modalDataTable.DataTable().ajax.reload();

                    // Refresh linked datatable
                    var linkedTableName = $(form).find('input[name=linkedName]');
                    var linkedDataTable = $($('#' + linkedTableName.val()).find('table').first()).dataTable();
                    linkedDataTable.DataTable().ajax.reload();

                }
                if (result.action == 'show_message') {

                    BAP_Common.showNotification('bg-red', result.message);

                }

                $('#' + modalTableName.val()).modal('toggle');

            });
        });
    },

    /**
     * Link Relation Modal
     */
    linkRelation: function () {


        $(document).on("click", ".modal-relation", function (e) {

            e.preventDefault();

            var modal = $(this).parent().find('.modal');

            modal.modal('toggle');
        });

    },

    /**
     * Unlink Relation
     */
    unlinkRelation: function () {

        $(document).on("submit", ".unlink-relation", function (e) {
            e.preventDefault();

            var form = $(e.target);

            $.post(form.attr('action'), form.serialize(), function (result) {

                if (result.action == 'refresh_datatable') {

                    BAP_Common.showNotification('bg-green', result.message);

                    var table = form.closest('table').DataTable().ajax.reload();
                }
                if (result.action == 'show_message') {
                    alert(result.message);

                    BAP_Common.showNotification('bg-red', result.message);
                }

            });
        });

    },

    deleteRelation: function () {

        $(document).on("submit", ".delete-relation", function (e) {
            e.preventDefault();

            var form = $(e.target);

            $.post(form.attr('action'), form.serialize(), function (result) {

                if (result.action == 'refresh_datatable') {

                    BAP_Common.showNotification('bg-green', result.message);

                    var table = form.closest('table').DataTable().ajax.reload();
                }
                if (result.action == 'show_message') {
                    alert(result.message);

                    BAP_Common.showNotification('bg-red', result.message);
                }

            });
        });

    },
};

BAP_Datatable.init();


