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
                <th colspan="7" class="text-left" style="color: #000; background-color: #FED7A0" scope="row">{{ $bet['league'] }}</th>
            </tr>
                @php
                    $a = 1;
                @endphp
                @foreach($bet['event'] as $skey => $rows)
                    @php
                        $t_f_hdp = 0;
                        $t_f_ou = 0;
                        $t_h_hdp = 0;
                        $t_h_ou = 0;
                        $time = null;
                        $event = null;
                    @endphp
                    @foreach($rows as $ag => $ev)
                        @php
                            $t_f_hdp += $ev['f_hdp'];
                            $t_f_ou += $ev['f_ou'];
                            $t_h_hdp += $ev['h_hdp'];
                            $t_h_ou += $ev['h_ou'];
                            $time = $ev['time'];
                            $event = $ev['event'];
                        @endphp
                        <tr class="table-warning collapse" id="coll_{{ $key.$skey }}" style="color: #0E2231">
                            <th scope="row" class="text-right">{{ $ag }}</th>
                            <td class="text-center">{!! $ev['time'] !!}</td>
                            <td>{{ $ev['event'] }}</td>
                            <td class="text-right {{ ($ev['f_hdp'] < 0) ? "text-danger" : "" }}">{{ $ev['f_hdp'] }}</td>
                            <td class="text-right {{ ($ev['f_ou'] < 0) ? "text-danger" : "" }}">{{ $ev['f_ou'] }}</td>
                            <td class="text-right {{ ($ev['h_hdp'] < 0) ? "text-danger" : "" }}">{{ $ev['h_hdp'] }}</td>
                            <td class="text-right {{ ($ev['h_ou'] < 0) ? "text-danger" : "" }}">{{ $ev['h_ou'] }}</td>
                        </tr>
                    @endforeach
                    <tr data-toggle="collapse" href="#coll_{{ $key.$skey }}" style="background-color: #FFDCCC; color: #000">
                        <th scope="col">{{ $a }}</th>
                        <th scope="col">{!! $time !!}</th>
                        <th scope="col" class="text-left">{{ $event }}</th>
                        <th scope="col" class="text-right {{ ($t_f_hdp < 0) ? "text-danger" : "" }}">{{ $t_f_hdp }}</th>
                        <th scope="col" class="text-right {{ ($t_f_ou < 0) ? "text-danger" : "" }}">{{ $t_f_ou }}</th>
                        <th scope="col" class="text-right {{ ($t_h_hdp < 0) ? "text-danger" : "" }}">{{ $t_h_hdp }}</th>
                        <th scope="col" class="text-right {{ ($t_h_ou < 0) ? "text-danger" : "" }}">{{ $t_h_ou }}</th>
                    </tr>
                    @php
                        $a++;
                    @endphp
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