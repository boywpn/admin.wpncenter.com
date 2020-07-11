<style>
    .panel-title a:hover, .panel-title a:active, .panel-title a:focus{
        color: #ffffff;
    }
</style>

<form class="form-horizontal" method="post" action="{{ route('core.username.bet_limit', $entity->id) }}">
    @csrf

    <div class="panel-group" id="accordion_19" role="tablist" aria-multiselectable="true">

        @foreach($games['types_game'] as $type)
            <div class="panel panel-col-cyan">
                <div class="panel-heading" role="tab" id="gameHead_{{ $type['id'] }}">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" href="#type_{{ $type['id'] }}" aria-expanded="false" aria-controls="type_{{ $type['id'] }}">
                            {{ $type['name'] }}
                        </a>
                    </h4>
                </div>
                <div id="type_{{ $type['id'] }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="typeHead_{{ $type['id'] }}">
                    <div class="panel-body">

                        @php
                        $values = json_decode($type['betlimit_value'], true);
                        $sel = json_decode($entity->bet_limits, true);
                        @endphp
                        @foreach ($values['values'] as $val => $label)
                            @php
                            $checked = (empty($sel[$type['code']])) ? $values['default'] : $sel[$type['code']];
                            @endphp
                            <input name="bet_limit[{{ $type['code'] }}]" {{ ($checked == $val) ? "checked" : "" }} type="radio" id="radio_{{ $type['id'] }}_{{ $val }}" value="{{ $val }}" class="with-gap radio-col-indigo" /><label for="radio_{{ $type['id'] }}_{{ $val }}">{{ $label }}</label>
                        @endforeach

                    </div>
                </div>
            </div>
        @endforeach

    </div>

    <button class="btn btn-primary waves-effect" type="submit">{{ trans('core::core.form.save') }}</button>
</form>