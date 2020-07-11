<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h2 class="card-inside-title">@lang('quotes::quotes.panel.products_and_services')</h2>
</div>


<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="table-responsive  col-lg-12 col-md-12 col-sm-12">
        <table class="table  table-condensed">
            <thead>
            <th>
                @lang('quotes::quotes.form.product_service')
            </th>
            <th>
                @lang('quotes::quotes.form.unit_cost')
            </th>
            <th>
                @lang('quotes::quotes.form.quantity')
            </th>

            <th>
                @lang('quotes::quotes.form.line_total')
            </th>
            </thead>
            <tbody>

            @foreach($entity->rows as $row)
                <tr>
                    <td>

                        {{ $row->product_name }}


                        @if(config('quotes.show_product_image') && !empty($row->product))
                            <br />
                            <img src="{{ asset($row->product->image_path) }}" class="image_path_size" />
                        @endif
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
                        @lang('quotes::quotes.form.subtotal')
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
                        @lang('quotes::quotes.form.discount')
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
                        @lang('quotes::quotes.form.delivery_cost')
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
                        @lang('quotes::quotes.form.tax')
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
                        @lang('quotes::quotes.form.gross_value')
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

            </tbody>

        </table>
    </div>

</div>
