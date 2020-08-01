@extends('report/winloss::index')

@section('custom_body')

    <div class="row">
        <div class="col-md-12">
            <form class="form-horizontal" action="{{ route($gameRouteSearch, $gameCode) }}" method="get">
                {{--@csrf--}}

                @php
                // print_r($formData);
                @endphp

                <input type="hidden" name="game" value="{{ $gameID }}">
                <input type="hidden" name="role" value="{{ (isset($request['role'])) ? $request['role'] : 'ss' }}">
                <input type="hidden" name="user" value="{{ (isset($request['user'])) ? $request['user'] : '' }}">
                <input type="hidden" name="id" value="{{ (isset($request['id'])) ? $request['id'] : '' }}">
                <input type="hidden" name="from_time" value="{{ (isset($request['from_time'])) ? $request['from_time'] : $formData['from_time'] }}">
                <input type="hidden" name="to_time" value="{{ (isset($request['to_time'])) ? $request['to_time'] : $formData['to_time'] }}">

                <div class="row clearfix">
                    <div class="col-md-1 form-control-label">
                        <label>{{ trans("report/winloss::winloss.from") }}</label>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" name="from" id="from" class="form-control datepicker" value="{{ (isset($request['from'])) ? $request['from'] : genDateFilter('0 day') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <div class="form-control-static">00:00:00</div>
                        </div>
                    </div>
                    <div class="col-md-1 form-control-label">
                        <label>{{ trans("report/winloss::winloss.to") }}</label>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" name="to" id="to" class="form-control datepicker" value="{{ (isset($request['to'])) ? $request['to'] : genDateFilter('0 day') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <div class="form-line">
                                <div class="form-control-static">23:59:59</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary waves-effect">{{ trans("report/winloss::winloss.search") }}</button>
                        <a href="javascript:history.back()" class="btn btn-danger waves-effect">{{ trans("report/winloss::winloss.back") }}</a>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-md-1 form-control-label">
                        <label>{{ trans("report/winloss::winloss.username") }}</label>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" name="username" id="username" class="form-control" value="{{ (isset($request['username'])) ? $request['username'] : "" }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-md-11 col-md-offset-1" style="padding-left: 0px">
                        <div class="game-type-check">
                            <input type="checkbox" id="type_all" name="type_all" class="filled-in chk-col-indigo checkbox-all" {{ (!empty($request)) ? ((isset($request['type_all'])) ? "checked" : "") : "checked" }} />
                            <label for="type_all">{{ trans("report/winloss::winloss.check_all") }}</label>
                            <div id="checkbox_type_all" style="display: inline;">
                                @foreach($gameType as $type)
                                    <input type="checkbox" id="type_{{ $type['id'] }}" name="type[]" value="{{ $type['id'] }}" class="filled-in chk-col-indigo" {{ (!empty($request)) ? ((isset($request['type'])) ? ((in_array($type['id'], $request['type'])) ? "checked" : "") : "checked") : "checked" }} />
                                    <label for="type_{{ $type['id'] }}">{{ $type['name'] }}</label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @php
        //print_r($queryString);
        //print_r($request);
        //print_r($formData);

        $menu = "";
        if($formData['role'] == "ag"){
            $queryString['role'] = 'ss';
            $queryString['user'] = '';
            $queryString['id'] = '';
            $alink = http_build_query($queryString);
            $menu .= '<li><a href="'.route($gameRoute, $gameCode)."?".$alink.'">WPN</a></li>';
            $menu .= '<li class="active">'.$request['user'].'</li>';
        }elseif($formData['role'] == "mm"){
            $menu .= '<li class="active">'.$request['user'].'</li>';
        }

    @endphp

    @if($formData['role'] == "ss" || $formData['role'] == "ag" || $formData['role'] == "mm")
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb breadcrumb-col-blue" style="font-size: 16px; margin-bottom: 0">
                {!! $menu !!}
            </ol>
        </div>
    </div>
    @endif

    @if($formData['role'] == "ss")
        <div class="row">
            <div class="col-md-12">
                <table id="winloss-table" class="table-bordered table-hover table-striped" cellspacing="1" cellpadding="3">
                    <thead>
                        <tr>
                            <th rowspan="2">{{ trans("report/winloss::winloss.table.account") }}</th>
                            <th rowspan="2">{{ trans("report/winloss::winloss.table.contact") }}</th>
                            <th rowspan="2">{{ trans("report/winloss::winloss.table.turnover") }}</th>
                            <th rowspan="2">{{ trans("report/winloss::winloss.table.valid_amount") }}</th>
                            <th rowspan="2">{{ trans("report/winloss::winloss.table.stake_count") }}</th>

                            <th colspan="3">{{ trans("report/winloss::winloss.table.member") }}</th>
                            <th colspan="4">{{ trans("report/winloss::winloss.table.agent") }}</th>
                            <th colspan="4">{{ trans("report/winloss::winloss.table.super") }}</th>
                            <th colspan="4">{{ trans("report/winloss::winloss.table.company") }}</th>
                        </tr>
                        <tr>
                            <th>{{ trans("report/winloss::winloss.table.w_l") }}</th>
                            <th>{{ trans("report/winloss::winloss.table.com") }}</th>
                            <th>{{ trans("report/winloss::winloss.table.total") }}</th>

                            <th>{{ trans("report/winloss::winloss.table.v_amount") }}</th>
                            <th>{{ trans("report/winloss::winloss.table.w_l") }}</th>
                            <th>{{ trans("report/winloss::winloss.table.com") }}</th>
                            <th>{{ trans("report/winloss::winloss.table.total") }}</th>

                            <th>{{ trans("report/winloss::winloss.table.v_amount") }}</th>
                            <th>{{ trans("report/winloss::winloss.table.w_l") }}</th>
                            <th>{{ trans("report/winloss::winloss.table.com") }}</th>
                            <th>{{ trans("report/winloss::winloss.table.total") }}</th>

                            <th>{{ trans("report/winloss::winloss.table.v_amount") }}</th>
                            <th>{{ trans("report/winloss::winloss.table.w_l") }}</th>
                            <th>{{ trans("report/winloss::winloss.table.com") }}</th>
                            <th>{{ trans("report/winloss::winloss.table.total") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total_turnover = 0;
                            $total_valid_amount = 0;
                            $total_stake_count = 0;

                            $total_member_winloss = 0;
                            $total_member_comm = 0;
                            $total_member_total = 0;

                            $total_agent_amount = 0;
                            $total_agent_winloss = 0;
                            $total_agent_comm = 0;
                            $total_agent_total = 0;

                            $total_super_amount = 0;
                            $total_super_winloss = 0;
                            $total_super_comm = 0;
                            $total_super_total = 0;

                            $total_company_amount = 0;
                            $total_company_winloss = 0;
                            $total_company_comm = 0;
                            $total_company_total = 0;

                            $partner_id = 0;
                            $partner_id_chg = 0;
                        @endphp

                        @if(count($dataWinloss) > 0)
                            @foreach($dataWinloss as $k => $list)

                                @php
                                    if($list->partner_id != $partner_id){
                                        $pn = \Modules\Core\Partners\Entities\Partners::findOrFail($list->partner_id);
                                    }
                                    $total_turnover_ss[$list->partner_id][$k] = $list->turnover;
                                    $total_valid_amount_ss[$list->partner_id][$k] = $list->valid_amount;
                                    $total_stake_count_ss[$list->partner_id][$k] = $list->stake_count;

                                    $total_member_winloss_ss[$list->partner_id][$k] = $list->member_winloss;
                                    $total_member_comm_ss[$list->partner_id][$k] = $list->member_comm;
                                    $total_member_total_ss[$list->partner_id][$k] = $list->member_total;

                                    $total_agent_amount_ss[$list->partner_id][$k] = $list->agent_amount;
                                    $total_agent_winloss_ss[$list->partner_id][$k] = $list->agent_winloss;
                                    $total_agent_comm_ss[$list->partner_id][$k] = $list->agent_comm;
                                    $total_agent_total_ss[$list->partner_id][$k] = $list->agent_total;

                                    $total_super_amount_ss[$list->partner_id][$k] = $list->super_amount;
                                    $total_super_winloss_ss[$list->partner_id][$k] = $list->super_winloss;
                                    $total_super_comm_ss[$list->partner_id][$k] = $list->super_comm;
                                    $total_super_total_ss[$list->partner_id][$k] = $list->super_total;

                                    $total_company_amount_ss[$list->partner_id][$k] = $list->company_amount;
                                    $total_company_winloss_ss[$list->partner_id][$k] = $list->company_winloss;
                                    $total_company_comm_ss[$list->partner_id][$k] = $list->company_comm;
                                    $total_company_total_ss[$list->partner_id][$k] = $list->company_total;

                                @endphp

                                @if($list->partner_id != $partner_id)
                                    @if($partner_id != 0)
                                        <tr class="total-partner">
                                            <th colspan="2" class="text-right">Total:</th>
                                            <th class="text-right">{{ number_format(array_sum($total_turnover_ss[$partner_id]), 2) }}</th>
                                            <th class="text-right">{{ number_format(array_sum($total_valid_amount_ss[$partner_id]), 2) }}</th>
                                            <th class="text-right">{{ number_format(array_sum($total_stake_count_ss[$partner_id]), 2) }}</th>

                                            <th class="text-right {{ (array_sum($total_member_winloss_ss[$partner_id]) < 0) ? 'text-red' : '' }}">{{ number_format(array_sum($total_member_winloss_ss[$partner_id]), 2) }}</th>
                                            <th class="text-right {{ (array_sum($total_member_comm_ss[$partner_id]) < 0) ? 'text-red' : '' }}">{{ number_format(array_sum($total_member_comm_ss[$partner_id]), 2) }}</th>
                                            <th class="text-right font-bold {{ (array_sum($total_member_total_ss[$partner_id]) < 0) ? 'text-red' : '' }}">{{ number_format(array_sum($total_member_total_ss[$partner_id]), 2) }}</th>

                                            <th class="text-right {{ (array_sum($total_agent_amount_ss[$partner_id]) < 0) ? 'text-red' : '' }}">{{ number_format(array_sum($total_agent_amount_ss[$partner_id]), 2) }}</th>
                                            <th class="text-right {{ (array_sum($total_agent_winloss_ss[$partner_id]) < 0) ? 'text-red' : '' }}">{{ number_format(array_sum($total_agent_winloss_ss[$partner_id]), 2) }}</th>
                                            <th class="text-right {{ (array_sum($total_agent_comm_ss[$partner_id]) < 0) ? 'text-red' : '' }}">{{ number_format(array_sum($total_agent_comm_ss[$partner_id]), 2) }}</th>
                                            <th class="text-right font-bold {{ (array_sum($total_agent_total_ss[$partner_id]) < 0) ? 'text-red' : '' }}">{{ number_format(array_sum($total_agent_total_ss[$partner_id]), 2) }}</th>

                                            <th class="text-right {{ (array_sum($total_super_amount_ss[$partner_id]) < 0) ? 'text-red' : '' }}">{{ number_format(array_sum($total_super_amount_ss[$partner_id]), 2) }}</th>
                                            <th class="text-right {{ (array_sum($total_super_winloss_ss[$partner_id]) < 0) ? 'text-red' : '' }}">{{ number_format(array_sum($total_super_winloss_ss[$partner_id]), 2) }}</th>
                                            <th class="text-right {{ (array_sum($total_super_comm_ss[$partner_id]) < 0) ? 'text-red' : '' }}">{{ number_format(array_sum($total_super_comm_ss[$partner_id]), 2) }}</th>
                                            <th class="text-right font-bold {{ (array_sum($total_super_total_ss[$partner_id]) < 0) ? 'text-red' : '' }}">{{ number_format(array_sum($total_super_total_ss[$partner_id]), 2) }}</th>

                                            <th class="text-right {{ (array_sum($total_company_amount_ss[$partner_id]) < 0) ? 'text-red' : '' }}">{{ number_format(array_sum($total_company_amount_ss[$partner_id]), 2) }}</th>
                                            <th class="text-right {{ (array_sum($total_company_winloss_ss[$partner_id]) < 0) ? 'text-red' : '' }}">{{ number_format(array_sum($total_company_winloss_ss[$partner_id]), 2) }}</th>
                                            <th class="text-right {{ (array_sum($total_company_comm_ss[$partner_id]) < 0) ? 'text-red' : '' }}">{{ number_format(array_sum($total_company_comm_ss[$partner_id]), 2) }}</th>
                                            <th class="text-right font-bold {{ (array_sum($total_company_total_ss[$partner_id]) < 0) ? 'text-red' : '' }}">{{ number_format(array_sum($total_company_total_ss[$partner_id]), 2) }}</th>
                                        </tr>
                                        <tr>
                                            <td colspan="20"></td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <th class="head-partner" colspan="20">{{ $pn->name }}</th>
                                    </tr>
                                @endif

                                @php
                                    $total_turnover += $list->turnover;
                                    $total_valid_amount += $list->valid_amount;
                                    $total_stake_count += $list->stake_count;

                                    $total_member_winloss += $list->member_winloss;
                                    $total_member_comm += $list->member_comm;
                                    $total_member_total += $list->member_total;

                                    $total_agent_amount += $list->agent_amount;
                                    $total_agent_winloss += $list->agent_winloss;
                                    $total_agent_comm += $list->agent_comm;
                                    $total_agent_total += $list->agent_total;

                                    $total_super_amount += $list->super_amount;
                                    $total_super_winloss += $list->super_winloss;
                                    $total_super_comm += $list->super_comm;
                                    $total_super_total += $list->super_total;

                                    $total_company_amount += $list->company_amount;
                                    $total_company_winloss += $list->company_winloss;
                                    $total_company_comm += $list->company_comm;
                                    $total_company_total += $list->company_total;

                                    $username = $list->ref;
                                    $id = $list->agent_id;
                                    $contact = $list->name;
                                @endphp

                                <tr>
                                    <td><a href="{{ route($gameRoute, $gameCode) }}?role=ag&user={{ $username }}&id={{ $id }}&{{ $urlParam }}">{{ $username }}</a></td>
                                    <td>{{ $contact }}</td>
                                    <td class="text-right">{{ number_format($list->turnover, 2) }}</td>
                                    <td class="text-right">{{ number_format($list->valid_amount, 2) }}</td>
                                    <td class="text-right">{{ number_format($list->stake_count, 2) }}</td>

                                    <td class="text-right {{ ($list->member_winloss < 0) ? 'text-red' : '' }}">{{ number_format($list->member_winloss, 2) }}</td>
                                    <td class="text-right {{ ($list->member_comm < 0) ? 'text-red' : '' }}">{{ number_format($list->member_comm, 2) }}</td>
                                    <td class="text-right font-bold {{ ($list->member_total < 0) ? 'text-red' : '' }}">{{ number_format($list->member_total, 2) }}</td>

                                    <td class="text-right {{ ($list->agent_amount < 0) ? 'text-red' : '' }}">{{ number_format($list->agent_amount, 2) }}</td>
                                    <td class="text-right {{ ($list->agent_winloss < 0) ? 'text-red' : '' }}">{{ number_format($list->agent_winloss, 2) }}</td>
                                    <td class="text-right {{ ($list->agent_comm < 0) ? 'text-red' : '' }}">{{ number_format($list->agent_comm, 2) }}</td>
                                    <td class="text-right font-bold {{ ($list->agent_total < 0) ? 'text-red' : '' }}">{{ number_format($list->agent_total, 2) }}</td>

                                    <td class="text-right {{ ($list->super_amount < 0) ? 'text-red' : '' }}">{{ number_format($list->super_amount, 2) }}</td>
                                    <td class="text-right {{ ($list->super_winloss < 0) ? 'text-red' : '' }}">{{ number_format($list->super_winloss, 2) }}</td>
                                    <td class="text-right {{ ($list->super_comm < 0) ? 'text-red' : '' }}">{{ number_format($list->super_comm, 2) }}</td>
                                    <td class="text-right font-bold {{ ($list->super_total < 0) ? 'text-red' : '' }}">{{ number_format($list->super_total, 2) }}</td>

                                    <td class="text-right {{ ($list->company_amount < 0) ? 'text-red' : '' }}">{{ number_format($list->company_amount, 2) }}</td>
                                    <td class="text-right {{ ($list->company_winloss < 0) ? 'text-red' : '' }}">{{ number_format($list->company_winloss, 2) }}</td>
                                    <td class="text-right {{ ($list->company_comm < 0) ? 'text-red' : '' }}">{{ number_format($list->company_comm, 2) }}</td>
                                    <td class="text-right font-bold {{ ($list->company_total < 0) ? 'text-red' : '' }}">{{ number_format($list->company_total, 2) }}</td>
                                </tr>

                                @php
                                    $partner_id = $list->partner_id;
                                @endphp

                            @endforeach

                            <tr class="total-partner">
                                <th colspan="2" class="text-right">Total:</th>
                                <th class="text-right">{{ number_format(array_sum($total_turnover_ss[$partner_id]), 2) }}</th>
                                <th class="text-right">{{ number_format(array_sum($total_valid_amount_ss[$partner_id]), 2) }}</th>
                                <th class="text-right">{{ number_format(array_sum($total_stake_count_ss[$partner_id]), 2) }}</th>

                                <th class="text-right {{ (array_sum($total_member_winloss_ss[$partner_id]) < 0) ? 'text-red' : '' }}">{{ number_format(array_sum($total_member_winloss_ss[$partner_id]), 2) }}</th>
                                <th class="text-right {{ (array_sum($total_member_comm_ss[$partner_id]) < 0) ? 'text-red' : '' }}">{{ number_format(array_sum($total_member_comm_ss[$partner_id]), 2) }}</th>
                                <th class="text-right font-bold {{ (array_sum($total_member_total_ss[$partner_id]) < 0) ? 'text-red' : '' }}">{{ number_format(array_sum($total_member_total_ss[$partner_id]), 2) }}</th>

                                <th class="text-right {{ (array_sum($total_agent_amount_ss[$partner_id]) < 0) ? 'text-red' : '' }}">{{ number_format(array_sum($total_agent_amount_ss[$partner_id]), 2) }}</th>
                                <th class="text-right {{ (array_sum($total_agent_winloss_ss[$partner_id]) < 0) ? 'text-red' : '' }}">{{ number_format(array_sum($total_agent_winloss_ss[$partner_id]), 2) }}</th>
                                <th class="text-right {{ (array_sum($total_agent_comm_ss[$partner_id]) < 0) ? 'text-red' : '' }}">{{ number_format(array_sum($total_agent_comm_ss[$partner_id]), 2) }}</th>
                                <th class="text-right font-bold {{ (array_sum($total_agent_total_ss[$partner_id]) < 0) ? 'text-red' : '' }}">{{ number_format(array_sum($total_agent_total_ss[$partner_id]), 2) }}</th>

                                <th class="text-right {{ (array_sum($total_super_amount_ss[$partner_id]) < 0) ? 'text-red' : '' }}">{{ number_format(array_sum($total_super_amount_ss[$partner_id]), 2) }}</th>
                                <th class="text-right {{ (array_sum($total_super_winloss_ss[$partner_id]) < 0) ? 'text-red' : '' }}">{{ number_format(array_sum($total_super_winloss_ss[$partner_id]), 2) }}</th>
                                <th class="text-right {{ (array_sum($total_super_comm_ss[$partner_id]) < 0) ? 'text-red' : '' }}">{{ number_format(array_sum($total_super_comm_ss[$partner_id]), 2) }}</th>
                                <th class="text-right font-bold {{ (array_sum($total_super_total_ss[$partner_id]) < 0) ? 'text-red' : '' }}">{{ number_format(array_sum($total_super_total_ss[$partner_id]), 2) }}</th>

                                <th class="text-right {{ (array_sum($total_company_amount_ss[$partner_id]) < 0) ? 'text-red' : '' }}">{{ number_format(array_sum($total_company_amount_ss[$partner_id]), 2) }}</th>
                                <th class="text-right {{ (array_sum($total_company_winloss_ss[$partner_id]) < 0) ? 'text-red' : '' }}">{{ number_format(array_sum($total_company_winloss_ss[$partner_id]), 2) }}</th>
                                <th class="text-right {{ (array_sum($total_company_comm_ss[$partner_id]) < 0) ? 'text-red' : '' }}">{{ number_format(array_sum($total_company_comm_ss[$partner_id]), 2) }}</th>
                                <th class="text-right font-bold {{ (array_sum($total_company_total_ss[$partner_id]) < 0) ? 'text-red' : '' }}">{{ number_format(array_sum($total_company_total_ss[$partner_id]), 2) }}</th>
                            </tr>

                        @else
                            <tr>
                                <td colspan="20">{{ trans("report/winloss::winloss.no_record") }}</td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="20"></td>
                        </tr>
                        <tr>
                            <th colspan="2" class="text-right">{{ trans("report/winloss::winloss.table.total") }}</th>
                            <th class="text-right">{{ number_format($total_turnover, 2) }}</th>
                            <th class="text-right">{{ number_format($total_valid_amount, 2) }}</th>
                            <th class="text-right">{{ number_format($total_stake_count, 2) }}</th>

                            <th class="text-right {{ ($total_member_winloss < 0) ? 'text-red' : '' }}">{{ number_format($total_member_winloss, 2) }}</th>
                            <th class="text-right {{ ($total_member_comm < 0) ? 'text-red' : '' }}">{{ number_format($total_member_comm, 2) }}</th>
                            <th class="text-right font-bold {{ ($total_member_total < 0) ? 'text-red' : '' }}">{{ number_format($total_member_total, 2) }}</th>

                            <th class="text-right {{ ($total_agent_amount < 0) ? 'text-red' : '' }}">{{ number_format($total_agent_amount, 2) }}</th>
                            <th class="text-right {{ ($total_agent_winloss < 0) ? 'text-red' : '' }}">{{ number_format($total_agent_winloss, 2) }}</th>
                            <th class="text-right {{ ($total_agent_comm < 0) ? 'text-red' : '' }}">{{ number_format($total_agent_comm, 2) }}</th>
                            <th class="text-right font-bold {{ ($total_agent_total < 0) ? 'text-red' : '' }}">{{ number_format($total_agent_total, 2) }}</th>

                            <th class="text-right {{ ($total_super_amount < 0) ? 'text-red' : '' }}">{{ number_format($total_super_amount, 2) }}</th>
                            <th class="text-right {{ ($total_super_winloss < 0) ? 'text-red' : '' }}">{{ number_format($total_super_winloss, 2) }}</th>
                            <th class="text-right {{ ($total_super_comm < 0) ? 'text-red' : '' }}">{{ number_format($total_super_comm, 2) }}</th>
                            <th class="text-right font-bold {{ ($total_super_total < 0) ? 'text-red' : '' }}">{{ number_format($total_super_total, 2) }}</th>

                            <th class="text-right {{ ($total_company_amount < 0) ? 'text-red' : '' }}">{{ number_format($total_company_amount, 2) }}</th>
                            <th class="text-right {{ ($total_company_winloss < 0) ? 'text-red' : '' }}">{{ number_format($total_company_winloss, 2) }}</th>
                            <th class="text-right {{ ($total_company_comm < 0) ? 'text-red' : '' }}">{{ number_format($total_company_comm, 2) }}</th>
                            <th class="text-right font-bold {{ ($total_company_total < 0) ? 'text-red' : '' }}">{{ number_format($total_company_total, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>

            </div>
        </div>
    @elseif($formData['role'] == "ag")
        <div class="row">
            <div class="col-md-12">
                <table id="winloss-table" class="table-bordered table-hover table-striped" cellspacing="1" cellpadding="3">
                    <thead>
                    <tr>
                        <th rowspan="2">{{ trans("report/winloss::winloss.table.account") }}</th>
                        <th rowspan="2">{{ trans("report/winloss::winloss.table.contact") }}</th>
                        <th rowspan="2">{{ trans("report/winloss::winloss.table.turnover") }}</th>
                        <th rowspan="2">{{ trans("report/winloss::winloss.table.valid_amount") }}</th>
                        {{--<th rowspan="2">{{ trans("report/winloss::winloss.table.member_count") }}</th>--}}
                        <th rowspan="2">{{ trans("report/winloss::winloss.table.stake_count") }}</th>
                        {{--<th rowspan="2">{{ trans("report/winloss::winloss.table.cross_comm") }}</th>--}}

                        <th colspan="3">{{ trans("report/winloss::winloss.table.member") }}</th>
                        <th colspan="4">{{ trans("report/winloss::winloss.table.agent") }}</th>
                        <th colspan="4">{{ trans("report/winloss::winloss.table.super") }}</th>
                        <th colspan="4">{{ trans("report/winloss::winloss.table.company") }}</th>
                    </tr>
                    <tr>
                        <th>{{ trans("report/winloss::winloss.table.w_l") }}</th>
                        <th>{{ trans("report/winloss::winloss.table.com") }}</th>
                        <th>{{ trans("report/winloss::winloss.table.total") }}</th>

                        <th>{{ trans("report/winloss::winloss.table.v_amount") }}</th>
                        <th>{{ trans("report/winloss::winloss.table.w_l") }}</th>
                        <th>{{ trans("report/winloss::winloss.table.com") }}</th>
                        <th>{{ trans("report/winloss::winloss.table.total") }}</th>

                        <th>{{ trans("report/winloss::winloss.table.v_amount") }}</th>
                        <th>{{ trans("report/winloss::winloss.table.w_l") }}</th>
                        <th>{{ trans("report/winloss::winloss.table.com") }}</th>
                        <th>{{ trans("report/winloss::winloss.table.total") }}</th>

                        <th>{{ trans("report/winloss::winloss.table.v_amount") }}</th>
                        <th>{{ trans("report/winloss::winloss.table.w_l") }}</th>
                        <th>{{ trans("report/winloss::winloss.table.com") }}</th>
                        <th>{{ trans("report/winloss::winloss.table.total") }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $total_turnover = 0;
                        $total_valid_amount = 0;
                        $total_stake_count = 0;

                        $total_member_winloss = 0;
                        $total_member_comm = 0;
                        $total_member_total = 0;

                        $total_agent_amount = 0;
                        $total_agent_winloss = 0;
                        $total_agent_comm = 0;
                        $total_agent_total = 0;

                        $total_super_amount = 0;
                        $total_super_winloss = 0;
                        $total_super_comm = 0;
                        $total_super_total = 0;

                        $total_company_amount = 0;
                        $total_company_winloss = 0;
                        $total_company_comm = 0;
                        $total_company_total = 0;
                    @endphp

                    @if(count($dataWinloss) > 0)
                        @foreach($dataWinloss as $list)

                            @php
                                $total_turnover += $list->turnover;
                                $total_valid_amount += $list->valid_amount;
                                $total_stake_count += $list->stake_count;

                                $total_member_winloss += $list->member_winloss;
                                $total_member_comm += $list->member_comm;
                                $total_member_total += $list->member_total;

                                $total_agent_amount += $list->agent_amount;
                                $total_agent_winloss += $list->agent_winloss;
                                $total_agent_comm += $list->agent_comm;
                                $total_agent_total += $list->agent_total;

                                $total_super_amount += $list->super_amount;
                                $total_super_winloss += $list->super_winloss;
                                $total_super_comm += $list->super_comm;
                                $total_super_total += $list->super_total;

                                $total_company_amount += $list->company_amount;
                                $total_company_winloss += $list->company_winloss;
                                $total_company_comm += $list->company_comm;
                                $total_company_total += $list->company_total;

                                $username = $list->ref;
                                $id = $list->agent_id;
                                $contact = $list->name;
                                $role = "mm";
                            @endphp

                            <tr>
                                <td><a href="{{ route($gameRoute, $gameCode) }}?role={{ $role }}&user={{ $username }}&id={{ $id }}&{{ $urlParam }}">{{ $username }}</a></td>
                                <td>{{ $contact }}</td>
                                <td class="text-right">{{ number_format($list->turnover, 2) }}</td>
                                <td class="text-right">{{ number_format($list->valid_amount, 2) }}</td>
                                <td class="text-right">{{ number_format($list->stake_count, 2) }}</td>

                                <td class="text-right {{ ($list->member_winloss < 0) ? 'text-red' : '' }}">{{ number_format($list->member_winloss, 2) }}</td>
                                <td class="text-right {{ ($list->member_comm < 0) ? 'text-red' : '' }}">{{ number_format($list->member_comm, 2) }}</td>
                                <td class="text-right font-bold {{ ($list->member_total < 0) ? 'text-red' : '' }}">{{ number_format($list->member_total, 2) }}</td>

                                <td class="text-right {{ ($list->agent_amount < 0) ? 'text-red' : '' }}">{{ number_format($list->agent_amount, 2) }}</td>
                                <td class="text-right {{ ($list->agent_winloss < 0) ? 'text-red' : '' }}">{{ number_format($list->agent_winloss, 2) }}</td>
                                <td class="text-right {{ ($list->agent_comm < 0) ? 'text-red' : '' }}">{{ number_format($list->agent_comm, 2) }}</td>
                                <td class="text-right font-bold {{ ($list->agent_total < 0) ? 'text-red' : '' }}">{{ number_format($list->agent_total, 2) }}</td>

                                <td class="text-right {{ ($list->super_amount < 0) ? 'text-red' : '' }}">{{ number_format($list->super_amount, 2) }}</td>
                                <td class="text-right {{ ($list->super_winloss < 0) ? 'text-red' : '' }}">{{ number_format($list->super_winloss, 2) }}</td>
                                <td class="text-right {{ ($list->super_comm < 0) ? 'text-red' : '' }}">{{ number_format($list->super_comm, 2) }}</td>
                                <td class="text-right font-bold {{ ($list->super_total < 0) ? 'text-red' : '' }}">{{ number_format($list->super_total, 2) }}</td>

                                <td class="text-right {{ ($list->company_amount < 0) ? 'text-red' : '' }}">{{ number_format($list->company_amount, 2) }}</td>
                                <td class="text-right {{ ($list->company_winloss < 0) ? 'text-red' : '' }}">{{ number_format($list->company_winloss, 2) }}</td>
                                <td class="text-right {{ ($list->company_comm < 0) ? 'text-red' : '' }}">{{ number_format($list->company_comm, 2) }}</td>
                                <td class="text-right font-bold {{ ($list->company_total < 0) ? 'text-red' : '' }}">{{ number_format($list->company_total, 2) }}</td>
                            </tr>

                        @endforeach
                    @else
                        <tr>
                            <td colspan="20">{{ trans("report/winloss::winloss.no_record") }}</td>
                        </tr>
                    @endif
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="2" class="text-right">{{ trans("report/winloss::winloss.table.total") }}</th>
                        <th class="text-right">{{ number_format($total_turnover, 2) }}</th>
                        <th class="text-right">{{ number_format($total_valid_amount, 2) }}</th>
                        <th class="text-right">{{ number_format($total_stake_count, 2) }}</th>

                        <th class="text-right {{ ($total_member_winloss < 0) ? 'text-red' : '' }}">{{ number_format($total_member_winloss, 2) }}</th>
                        <th class="text-right {{ ($total_member_comm < 0) ? 'text-red' : '' }}">{{ number_format($total_member_comm, 2) }}</th>
                        <th class="text-right font-bold {{ ($total_member_total < 0) ? 'text-red' : '' }}">{{ number_format($total_member_total, 2) }}</th>

                        <th class="text-right {{ ($total_agent_amount < 0) ? 'text-red' : '' }}">{{ number_format($total_agent_amount, 2) }}</th>
                        <th class="text-right {{ ($total_agent_winloss < 0) ? 'text-red' : '' }}">{{ number_format($total_agent_winloss, 2) }}</th>
                        <th class="text-right {{ ($total_agent_comm < 0) ? 'text-red' : '' }}">{{ number_format($total_agent_comm, 2) }}</th>
                        <th class="text-right font-bold {{ ($total_agent_total < 0) ? 'text-red' : '' }}">{{ number_format($total_agent_total, 2) }}</th>

                        <th class="text-right {{ ($total_super_amount < 0) ? 'text-red' : '' }}">{{ number_format($total_super_amount, 2) }}</th>
                        <th class="text-right {{ ($total_super_winloss < 0) ? 'text-red' : '' }}">{{ number_format($total_super_winloss, 2) }}</th>
                        <th class="text-right {{ ($total_super_comm < 0) ? 'text-red' : '' }}">{{ number_format($total_super_comm, 2) }}</th>
                        <th class="text-right font-bold {{ ($total_super_total < 0) ? 'text-red' : '' }}">{{ number_format($total_super_total, 2) }}</th>

                        <th class="text-right {{ ($total_company_amount < 0) ? 'text-red' : '' }}">{{ number_format($total_company_amount, 2) }}</th>
                        <th class="text-right {{ ($total_company_winloss < 0) ? 'text-red' : '' }}">{{ number_format($total_company_winloss, 2) }}</th>
                        <th class="text-right {{ ($total_company_comm < 0) ? 'text-red' : '' }}">{{ number_format($total_company_comm, 2) }}</th>
                        <th class="text-right font-bold {{ ($total_company_total < 0) ? 'text-red' : '' }}">{{ number_format($total_company_total, 2) }}</th>
                    </tr>
                    </tfoot>
                </table>

            </div>
        </div>
    @else
        <div class="row">
            <div class="col-md-12">
                <table id="winloss-table" class="table-bordered table-hover table-striped" cellspacing="1" cellpadding="3">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ trans("report/winloss::winloss.table.tran_time") }}</th>
                            <th>{{ trans("report/winloss::winloss.table.choice") }}</th>
                            <th>{{ trans("report/winloss::winloss.table.stake") }}</th>
                            <th>{{ trans("report/winloss::winloss.table.valid_amount") }}</th>
                            <th>{{ trans("report/winloss::winloss.table.w_l") }}</th>
                            <th>{{ trans("report/winloss::winloss.table.status") }}</th>

                            <th>{{ trans("report/winloss::winloss.table.agent_comm") }}</th>
                            <th>{{ trans("report/winloss::winloss.table.super_comm") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total_amount = 0;
                            $total_valid_amount = 0;
                            $total_winloss = 0;
                        @endphp

                        @if(count($dataWinloss) > 0)
                            @foreach($dataWinloss as $no => $list)

                                @php
                                    if($list->result_amount == 0){
                                        $text_result = "deal";
                                    }elseif($list->result_amount < 0){
                                        $text_result = "loss";
                                    }else{
                                        $text_result = "win";
                                    }

                                    $total_amount += $list->bet_amount;
                                    $total_valid_amount += $list->rolling;
                                    $total_winloss += $list->result_amount;

                                    $type = \Modules\Core\Games\Entities\GamesTypes::where('id', $list->game_type_id)->first();
                                    $game = \Modules\Core\Games\Entities\Games::where('id', $list->board_game_id)->first();
                                @endphp

                                <tr>
                                    <td>{{ $no+1 }}</td>
                                    <td class="text-center">
                                        <span class="text-blue">{{ trans("report/winloss::winloss.table.ref_no") }} {{ $list->id }}</span><br>
                                        {{ $list->bet_date }}<br>
                                        {{ $list->work_date }}
                                    </td>
                                    <td class="text-right">
                                        {{ trans("report/winloss::winloss.table.bet_on") }} {{ $type->name }}<br>
                                        <span class="text-blue">{{ $game->name }}</span><br>
                                        <span class="text-black-bold">{{ date('d/m', strtotime($list->bet_time)) }}</span><br>
                                        {{ trans("report/winloss::winloss.table.bet_id") }} {{ $list->bet_id }}
                                    </td>
                                    <td class="text-right">{{ number_format($list->bet_amount, 2) }}</td>
                                    <td class="text-right">{{ number_format($list->rolling, 2) }}</td>
                                    <td class="text-right">
                                        <span class="{{ ($list->result_amount < 0) ? 'text-red' : '' }}">{{ number_format($list->result_amount, 2) }}</span><br>
                                        {{ number_format($list->commission_amount, 2) }}
                                    </td>
                                    <td class="text-center font-bold">{{ $list->state }}</td>
                                    <td class="text-right">
                                        <span class="taking-block">
                                            {{ $list->agent_taking * 100 }}%<br>
                                            {{ number_format($list->agent_winloss, 2) }}
                                        </span><br>
                                        {{ $list->commission * 100 }}%<br>
                                        <span class="{{ ($list->agent_commission < 0) ? 'text-red' : '' }}">{{ number_format($list->agent_commission, 2) }}</span>
                                    </td>
                                    <td class="text-right">
                                        <span class="taking-block">
                                            {{ $list->super_taking * 100 }}%<br>
                                            {{ number_format($list->super_winloss, 2) }}
                                        </span><br>
                                        {{ $list->commission * 100 }}%<br>
                                        <span class="{{ ($list->super_commission < 0) ? 'text-red' : '' }}">{{ number_format($list->super_commission, 2) }}</span>
                                    </td>
                                </tr>

                            @endforeach
                        @else
                            <tr>
                                <td colspan="20">{{ trans("report/winloss::winloss.no_record") }}</td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="text-right">{{ number_format($total_amount, 2) }}</th>
                            <th class="text-right">{{ number_format($total_valid_amount, 2) }}</th>
                            <th class="text-right">{{ number_format($total_winloss, 2) }}</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>

            </div>
        </div>
    @endif

@endsection

@push('css')
    <style>
        table#winloss-table{
            font-size: 11px;
            font-family: Tahoma, Helvetica, sans-serif;
            width: 100%;
            border-collapse: collapse;
        }

        table#winloss-table th, table#winloss-table td{
            padding: 5px;
        }

        table#winloss-table th.head-partner {
            color: #000000;
            background-color: #009999;
        }
        table#winloss-table tr.total-partner th{
            color: #000000;
            background-color: #fffde7;
        }

        table#winloss-table td a{
            color: blue;
        }
        table#winloss-table td a:hover{
            text-decoration: underline;
        }

        table#winloss-table thead th{
            background-color: #0A5C9F;
            color: #ffffff;
            text-align: center;
        }

        table#winloss-table tfoot th{
            background-color: #9fcbea;
            color: #000;
        }

        table#winloss-table td.text-red, table#winloss-table th.text-red{
            color: #B50000 !important;
        }

        .taking-block{
            width: 100%;
            background-color: #F5E3E3;
            display: inline-block;
            font-weight: bold;
            color: #000;
        }

        .game-type-check label {
            min-width: 120px;
        }

        .text-blue{
            color: blue;
        }
        .text-black-bold{
            color: #000;
            font-weight: bold;
        }
        .text-red{
            color: #B50000;
        }
    </style>
@endpush

@push('scripts')
    <script>
        /**
         * For Type
         * */
        $("input[id=type_all]").click(function() {
            var main = $(this).val();
            console.log(main);
            var checkBoxes = $("#checkbox_type_all input[type=checkbox]");
            if($(this).prop("checked")){
                checkBoxes.prop("checked", true);
            }else{
                checkBoxes.prop("checked", false);
            }
        });

        $("#checkbox_type_all input[type=checkbox]").click(function() {
            var checkBoxes = $("#checkbox_type_all input[type=checkbox]").length;
            var checkBoxes_checked = $("#checkbox_type_all input[type=checkbox]:checked").length;
            var checkBoxesAll = $("input[id=type_all]");
            if(checkBoxes == checkBoxes_checked){
                checkBoxesAll.prop("checked", true);
            }else{
                checkBoxesAll.prop("checked", false);
            }
        });
    </script>
@endpush