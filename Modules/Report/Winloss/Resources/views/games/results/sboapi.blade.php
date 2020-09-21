@php
    $results = \Modules\Report\Betlists\Entities\BetlistsResults::where('betlist_id', $list->id)->first();
    $res = json_decode($results->game_result, true);
@endphp

{{--@if(in_array($res['sportsType'], ['sportsType', 'Mix Parlay']))--}}
@if(isset($res['sportsType']))
    @foreach($res['subBet'] as $v)
        <div class="betOption" style="font-weight: bold; color: red">{{ $v['betOption'] }} {{ "@".$v['hdp'] }} {{ "@".$v['liveScore'] }} {{ "@".number_format($v['odds'], 2, '.', '') }}</div>
        <div class="sportType" style="color: #0A5C9F">{{ (isset($v['sportType'])) ? $v['sportType']." / " : "" }}{{ $v['marketType'] }}</div>
        <div class="match" style="font-weight: bold; color: #000000">{{ $v['match'] }}</div>
        <div class="league" style="color: orange">{{ $v['league'] }} <span style="color: #0A5C9F">@</span> {{ date("m/d/Y", strtotime($v['winlostDate'])) }}</div>
        <br>
    @endforeach
@else
    {{ trans("report/winloss::winloss.table.bet_on") }} {{ $type->name }}<br>
    <span class="text-blue">{{ $game->name }}</span><br>
    <span class="text-black-bold">{{ date('d/m', strtotime($list->bet_time)) }}</span><br>
    {{ trans("report/winloss::winloss.table.bet_id") }} {{ $list->bet_id }}
@endif