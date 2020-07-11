@extends('layouts.app')

@section('content')

    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">

                <div class="header">
                    <h2>
                        <div class="header-buttons">
                            <a href="{{ route($routes['create']) }}" title="@lang('core::core.crud.back')" class="btn btn-primary btn-back btn-crud">@lang('core::core.crud.back')</a>
                        </div>

                        <div class="header-text">
                            @lang($language_file.'.created_job') - @lang('core::core.crud.details')
                            <small>@lang($language_file.'.module_description')</small>
                        </div>
                    </h2>
                </div>

            </div>
        </div>

        <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
            <div class="card">

                <div class="header bg-cyan">
                    <h2>
                        <div class="header-text">
                            @lang($language_file.'.member_information')
                        </div>
                    </h2>
                </div>

                <div class="body">

                    <form class="form-horizontal">

                        <h2 class="card-inside-title">@lang($language_file.'.information')</h2>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                <label>@lang($language_file.'.form.cust_name')</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                <div class="form-group">
                                    <div class="form-line">
                                        <div class="form-control-static">{{ $member['name'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                <label>@lang($language_file.'.form.cust_phone')</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                <div class="form-group">
                                    <div class="form-line">
                                        <div class="form-control-static">{{ replacePhone($member['phone']) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                <label>@lang($language_file.'.form.cust_email')</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                <div class="form-group">
                                    <div class="form-line">
                                        <div class="form-control-static">{{ $member['email'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                <label>@lang($language_file.'.form.cust_facebook')</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                <div class="form-group">
                                    <div class="form-line">
                                        <div class="form-control-static">{{ $member['facebook'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                <label>@lang($language_file.'.form.cust_line')</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                <div class="form-group">
                                    <div class="form-line">
                                        <div class="form-control-static">{{ $member['lineid'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                <label>@lang($language_file.'.form.cust_status')</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                <div class="form-group">
                                    <div class="form-line">
                                        <div class="form-control-static">{{ $member['members_status']['name'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                <label>@lang($language_file.'.form.cust_notes')</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                <div class="form-group">
                                    <div class="form-line">
                                        <div class="form-control-static">{{ $member['notes'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                <label>@lang($language_file.'.form.cust_agent')</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                <div class="form-group">
                                    <div class="form-line">
                                        <div class="form-control-static">{{ $member['members_agent']['name'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                <label>@lang($language_file.'.form.cust_partner')</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                <div class="form-group">
                                    <div class="form-line">
                                        <div class="form-control-static">{{ $member['members_agent']['agents_partner']['website'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 class="card-inside-title" style="margin-bottom: 15px">@lang($language_file.'.bank_information')</h2>
                        <div class="list-group">
                            @php $bank_main = "" @endphp
                            @foreach($member['banks_member'] as $bank)
                            <a href="javascript:void(0);" class="list-group-item">
                                @if($bank['is_main'])
                                    @php $bank_main = $bank['id'] @endphp
                                    <span class="badge bg-red">@lang($language_file.'.main_bank_account')</span>
                                @endif
                                {{ $bank['bank_account'] }} [{{ $bank['banks_bank']['code'] }} {{ $bank['bank_number'] }}]
                            </a>
                            @endforeach
                        </div>

                    </form>

                </div>

            </div>
        </div>


        <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
            <div class="card">

                <div class="header bg-green">
                    <h2>
                        <div class="header-text">
                            @lang($language_file.'.job_information') @lang($language_file.'.'.$input['submit_action'])
                        </div>
                    </h2>
                </div>

                <div class="body">

                    @if($input['submit_action'] == "deposit")

                        <form id="form_deposit" method="POST" action="{{ route($routes['store']) }}" class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="type_id" value="1">
                            <input type="hidden" name="member_id" value="{{ $member['id'] }}">
                            <input type="hidden" name="code" value="{{ time() }}">

                            <h2 class="card-inside-title" style="margin-bottom: 15px; margin-top: 0">@lang($language_file.'.deposit_information')</h2>
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                    <label for="username_id">@lang($language_file.'.form.to_username')</label>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <select name="username_id" id="username_id" class="form-control select2 show-tick" data-placeholder="@lang('core::core.please_selected_option')">
                                                <option value=""></option>
                                                @foreach($member['username_member'] as $username)
                                                    <option value="{{ $username['id'] }}" @if($username['id'] == $input['username_id']) selected @endif>[{{ $username['username_board']['boards_game']['name'] }}] {{ $username['username'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                    <label for="topup_from_bank">@lang($language_file.'.form.topup_from_bank')</label>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <select name="topup_from_bank" id="topup_from_bank" class="form-control select2 show-tick" data-placeholder="@lang('core::core.please_selected_option')">
                                                <option value=""></option>
                                                @foreach($member['banks_member'] as $banks_member)
                                                    <option value="{{ $banks_member['id'] }}" @if($banks_member['id'] == $bank_main) selected @endif>{{ delMiltiSpace($banks_member['bank_account']) }} [{{ $banks_member['banks_bank']['code'] }} {{ $banks_member['bank_number'] }}]</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                    <label for="amount">@lang($language_file.'.form.amount')</label>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" id="amount" name="amount" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                    <label for="topup_pay_at">@lang($language_file.'.form.topup_pay_at')</label>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" id="topup_pay_at" name="topup_pay_at" class="form-control datetimepicker">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                    <label for="topup_to_bank">@lang($language_file.'.form.topup_to_bank')</label>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <select name="topup_to_bank" id="topup_to_bank" class="form-control select2 show-tick" data-placeholder="@lang('core::core.please_selected_option')">
                                                <option value=""></option>
                                                @foreach($bankspartner as $bankspartner)
                                                    <option value="{{ $bankspartner['bank_partner_id'] }}">[{{ $bankspartner['bank_code'] }} {{ $bankspartner['bank_number'] }}] {{ delMiltiSpace($bankspartner['bank_account']) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                    <label for="topup_slip">@lang($language_file.'.form.topup_slip')</label>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="file" id="topup_slip" name="topup_slip" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                    <label for="topup_slip">@lang($language_file.'.form.promotions')</label>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                    <div class="form-group">
                                        <div class="radio">
                                            <input name="promotion_id" type="radio" id="promotion_0" value="" class="with-gap radio-col-green" checked/> <label for="promotion_0" class="text-danger">@lang($language_file.'.form.not_get_promotion')</label>
                                        </div>
                                        @foreach($member['members_agent']['agents_partner']['partners_promotion'] as $promotion)
                                            @if($promotion['is_front'])
                                                <div class="radio">
                                                    <input name="promotion_id" type="radio" id="promotion_{{ $promotion['id'] }}" value="{{ $promotion['id'] }}" class="with-gap radio-col-green"/> <label for="promotion_{{ $promotion['id'] }}">{{ $promotion['title'] }}</label>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-lg-offset-3 col-md-offset-3 col-sm-offset-4 col-xs-offset-5">
                                    <button type="submit" class="btn btn-primary m-t-15 waves-effect"><i class="fa fa-save" aria-hidden="true"></i>  @lang($language_file.'.save')</button>
                                </div>
                            </div>

                        </form>

                    @elseif($input['submit_action'] == "withdraw")

                        <form id="form_withdraw" method="POST" action="{{ route($routes['store']) }}" class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="type_id" value="2">
                            <input type="hidden" name="member_id" value="{{ $member['id'] }}">
                            <input type="hidden" name="code" value="{{ time() }}">

                            <h2 class="card-inside-title" style="margin-bottom: 15px; margin-top: 0">@lang($language_file.'.withdraw_information')</h2>
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                    <label for="username_id">@lang($language_file.'.form.from_username')</label>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <select name="username_id" id="username_id" class="form-control select2 show-tick" data-placeholder="@lang('core::core.please_selected_option')">
                                                <option value=""></option>
                                                @foreach($member['username_member'] as $username)
                                                    <option value="{{ $username['id'] }}" @if($username['id'] == $input['username_id']) selected @endif>[{{ $username['username_board']['boards_game']['name'] }}] {{ $username['username'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                    <label for="withdraw_to_bank">@lang($language_file.'.form.withdraw_to_bank')</label>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <select name="withdraw_to_bank" id="withdraw_to_bank" class="form-control select2 show-tick" data-placeholder="@lang('core::core.please_selected_option')">
                                                <option value=""></option>
                                                @foreach($member['banks_member'] as $banks_member)
                                                    <option value="{{ $banks_member['id'] }}" @if($banks_member['id'] == $bank_main) selected @endif>{{ delMiltiSpace($banks_member['bank_account']) }} [{{ $banks_member['banks_bank']['code'] }} {{ $banks_member['bank_number'] }}]</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                    <label for="amount">@lang($language_file.'.form.amount')</label>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" id="amount" name="amount" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-lg-offset-3 col-md-offset-3 col-sm-offset-4 col-xs-offset-5">
                                    <button type="submit" class="btn btn-primary m-t-15 waves-effect"><i class="fa fa-save" aria-hidden="true"></i>  @lang($language_file.'.save')</button>
                                </div>
                            </div>

                        </form>

                    @elseif($input['submit_action'] == "transfer")

                        <form id="form_withdraw" method="POST" action="{{ route($routes['store']) }}" class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="type_id" value="3">
                            <input type="hidden" name="member_id" value="{{ $member['id'] }}">
                            <input type="hidden" name="code" value="{{ time() }}">

                            <h2 class="card-inside-title" style="margin-bottom: 15px; margin-top: 0">@lang($language_file.'.transfer_information')</h2>
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                    <label for="username_id">@lang($language_file.'.form.from_username')</label>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <select name="username_id" id="username_id" class="form-control select2 show-tick" data-placeholder="@lang('core::core.please_selected_option')">
                                                <option value=""></option>
                                                @foreach($member['username_member'] as $username)
                                                    <option value="{{ $username['id'] }}" @if($username['id'] == $input['username_id']) selected @endif>[{{ $username['username_board']['boards_game']['name'] }}] {{ $username['username'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                    <label for="transfer_to_username">@lang($language_file.'.form.to_username')</label>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <select name="transfer_to_username" id="transfer_to_username" class="form-control select2 show-tick" data-placeholder="@lang('core::core.please_selected_option')">
                                                <option value=""></option>
                                                @foreach($member['username_member'] as $username)
                                                    <option value="{{ $username['id'] }}">[{{ $username['username_board']['boards_game']['name'] }}] {{ $username['username'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                    <label for="amount">@lang($language_file.'.form.amount')</label>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" id="amount" name="amount" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-lg-offset-3 col-md-offset-3 col-sm-offset-4 col-xs-offset-5">
                                    <button type="submit" class="btn btn-primary m-t-15 waves-effect"><i class="fa fa-save" aria-hidden="true"></i>  @lang($language_file.'.save')</button>
                                </div>
                            </div>

                        </form>

                        @elseif($input['submit_action'] == "promotion")

                            <form id="form_deposit" method="POST" action="{{ route($routes['store']) }}" class="form-horizontal" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="type_id" value="4">
                                <input type="hidden" name="member_id" value="{{ $member['id'] }}">
                                <input type="hidden" name="code" value="{{ time() }}">
                                <input type="hidden" name="amount" value="0">

                                <h2 class="card-inside-title" style="margin-bottom: 15px; margin-top: 0">@lang($language_file.'.deposit_information')</h2>
                                <div class="row clearfix">
                                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                        <label for="username_id">@lang($language_file.'.form.to_username')</label>
                                    </div>
                                    <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <select name="username_id" id="username_id" class="form-control select2 show-tick" data-placeholder="@lang('core::core.please_selected_option')">
                                                    <option value=""></option>
                                                    @foreach($member['username_member'] as $username)
                                                        <option value="{{ $username['id'] }}" @if($username['id'] == $input['username_id']) selected @endif>[{{ $username['username_board']['boards_game']['name'] }}] {{ $username['username'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                        <label for="job_ref">@lang($language_file.'.form.job_ref')</label>
                                    </div>
                                    <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="job_ref" name="job_ref" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                        <label for="promotion_amount">@lang($language_file.'.form.amount')</label>
                                    </div>
                                    <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="promotion_amount" name="promotion_amount" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                        <label for="topup_slip">@lang($language_file.'.form.promotions')</label>
                                    </div>
                                    <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <div class="radio">
                                                <input name="promotion_id" type="radio" id="promotion_0" value="" class="with-gap radio-col-green" checked/> <label for="promotion_0" class="text-danger">@lang($language_file.'.form.not_get_promotion')</label>
                                            </div>
                                            @foreach($member['members_agent']['agents_partner']['partners_promotion'] as $promotion)
                                                @if(!$promotion['is_front'])
                                                    <div class="radio">
                                                        <input name="promotion_id" type="radio" id="promotion_{{ $promotion['id'] }}" value="{{ $promotion['id'] }}" class="with-gap radio-col-green"/> <label for="promotion_{{ $promotion['id'] }}">{{ $promotion['title'] }}</label>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="row clearfix">
                                    <div class="col-lg-offset-3 col-md-offset-3 col-sm-offset-4 col-xs-offset-5">
                                        <button type="submit" class="btn btn-primary m-t-15 waves-effect"><i class="fa fa-save" aria-hidden="true"></i>  @lang($language_file.'.save')</button>
                                    </div>
                                </div>

                            </form>

                    @endif

                </div>

            </div>
        </div>

    </div>

    @foreach($includeViews as $v)
        @include($v)
    @endforeach

@endsection

@push('css')
    @foreach($cssFiles as $file)
        <link rel="stylesheet" href="{!! Module::asset($moduleName.':css/'.$file) !!}"></link>
    @endforeach
@endpush

@push('scripts')
    @foreach($jsFiles as $jsFile)
        <script src="{!! Module::asset($moduleName.':js/'.$jsFile) !!}"></script>
    @endforeach

    <script>
        jQuery(document).ready(function(){

            $("#form_deposit, #form_withdraw").validate({
                errorElement: 'span',
                errorClass: 'help-block error-help-block',

                @if($input['submit_action'] == "deposit")
                    rules: {
                        "username_id":{"laravelValidation":[["Required",[],"@lang('core::core.field_is_required')",true]]},
                        "amount":{"laravelValidation":[["Required",[],"@lang('core::core.field_is_required')",true]]},
                        "topup_pay_at":{"laravelValidation":[["Required",[],"@lang('core::core.field_is_required')",true]]},
                        "topup_to_bank":{"laravelValidation":[["Required",[],"@lang('core::core.field_is_required')",true]]},
                        "topup_slip":{"laravelValidation":[["Required",[],"@lang('core::core.field_is_required')",true]]}
                    },
                @elseif($input['submit_action'] == "withdraw")
                    rules: {
                        "username_id":{"laravelValidation":[["Required",[],"@lang('core::core.field_is_required')",true]]},
                        "withdraw_to_bank":{"laravelValidation":[["Required",[],"@lang('core::core.field_is_required')",true]]},
                        "amount":{"laravelValidation":[["Required",[],"@lang('core::core.field_is_required')",true]]}
                    },
                @elseif($input['submit_action'] == "transfer")
                    rules: {
                        "username_id":{"laravelValidation":[["Required",[],"@lang('core::core.field_is_required')",true]]},
                        "transfer_to_username":{"laravelValidation":[["Required",[],"@lang('core::core.field_is_required')",true]]},
                        "amount":{"laravelValidation":[["Required",[],"@lang('core::core.field_is_required')",true]]}
                    },
                @elseif($input['submit_action'] == "promotion")
                    rules: {
                        "username_id":{"laravelValidation":[["Required",[],"@lang('core::core.field_is_required')",true]]},
                        "amount":{"laravelValidation":[["Required",[],"@lang('core::core.field_is_required')",true]]}
                    },
                @endif

                errorPlacement: function(error, element) {
                    if (element.parent('.input-group').length || element.prop('type') === 'checkbox' || element.prop('type') === 'radio' || element.hasClass('manyToOne')) {
                        error.insertAfter(element.parent());
                        // else just place the validation message immediatly after the input
                    } else {
                        error.insertAfter(element.parent());
                    }
                },
                highlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-success').addClass('has-error'); // add the Bootstrap error class to the control group
                },


                /*
                 // Uncomment this to mark as validated non required fields
                 unhighlight: function(element) {
                 $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
                 },
                 */
                success: function(element) {
                    $(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // remove the Boostrap error class from the control group
                },

                focusInvalid: false, // do not focus the last invalid input
                invalidHandler: function(form, validator) {

                    if (!validator.numberOfInvalids())
                        return;

                    $('html, body').animate({
                        scrollTop: $(validator.errorList[0].element).offset().top -100
                    }, 1000);
                    $(validator.errorList[0].element).focus();

                }

            })
        })
    </script>
@endpush