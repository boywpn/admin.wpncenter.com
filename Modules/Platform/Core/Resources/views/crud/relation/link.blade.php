{!! Form::open(['route' => [$route], 'method' => 'post','class'=>'link-selected']) !!}

{{ Form::hidden('entityId',$entityId) }}
{{ Form::hidden('relationEntityIds') }}
{{ Form::hidden('modalName','modal_'.$tabkey) }}
{{ Form::hidden('linkedName','linked_'.$tabkey) }}

{!! Form::button(trans('core::core.add_selected'), [
    'type' => 'submit',
    'class' => 'btn btn-primary link-selected',
    'title' => trans('core::core.unlink_relation'),
    'onclick' => "return confirm($.i18n._('are_you_sure'))"
]) !!}

{!! Form::close() !!}
