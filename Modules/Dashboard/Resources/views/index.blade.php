@extends('layouts.app')

@section('content')

    <div class="row">
        @widget('Modules\Dashboard\Widgets\CountWidget',['title' =>
        trans('dashboard::dashboard.widgets.leads'),'bg_color'=>'bg-pink','icon'=>'search','counter' =>
        $countLeads])
        @widget('Modules\Dashboard\Widgets\CountWidget',['title' =>
        trans('dashboard::dashboard.widgets.contacts'),'bg_color'=>'bg-cyan','icon'=>'contacts','counter' =>
        $countContacts])
        @widget('Modules\Dashboard\Widgets\CountWidget',['title' =>
        trans('dashboard::dashboard.widgets.orders'),'bg_color'=>'bg-orange','icon'=>'pageview','counter' =>
        $countOrders])
        @widget('Modules\Dashboard\Widgets\CountWidget',['title' =>
        trans('dashboard::dashboard.widgets.invoices'),'bg_color'=>'bg-green','icon'=>'shopping_cart','counter' =>
        $countInvoices])
    </div>

    <div class="row dashboard-row">

        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
            <div class="card">
                <div class="header">
                    <span class="badge bg-pink pull-right">@lang('core::core.this_month')</span>
                    <h2>@lang('dashboard::dashboard.widgets.leads_chart')</h2>

                </div>
                <div class="body">
                    <div id="leads_chart" class="dashboard-leads_chart" style="height: 230px">
                        {!! $leadOverview->container() !!}
                    </div>


                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
            <div class="card">
                <div class="header">

                    <span class="badge bg-pink pull-right">@lang('core::core.last_three_months')</span>
                    <span class="badge bg-pink pull-right m-r-5">@lang('core::core.dict.usd')</span>
                    <h2>@lang('dashboard::dashboard.widgets.income_vs_expenses')</h2>
                </div>
                <div class="body" style="text-align: center">
                    <div id="income_chart" class="dashboard-income_chartt" style="text-align: center; height: 230px">
                        {!! $incomeVsExpense->container() !!}
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row dashboard-row">


        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
            <div class="card">
                <div class="header">
                    <h2>@lang('dashboard::dashboard.widgets.tickets')</h2>
                </div>
                <div class="body">
                    <div class="table-responsive col-lg-12 col-md-12 col-sm-12">
                        {{ $ticketDatatable->table(['width' => '100%']) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <div class="card">
                <div class="header">

                    <span class="badge bg-red pull-right">@lang('core::core.this_month')</span>
                    <h2>@lang('dashboard::dashboard.widgets.tickets_overview')</h2>
                </div>
                <div class="body">
                    <h5>@lang('dashboard::dashboard.widgets.tickets_by_status')</h5>
                    <div style="text-align: center; height: 253px">
                        {!! $ticketStatusOverview->container() !!}
                    </div>
                    <br /><br />
                    <h5>@lang('dashboard::dashboard.widgets.tickets_by_priority')</h5>
                    <div style="text-align: center; height: 253px">
                        {!! $ticketPriorityOverview->container() !!}
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection


@push('css-up')

@endpush
@push('scripts')


@push('scripts')

    <script type="text/javascript" src="{{ asset('bap/plugins/chartjs/Chart.bundle.js')}}"></script>
    <script src="{!! Module::asset('dashboard:js/BAP_Dashboard.js') !!}"></script>

    {!! $leadOverview->script() !!}
    {!! $incomeVsExpense->script() !!}
    {!! $ticketStatusOverview->script() !!}
    {!! $ticketPriorityOverview->script() !!}

@endpush

@push('scripts')
{!! $ticketDatatable->scripts() !!}
@endpush


