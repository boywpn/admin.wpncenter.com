<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $entity->invoice_number }}</title>

    <!-- Invoice styling -->
    <style>
        table, tr, td, th, tbody, thead, tfoot {
            page-break-inside: avoid !important;
        }

        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #777;
        }

        body h1 {
            font-weight: 300;
            margin-bottom: 0px;
            padding-bottom: 0px;
            color: #000;
        }

        body h3 {
            font-weight: 300;
            margin-top: 10px;
            margin-bottom: 20px;
            font-style: italic;
            color: #555;
        }

        body a {
            color: #06F;
        }

        .invoice-box {

            margin: auto;


            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 10px;
        }

        .invoice-box table tr.top table td.title .logo {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .title .logo img {
            max-width: 300px;
            max-height: 300px;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            padding-left: 10px;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;

        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
            text-align: left;
            padding-left: 10px;

        }

        .invoice-box table tr.summary td {
            border-bottom: 1px solid #eee;
            text-align: left;
            padding-left: 10px;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .text-right {
            text-align: right !important;
        }

    </style>
</head>

<body>

<div class="invoice-box">


    <table cellpadding="0" cellspacing="0">
        <tr class="top">
            <td colspan="4">
                <table>
                    <tr>
                        <td class="title">
                            <div class="logo">
                                @if(\Modules\Platform\Core\Helper\CompanySettings::get(\Modules\Platform\Core\Helper\SettingsHelper::S_DISPLAY_SHOW_LOGO_IN_PDF))
                                    {!!   \Modules\Platform\Core\Helper\SettingsHelper::pdfLogoPath() !!}
                                @else
                                    {{ \Modules\Platform\Core\Helper\CompanySettings::get(\Modules\Platform\Core\Helper\SettingsHelper::S_COMPANY_NAME, config('app.name'))}}
                                @endif
                            </div>

                        </td>
                        <td class="text-right">
                            @lang('invoices::invoices.pdf.invoice_number') #: {{ $entity->invoice_number }}<br>
                            @lang('invoices::invoices.pdf.invoice_date'): {{ \Modules\Platform\Core\Helper\UserHelper::formatUserDate($entity->invoice_date) }}<br>
                            @lang('invoices::invoices.pdf.due_date'): {{ \Modules\Platform\Core\Helper\UserHelper::formatUserDate($entity->due_date) }}<br>
                            <br/>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>

        <tr class="information">
            <td colspan="4">
                <table>
                    <tr>
                        <td colspan="2">
                            <h2>@lang('invoices::invoices.pdf.company')</h2>
                            {{ $entity->from_company }} <br/>
                            @if(!empty($entity->from_tax_number))
                                @lang('invoices::invoices.pdf.tax_number'): {{ $entity->from_tax_number }} <br/>
                            @endif
                            {{ $entity->from_street }}, {{ $entity->from_city }}, {{ $entity->from_zip_code }}, <br/>
                            {{ $entity->from_state }} {{$entity->from_country }} <br/>

                        </td>

                    </tr>
                    <tr>
                        <td>
                            <h2>@lang('invoices::invoices.pdf.bill_to')</h2>
                            {{ $entity->bill_to }} <br/>
                            @if(!empty($entity->bill_tax_number))
                                @lang('invoices::invoices.pdf.tax_number'): {{ $entity->bill_tax_number }} <br/>
                            @endif
                            {{ $entity->bill_street }}, {{ $entity->bill_city }}, {{ $entity->bill_zip_code }}, <br/>
                            {{ $entity->bill_state }} {{$entity->bill_country }} <br/>

                        </td>

                        <td >
                            <h2>@lang('invoices::invoices.pdf.ship_to')</h2>
                            {{ $entity->ship_to }} <br/>
                            @if(!empty($entity->ship_tax_number))
                                @lang('invoices::invoices.pdf.tax_number'): {{ $entity->ship_tax_number }} <br/>
                            @endif
                            {{ $entity->ship_street }}, {{ $entity->ship_city }}, {{ $entity->ship_zip_code }}, <br/>
                            {{ $entity->bill_state }} {{$entity->ship_country }} <br/>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="heading">
            <td>
                @lang('invoices::invoices.pdf.product_service')
            </td>

            <td>
                @lang('invoices::invoices.pdf.unit_cost')
            </td>
            <td>
                @lang('invoices::invoices.pdf.quantity')
            </td>
            <td>
                @lang('invoices::invoices.pdf.line_total')
            </td>
        </tr>

        @foreach($entity->rows as $row)
            <tr class="item">
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
        <tr class="summary">
            <td colspan="2" rowspan="7">
            </td>
            <td>


                @lang('invoices::invoices.pdf.subtotal')

            </td>
            <td>
                {{ number_format($entity->subtotal,2) }}
                @if(!empty($entity->currency))
                    {{ $entity->currency->code }}
                @endif
            </td>
        </tr>
        <tr class="summary">
            <td>
                <label class=" text-right">
                    @lang('invoices::invoices.pdf.discount')
                </label>
            </td>
            <td>
                {{ number_format($entity->discount,2) }}
                @if(!empty($entity->currency))
                    {{ $entity->currency->code }}
                @endif
            </td>
        </tr>
        <tr class="summary">
            <td>
                <label class=" text-right">
                    @lang('invoices::invoices.pdf.delivery_cost')
                </label>
            </td>
            <td>
                {{ number_format($entity->delivery_cost,2) }}
                @if(!empty($entity->currency))
                    {{ $entity->currency->code }}
                @endif
            </td>
        </tr>
        <tr class="summary">
            <td>
                <label class=" text-right">
                    @lang('invoices::invoices.pdf.tax')
                    @if(!empty($entity->tax))
                        ({{ $entity->tax->name }})
                    @endif
                </label>
            </td>
            <td>
                {{ number_format($entity->taxValue,2) }}
                @if(!empty($entity->currency))
                    {{ $entity->currency->code }}
                @endif
            </td>
        </tr>
        <tr class="summary">
            <td>
                <label class=" text-right">
                    @lang('invoices::invoices.pdf.gross_value')
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


    </table>
    @if($entity->shipping)
        <br/><br/>
        <table cellpadding="0" cellspacing="0">
            <tr class="heading">
                <td>
                    @lang('invoices::invoices.pdf.shipping')
                </td>
            </tr>
            <tr class="item">
                <td>
                    @if(!empty($entity->quoteCarrier))
                        @lang('invoices::invoices.pdf.carrier'): {{$entity->quoteCarrier->name}}  <br/>
                    @endif
                    {{ $entity->shipping }}
                </td>
            </tr>
        </table>
    @endif
    @if($entity->terms_and_cond)
        <br/><br/>
        <table cellpadding="0" cellspacing="0">
            <tr class="heading">
                <td>
                    @lang('invoices::invoices.pdf.terms_and_cond')
                </td>
            </tr>
            <tr class="item">
                <td>
                    {{ $entity->terms_and_cond }}
                </td>
            </tr>
        </table>
    @endif
    @if($entity->notes)
        <br/><br/>
        <table cellpadding="0" cellspacing="0">
            <tr class="heading">
                <td>
                    @lang('invoices::invoices.pdf.notes')
                </td>
            </tr>
            <tr class="item">
                <td>
                    {{ $entity->notes }}
                </td>
            </tr>
        </table>
    @endif
</div>

</html>
