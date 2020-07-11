{!! Form::open(['route' => [$unlink_route], 'method' => 'post','class'=>'unlink-relation']) !!}

{{ Form::hidden('entityId',$entityId) }}
{{ Form::hidden('relationEntityId',$relationEntityId) }}

{!! Form::button('<i class="unlink-icon material-icons">clear</i>', [
    'type' => 'submit',
    'class' => 'unlink-relation',
    'title' => trans('core::core.unlink_relation'),
    'onclick' => "return confirm($.i18n._('are_you_sure'))"
]) !!}

{!! Form::close() !!}

