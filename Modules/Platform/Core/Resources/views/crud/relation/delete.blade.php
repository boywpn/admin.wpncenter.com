{!! Form::open(['route' => [$delete_route], 'method' => 'post','class'=>'delete-relation']) !!}

{{ Form::hidden('entityId',$entityId) }}
{{ Form::hidden('relationEntityId',$relationEntityId) }}

{!! Form::button('<i class="unlink-icon material-icons">delete</i>', [
    'type' => 'submit',
    'class' => 'unlink-relation',
    'title' => trans('core::core.delete_relation'),
    'onclick' => "return confirm($.i18n._('are_you_sure'))"
]) !!}

{!! Form::close() !!}

