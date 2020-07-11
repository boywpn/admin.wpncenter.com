<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h2 class="card-inside-title">@lang('invoices::invoices.panel.products_and_services')</h2>
</div>


<div class="col-lg-12 col-md-12 col-xs-12">
    <div class="table-responsive  col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <table class="table  table-condensed">
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
            </thead>
            <tbody>

            @foreach($entity->rows as $row)
                <tr>
                    <td>
                        @if($row->price_list_id > 0 )
                            <i title="@lang('products::products.price_book')" class="fa fa-book"></i>
                        @endif
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
                        @lang('invoices::invoices.form.subtotal')
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
                        @lang('invoices::invoices.form.discount')
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
                        @lang('invoices::invoices.form.delivery_cost')
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
                        @lang('invoices::invoices.form.tax')
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
                        @lang('invoices::invoices.form.gross_value')
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
                        @lang('invoices::invoices.form.paid_to_date')
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
                        @lang('invoices::invoices.form.balance_due')
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
