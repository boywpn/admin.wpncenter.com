<div class="row">


    {!! form_start($form) !!}

    @foreach($show_fields as $panelName => $panel)

        {{ Html::section($language_file,$panelName,$sectionButtons) }}


        @foreach($panel as $fieldName => $options)

            @if(!isset($options['hide_in_form']) && !isset($options['hide_in_form_edit']))
                @if($loop->iteration % 2 == 0)
                    <div class="{{ isset($options['col-class']) ? $options['col-class'] : 'col-lg-6 col-md-6 col-sm-6 col-xs-6' }}">
                        @else
                            <div class="{{ isset($options['col-class']) ? $options['col-class'] : 'col-lg-6 col-md-6 col-sm-6 col-xs-6 clear-left' }}">
                                @endif

                                {!! form_row($form->{$fieldName}) !!}
                            </div>
                        @endif

                        @endforeach

                        @endforeach

                        {!! form_end($form, $renderRest = true) !!}

                    </div>

</div>



@if( $modal_form )
    @foreach($jsFiles as $jsFile)
        <script src="{!! Module::asset($moduleName.':js/'.$jsFile) !!}"></script>
    @endforeach
@endif


@if($form_request != null && $modal_form)
    {!! JsValidator::formRequest($form_request, '#'.$formId) !!}
@endif
