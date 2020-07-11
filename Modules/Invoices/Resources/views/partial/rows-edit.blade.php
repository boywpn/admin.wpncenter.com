<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h2 class="card-inside-title">
        @lang('invoices::invoices.panel.products_and_services')

        <div class="section-buttons">
            <a href="#" class="btn normal-text btn-primary btn-xs" id="invoice-add-row">
                @lang('invoices::invoices.form.add_row')
            </a>
        </div>
    </h2>
</div>

<div class="col-lg-12 col-md-12 col-xs-12">
    <div class="table-responsive  col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <table id="invoice-rows" class="table table-condensed">
            <thead>

            <th>
                @lang('invoices::invoices.form.product_service')
            </th>
            <th>
                @lang('invoices::invoices.form.unit_cost')
            </th>
            <th>
                @lang('invoices::invoices.form.quantity')
            </th>

            <th>
                @lang('invoices::invoices.form.line_total')
            </th>
            <th>

            </th>
            </thead>
            <tbody>


            @foreach($options['children'] as $row)
                <tr class="invoice_row">

                    <td>
                        {!! form_row($row->id) !!}
                        {!! form_row($row->product_id) !!}
                        {!! form_row($row->price_list_id) !!}
                        <div class="input-group">
                        <span class="input-group-addon">
                           <i title="@lang('products::products.search_product')" class="search-product material-icons">search</i>
                        </span>
                            {!! form_row($row->product_name) !!}
                        </div>

                    </td>
                    <td>
                        <div class="input-group">
                            <span  class="input-group-addon">
                               <i title="@lang('products::products.price_book')" class="search-price-list fa fa-book pointer"></i>
                            </span>

                            {!! form_row($row->price, $options = ['attr' => ['class' => 'form-control row_price '.($row->price_list_id->getValue() > 0 ? 'read-only' : 'f')]]) !!}

                        </div>

                    </td>
                    <td>
                        {!! form_row($row->quantity) !!}
                    </td>
                    <td>
                        {!! form_row($row->lineTotal) !!}
                    </td>
                    <td>
                        <i class="material-icons invoice-remove-row pointer">clear</i>
                    </td>
                </tr>

            @endforeach

            <tr>
                <td colspan="2" rowspan="7">

                </td>
                <td>
                    <label class="show-control-label text-right">
                        @lang('invoices::invoices.form.subtotal')
                    </label>
                </td>
                <td class="summary_subtotal">

                </td>
            </tr>
            <tr>
                <td>
                    <label class="show-control-label text-right">
                        @lang('invoices::invoices.form.discount')
                    </label>
                </td>
                <td class="summary_discount">

                </td>
            </tr>
            <tr>
                <td>
                    <label class="show-control-label text-right">
                        @lang('invoices::invoices.form.delivery_cost')
                    </label>
                </td>
                <td class="summary_delivery_cost">

                </td>
            </tr>
            <tr>
                <td>
                    <label class="show-control-label text-right">
                        @lang('invoices::invoices.form.tax')
                    </label>
                </td>
                <td class="summary_tax">

                </td>
            </tr>
            <tr>
                <td>
                    <label class="show-control-label text-right">
                        @lang('invoices::invoices.form.gross_value')
                    </label>
                </td>
                <td class="summary_gross">

                </td>
            </tr>
            <tr>
                <td>
                    <label class="show-control-label text-right">
                        @lang('invoices::invoices.form.paid_to_date')
                    </label>
                </td>
                <td class="summary_paid_to_date">

                </td>
            </tr>
            <tr>
                <td>
                    <label class="show-control-label text-right">
                        @lang('invoices::invoices.form.balance_due')
                    </label>
                </td>
                <td class="summary_balance_due bold">

                </td>
            </tr>

            </tbody>

        </table>
    </div>

</div>
