{{--{{ print_r($commissions) }}--}}

<style>
    .panel-title a:hover, .panel-title a:active, .panel-title a:focus{
        color: #ffffff;
    }
</style>

<form class="form-horizontal" method="post" action="{{ route('member.members.commission.save', $entity->id) }}">
    @csrf

    <div class="panel-group" id="accordion_19" role="tablist" aria-multiselectable="true">

        @foreach($games as $game)
            <div class="panel panel-col-cyan">
                <div class="panel-heading" role="tab" id="gameHead_{{ $game['id'] }}">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" href="#game_{{ $game['id'] }}" aria-expanded="false" aria-controls="game_{{ $game['id'] }}">
                            {{ $game['name'] }}
                        </a>
                    </h4>
                </div>
                <div id="game_{{ $game['id'] }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="gameHead_{{ $game['id'] }}">
                    <div class="panel-body">

                        @foreach($game['types_game'] as $type)
                            <div class="row clearfix">
                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                    <label for="{{ $type['code'] }}">{{ $type['name'] }}</label>
                                </div>
                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <select name="type_id[{{ $game['id'] }}][{{ $type['id'] }}]" class="form-control">
                                                @foreach(setCommissionSelect() as $value => $comm)
                                                    <option value="{{ $value }}" {{ (isset($commissions[$game['id']][$type['id']])) ? (($commissions[$game['id']][$type['id']] == $value) ? "selected" : "") : (($type['start_comm'] == $value) ? "selected" : "") }}>{{ $comm }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if(!$game['types_game'])
                            <div class="alert alert-danger">
                                <strong>{{ trans('core::core.warning') }}!</strong> {{ trans('core::core.please_check_game_type_of_this_game') }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        @endforeach

    </div>

    <button class="btn btn-primary waves-effect" type="submit">{{ trans('core::core.form.save') }}</button>
</form>