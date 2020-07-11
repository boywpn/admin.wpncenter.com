<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h2 class="card-inside-title">
        @lang('orders::orders.panel.products_and_services')

        <div class="section-buttons">
            <a href="#" class="btn normal-text btn-primary btn-xs" id="order-add-row">
                @lang('orders::orders.form.add_row')
            </a>
        </div>
    </h2>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

    <div class="table-responsive  col-lg-12 col-md-12 col-sm-12">
        <table id="order-rows" class="table table-condensed">
            <thead>

            <th>
                @lang('orders::orders.form.product_service')
            </th>
            <th>
                @lang('orders::orders.form.unit_cost')
            </th>
            <th>
                @lang('orders::orders.form.quantity')
            </th>

            <th>
                @lang('orders::orders.form.line_total')
            </th>
            <th>

            </th>
            </thead>
            <tbody>


            @foreach($options['children'] as $row)
                <tr class="order_row">

                    <td>
                        {!! form_row($row->id) !!}
                        <div class="input-group">
                        <span class="input-group-addon">
                           <i class="search-product material-icons">search</i>
                        </span>
                            {!! form_row($row->product_name) !!}

                        </div>

                    </td>
                    <td>
                        {!! form_row($row->price) !!}
                    </td>
                    <td>
                        {!! form_row($row->quantity) !!}
                    </td>
                    <td>
                        {!! form_row($row->lineTotal) !!}
                    </td>
                    <td>
                        <i class="material-icons order-remove-row pointer">clear</i>
                    </td>
                </tr>

            @endforeach

            <tr>
                <td colspan="2" rowspan="7">

                </td>
                <td>
                    <label class="show-control-label text-right">
                        @lang('orders::orders.form.subtotal')
                    </label>
                </td>
                <td class="summary_subtotal">

                </td>
            </tr>
            <tr>
                <td>
                    <label class="show-control-label text-right">
                        @lang('orders::orders.form.discount')
                    </label>
                </td>
                <td class="summary_discount">

                </td>
            </tr>
            <tr>
                <td>
                    <label class="show-control-label text-right">
                        @lang('orders::orders.form.delivery_cost')
                    </label>
                </td>
                <td class="summary_delivery_cost">

                </td>
            </tr>
            <tr>
                <td>
                    <label class="show-control-label text-right">
                        @lang('orders::orders.form.tax')
                    </label>
                </td>
                <td class="summary_tax">

                </td>
            </tr>
            <tr>
                <td>
                    <label class="show-control-label text-right">
                        @lang('orders::orders.form.gross_value')
                    </label>
                </td>
                <td class="summary_gross">

                </td>
            </tr>
            <tr>
                <td>
                    <label class="show-control-label text-right">
                        @lang('orders::orders.form.paid_to_date')
                    </label>
                </td>
                <td class="summary_paid_to_date">

                </td>
            </tr>
            <tr>
                <td>
                    <label class="show-control-label text-right">
                        @lang('orders::orders.form.balance_due')
                    </label>
                </td>
                <td class="summary_balance_due bold">

                </td>
            </tr>

            </tbody>

        </table>
    </div>

</div>
