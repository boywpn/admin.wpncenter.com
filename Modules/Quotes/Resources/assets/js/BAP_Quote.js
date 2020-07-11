var BAP_Quote = {

    tmpProductRow: null,

    init: function () {

        if (!window.bapQuoteModuleLoaded) {

            this.quoteSetup();
            this.recalculateSummary();
            this.copyButtons();
            this.searchProduct();

            window.bapQuoteModuleLoaded = true;
        }
    },

    loadProduct: function (productId) {

        $.ajax({
            type: "POST",
            url: '/quotes/load-product',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                'productId': productId
            },
            dataType: 'json',
            success: function (result) {

                if (result.data.product_id > 0) {

                    var row = BAP_Quote.tmpProductRow;

                    row.find('.row_product_id').val(result.data.product_id);
                    row.find('.row_product_name').val(result.data.product_name);
                    row.find('.row_price').val(result.data.unit_cost);
                    row.find('.row_quantity').val(result.data.quantity).trigger('change');
                    BAP_Quote.recalculateRows();

                    BAP_Common.initComponents();
                    $.AdminBSB.input.activate();
                }

            }
        });

    },

    searchProduct: function () {

        $(document).on('click', '.search-product', function (e) {
            e.preventDefault();

            var current = $(this);

            BAP_Quote.tmpProductRow = current.closest('.quote_row');

            $('#genericModal .modal-dialog').removeClass('modal-lg').addClass('modal-xl');
            $('#genericModal .modal-title').html($.i18n._('choose_product_or_service'));

            $('#genericModal .modal-body').load('/products/products?mode=modal', function (result) {

                $('#genericModal').modal('show');

                return true;
            });
        });

        $(document).on('click', '#RelatedModalTable tbody a', function (e) {
            e.preventDefault();

            var record = $(this);
            var row = record.parent().parent();

            var recordType = row.attr('record-type');
            var recordId = row.attr('record-id');

            if (recordType === 'Modules\\Products\\Entities\\Product') {

                BAP_Quote.loadProduct(recordId);

            }
        });

    },

    copyButtons: function () {

        $(document).on('click', '#quote-copy-from-company', function (e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: '/quotes/company-settings',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {},
                dataType: 'json',
                success: function (result) {

                    $('#from_company').val(result.data.company_name);
                    $('#from_tax_number').val(result.data.vat_id);
                    $('#from_street').val(result.data.address);
                    $('#from_city').val(result.data.city);
                    $('#from_state').val(result.data.state);
                    $('#from_country').val(result.data.country);
                    $('#from_zip_code').val(result.data.postal_code);

                    BAP_Common.initComponents();
                    $.AdminBSB.input.activate();
                }
            });

        });
        $(document).on('click', '#quote-copy-from-shipping', function (e) {
            e.preventDefault();

            var ship_to = $('#ship_to').val();
            var ship_tax_number = $('#ship_tax_number').val();
            var ship_street = $('#ship_street').val();
            var ship_city = $('#ship_city').val();
            var ship_state = $('#ship_state').val();
            var ship_country = $('#ship_country').val();
            var ship_zip_code = $('#ship_zip_code').val();

            $('#bill_to').val(ship_to);
            $('#bill_tax_number').val(ship_tax_number);
            $('#bill_street').val(ship_street);
            $('#bill_city').val(ship_city);
            $('#bill_state').val(ship_state);
            $('#bill_country').val(ship_country);
            $('#bill_zip_code').val(ship_zip_code);

            BAP_Common.initComponents();
            $.AdminBSB.input.activate();
        });
        $(document).on('click', '#quote-copy-from-account', function (e) {
            e.preventDefault();

            var accountId = $('#account_id').val();

            if (accountId > 0) {
                $.ajax({
                    type: "POST",
                    url: '/quotes/copy-account',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'accountId': accountId
                    },
                    dataType: 'json',
                    success: function (result) {


                        $('#street').val(result.data.address);
                        $('#city').val(result.data.city);
                        $('#state').val(result.data.state);
                        $('#country').val(result.data.country);
                        $('#zip_code').val(result.data.postal_code);

                        BAP_Common.initComponents();
                        $.AdminBSB.input.activate();
                    }
                });
            } else {
                BAP_Common.showNotification('bg-red', $.i18n._('fill_in_the_missing_record'));
            }

        });
        $(document).on('click', '#quote-copy-from-billing', function (e) {
            e.preventDefault();

            var bill_to = $('#bill_to').val();
            var bill_tax_number = $('#bill_tax_number').val();
            var bill_street = $('#bill_street').val();
            var bill_city = $('#bill_city').val();
            var bill_state = $('#bill_state').val();
            var bill_country = $('#bill_country').val();
            var bill_zip_code = $('#bill_zip_code').val();

            $('#ship_to').val(bill_to);
            $('#ship_tax_number').val(bill_tax_number);
            $('#ship_street').val(bill_street);
            $('#ship_city').val(bill_city);
            $('#ship_state').val(bill_state);
            $('#ship_country').val(bill_country);
            $('#ship_zip_code').val(bill_zip_code);

            BAP_Common.initComponents();
            $.AdminBSB.input.activate();
        });

    },

    /**
     1. recalculate rows names
     **/
    recalculateRows: function () {

        $('#quote-rows').find('.quote_row').each(function (counter, element) {

            $(element).find("input[name$='[id]']").attr('name', 'rows[' + counter + '][id]');
            $(element).find("input[name$='[product_name]']").attr('name', 'rows[' + counter + '][product_name]');
            $(element).find("input[name$='[price]']").attr('name', 'rows[' + counter + '][price]');
            $(element).find("input[name$='[quantity]']").attr('name', 'rows[' + counter + '][quantity]');
            $(element).find(".form-control-static").attr('id', 'rows[' + counter + '][lineTotal]');

        });

        BAP_Common.initComponents();
        $.AdminBSB.input.activate();

        BAP_Quote.recalculateSummary();
    },


    calculateTax: function (subtotal, discount, deliveryCost) {

        var nettoValue = (((subtotal + deliveryCost) - discount));

        var tax_percent = $('#tax_id :selected').attr('data-tax');

        if (tax_percent != null) {
            return nettoValue * tax_percent;
        }
        return 0;
    },

    calculateGross: function (subtotal, deliveryCost, discount, tax) {
        return subtotal + deliveryCost - discount + tax;
    },

    recalculateSummary: function () {

        var subtotal = 0;
        var discount = parseFloat($('#discount').val() != '' ? $('#discount').val() : 0);
        var deliveryCost = parseFloat($('#delivery_cost').val() != '' ? $('#delivery_cost').val() : 0);

        $.each($('.row_line_total'), function (index, value) {
            lineTotal = $(value).html().replaceAll(',', '.').replaceAll('&nbsp;', '');
            subtotal += parseFloat(lineTotal);
        });

        var tax = BAP_Quote.calculateTax(subtotal, discount, deliveryCost);

        var summaryGross = BAP_Quote.calculateGross(subtotal, deliveryCost, discount, tax);


        $('.summary_subtotal').html($.number(subtotal, 2));
        $('.summary_discount').html($.number(discount, 2));
        $('.summary_delivery_cost').html($.number(deliveryCost, 2));
        $('.summary_tax').html($.number(tax, 2));
        $('.summary_gross').html($.number(summaryGross, 2));

    },

    addRow: function () {

        var clone = $('#quote-rows').find('.quote_row:first').clone();

        var countRows = $('#quote-rows').find('.quote_row').length;

        clone.find('.form-line').removeClass('focused');
        clone.find('.row_line_total').html(0);

        clone.find('input').each(function () {
            this.name = this.name.replace('[0]', '[' + countRows + ']');
        });

        clone.find('input').val('');
        clone.find('input').attr('value', '');

        clone.insertAfter($('#quote-rows tbody .quote_row:last'));

        BAP_Quote.recalculateRows();
    },

    quoteSetup: function () {

        $(document).on('click', '#quote-add-row', function (e) {

            e.preventDefault();

            BAP_Quote.addRow();

        });

        $(document).on('change', '.row_price,.row_quantity', function (e) {

            var price = $(this).closest('tr').find('.row_price').val();
            var quantity = $(this).closest('tr').find('.row_quantity').val();

            var line_total = price * quantity;

            $(this).closest('tr').find('.row_line_total').html($.number(line_total, 2));

            BAP_Quote.recalculateSummary();
        });

        $(document).on('change', '#discount,#delivery_cost,#tax_id', function (e) {
            BAP_Quote.recalculateSummary();
        });


        $(document).on('click', '.quote-remove-row', function (e) {

            e.preventDefault();
            var countRows = $('#quote-rows').find('.quote_row').length;
            if (countRows > 1) {
                $(this).closest('.quote_row').remove();

                BAP_Quote.recalculateRows();
            }


        });
    },

};

BAP_Quote.init();
