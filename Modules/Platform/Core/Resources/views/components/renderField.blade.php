
<?php
/**
 * 'entity','fieldName','$options','language_file'
 */
?>
@if(isset($options['hide_in_show']) && $options['hide_in_show'])

@else
    <div class="field-wrapper {{ isset($options['col-class']) ? $options['col-class'] : 'col-lg-6 col-md-6 col-sm-6 col-xs-6' }}">
    @if(isset($options['hide_label']) && $options['hide_label'])

    @else
        <label class="show-control-label">
            @lang($language_file.'.form.'.$fieldName)
        </label>
    @endif
       @include('core::components.types.'.$options['type'],['entity'=>$entity,'fieldName'=>$fieldName,'options'=>$options])
    </div>
@endif

