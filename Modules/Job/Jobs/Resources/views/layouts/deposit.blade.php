<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">

        <form class="form-horizontal">
            <h2 class="card-inside-title">@lang($language_file.'.general_information')</h2>
            <table class="table table-striped table-hover table-bordered">
                <tr>
                    <th width="20%">@lang($language_file.'.form.order_id')</th>
                    <td width="30%" class="font-16 font-bold col-red">{{ $job['order_code'] }}</td>
                    <th width="20%">@lang($language_file.'.form.cust_username')</th>
                    <td width="30%" class="font-16 font-bold col-red">{{ $username['username'] }}</td>
                </tr>
                <tr>
                    <th>@lang($language_file.'.form.created_at')</th>
                    <td class="font-16 font-bold col-blue">{{ $job['created_at'] }}</td>
                    <th>@lang($language_file.'.form.cust_agent')</th>
                    <td>{{ $member['members_agent']['name'] }}</td>
                </tr>
                <tr>
                    <th>@lang($language_file.'.form.cust_name')</th>
                    <td class="font-16 font-bold col-blue">{{ $member['name'] }}</td>
                    <th>@lang($language_file.'.form.cust_partner')</th>
                    <td class="font-16 font-bold col-red">{{ $member['members_agent']['agents_partner']['name'] }}</td>
                </tr>
                <tr>
                    <th>@lang($language_file.'.form.cust_phone')</th>
                    <td>{{ phoneReplace($member['phone']) }}</td>
                    <th>@lang($language_file.'.form.user_game')</th>
                    <td>{{ $username['username_board']['boards_game']['name'] }}</td>
                </tr>
                <tr>
                    <th>@lang($language_file.'.form.cust_bank')</th>
                    <td>
                        <ul class="list-group" style="list-style: inside">
                            @foreach($member_banks as $bank)
                                <li class="{{ ($bank['is_main']) ? "font-bold" : "" }}">[{{ $bank['banks_bank']['code'] }}] {{ $bank['bank_number'] }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <th>@lang($language_file.'.form.user_board')</th>
                    <td>{{ $username['username_board']['name'] }}</td>
                </tr>
            </table>

            <h2 class="card-inside-title">@lang($language_file.'.transfer_information')</h2>
            <table class="table table-striped table-hover table-bordered">
                <tr>
                    <th width="20%">@lang($language_file.'.form.amount')</th>
                    <td width="30%" class="font-16 font-bold col-red">{{ number_format($job['total_amount'], 0) }}</td>
                    <th width="20%">@lang($language_file.'.form.topup_pay_at')</th>
                    <td width="30%" class="font-16 font-bold col-red">{{ $job['topup_pay_at'] }}</td>
                </tr>
                <tr>
                    <th>@lang($language_file.'.form.topup_to_bank')</th>
                    <td colspan="4">
                        @if($job['type_id'] == 1)
                            [{{ $job['topup_to_bank']['banks_bank']['banks']['code'] }}] {{ $job['topup_to_bank']['banks_bank']['account'] }} {{ $job['topup_to_bank']['banks_bank']['number'] }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>@lang($language_file.'.form.topup_slip')</th>
                    <td colspan="4">
                        @if(!empty($job['topup_slip']))
                            <div class="row"id="aniimated-thumbnials">
                                <div class="col-md-3">
                                    <a href="/storage/files/jobs_images/{{ $job['topup_slip'] }}">
                                        <img class="img-responsive thumbnail m-b-0" src="/storage/files/jobs_images/{{ $job['topup_slip'] }}">
                                    </a>
                                </div>
                            </div>
                        @endif
                    </td>
                </tr>
            </table>

            <h2 class="card-inside-title">@lang($language_file.'.credit_information')</h2>
            <table class="table table-striped table-hover table-bordered">
                <tr>
                    <th width="20%"></th>
                    <th width="40%" class="text-center">@lang($language_file.'.form.credit_before')</th>
                    <th width="40%" class="text-center">@lang($language_file.'.form.credit_after')</th>
                </tr>
                <tr>
                    <th>@lang($language_file.'.form.credit_member')</th>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>@lang($language_file.'.form.credit_board')</th>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </form>

    </div>
</div>