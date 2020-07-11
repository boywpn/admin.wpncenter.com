<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h2 class="card-inside-title">@lang('orders::orders.panel.products_and_services')</h2>
</div>


<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="table-responsive  col-lg-12 col-md-12 col-sm-12">
        <table class="table  table-condensed">
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
            </thead>
            <tbody>

            @foreach($entity->rows as $row)
                <tr>
                    <td>
                        {{ $row->product_name }}
                    </td>
                    <td>
                        {{ number_format($row->price,2) }}
                        @if(!empty($entity->currency))
                            {{ $entity->currency->code }}
                        @endif
                    </td>
                    <td>
                        {{ $row->quantity }}
                    </td>
                    <td>
                        {{ number_format($row->lineTotal,2) }}
                        @if(!empty($entity->currency))
                            {{ $entity->currency->code }}
                        @endif
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
                <td>
                    {{ number_format($entity->subtotal,2) }}
                    @if(!empty($entity->currency))
                        {{ $entity->currency->code }}
                    @endif
                </td>
            </tr>
            <tr>
                <td>
                    <label class="show-control-label text-right">
                        @lang('orders::orders.form.discount')
                    </label>
                </td>
                <td>
                    {{ number_format($entity->discount,2) }}
                    @if(!empty($entity->currency))
                        {{ $entity->currency->code }}
                    @endif
                </td>
            </tr>
            <tr>
                <td>
                    <label class="show-control-label text-right">
                        @lang('orders::orders.form.delivery_cost')
                    </label>
                </td>
                <td>
                    {{ number_format($entity->delivery_cost,2) }}
                    @if(!empty($entity->currency))
                        {{ $entity->currency->code }}
                    @endif
                </td>
            </tr>
            <tr>
                <td>
                    <label class="show-control-label text-right">
                        @lang('orders::orders.form.tax')
                    </label>
                </td>
                <td>
                    {{ number_format($entity->taxValue,2) }}
                    @if(!empty($entity->currency))
                        {{ $entity->currency->code }}
                    @endif
                </td>
            </tr>
            <tr>
                <td>
                    <label class="show-control-label text-right">
                        @lang('orders::orders.form.gross_value')
                    </label>
                </td>
                <td>
                    <strong>
                        {{ number_format($entity->grossValue,2) }}
                        @if(!empty($entity->currency))
                            {{ $entity->currency->code }}
                        @endif
                    </strong>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="show-control-label text-right">
                        @lang('orders::orders.form.paid_to_date')
                    </label>
                </td>
                <td>
                    <strong>
                        {{ number_format($entity->paid,2) }}
                        @if(!empty($entity->currency))
                            {{ $entity->currency->code }}
                        @endif
                    </strong>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="show-control-label text-right">
                        @lang('orders::orders.form.balance_due')
                    </label>
                </td>
                <td>
                    <strong>
                        {{ number_format($entity->balanceDue,2) }}
                        @if(!empty($entity->currency))
                            {{ $entity->currency->code }}
                        @endif
                    </strong>
                </td>
            </tr>

            </tbody>

        </table>
    </div>
</div>
