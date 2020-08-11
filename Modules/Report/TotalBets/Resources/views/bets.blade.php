@extends('bootstrap')

@section('content')

    <table class="table table-bordered table-hover table-dark">
        <thead>
            <tr>
                <th rowspan="2" scope="col">#</th>
                <th rowspan="2" scope="col">Time</th>
                <th rowspan="2" scope="col">Event</th>
                <th colspan="2" scope="col">Full Time</th>
                <th colspan="2" scope="col">1st Half</th>
            </tr>
            <tr>
                <th scope="col">Handicap</th>
                <th scope="col">Over/Under</th>
                <th scope="col">Handicap</th>
                <th scope="col">Over/Under</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bets as $key => $bet)
            <tr>
                <th colspan="7" class="table-danger text-left" style="color: #000;" scope="row">{{ $bet['league'] }}</th>
            </tr>
                @foreach($bet['event'] as $skey => $rows)
                    @php
                        $t_f_hdp = 0;
                        $t_f_ou = 0;
                        $t_h_hdp = 0;
                        $t_h_ou = 0;
                    @endphp
                    @foreach($rows as $ag => $ev)
                        @php
                            $t_f_hdp += $ev['f_hdp'];
                            $t_f_ou += $ev['f_ou'];
                            $t_h_hdp += $ev['h_hdp'];
                            $t_h_ou += $ev['h_ou'];
                        @endphp
                        <tr class="table-warning" style="color: #0E2231">
                            <th scope="row" class="text-right">{{ $ag }}</th>
                            <td class="text-center">{!! $ev['time'] !!}</td>
                            <td>{{ $ev['event'] }}</td>
                            <td class="text-right {{ ($ev['f_hdp'] < 0) ? "text-danger" : "" }}">{{ $ev['f_hdp'] }}</td>
                            <td class="text-right {{ ($ev['f_ou'] < 0) ? "text-danger" : "" }}">{{ $ev['f_ou'] }}</td>
                            <td class="text-right {{ ($ev['h_hdp'] < 0) ? "text-danger" : "" }}">{{ $ev['h_hdp'] }}</td>
                            <td class="text-right {{ ($ev['h_ou'] < 0) ? "text-danger" : "" }}">{{ $ev['h_ou'] }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th scope="col" colspan="3">Total</th>
                        <th scope="col" class="text-right {{ ($t_f_hdp < 0) ? "text-danger" : "" }}">{{ $t_f_hdp }}</th>
                        <th scope="col" class="text-right {{ ($t_f_ou < 0) ? "text-danger" : "" }}">{{ $t_f_ou }}</th>
                        <th scope="col" class="text-right {{ ($t_h_hdp < 0) ? "text-danger" : "" }}">{{ $t_h_hdp }}</th>
                        <th scope="col" class="text-right {{ ($t_h_ou < 0) ? "text-danger" : "" }}">{{ $t_h_ou }}</th>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

@endsection

@section('css')
    <style>
        body{
            font-size: 12px;
        }
        .container{
            width: 1600px;
            max-width: 1600px;
        }
        table th{
            text-align: center;
        }
    </style>
@endsection