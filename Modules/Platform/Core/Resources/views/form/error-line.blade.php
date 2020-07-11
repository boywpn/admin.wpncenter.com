
@if ($errors->has($field))
    <span class="help-block error-help-block">
         <strong>{{ $errors->first($field) }}</strong>
    </span>
@endif