<form method="POST" action="{{ route('core.advanced-view.update') }}" accept-charset="UTF-8" class="module_form" id="advanced_view_settings_form" >

    {{ Form::token() }}
    {{ Form::hidden('filterId',$viewEdit->id) }}
    {{ Form::hidden('module_name',$moduleName) }}
    {{ Form::hidden('module_rules','',['id'=>'module_rules']) }}

    <div class="row">
        <div class="col-lg-5 col-md-5 col-sm-5">

            <div>
                <label for="view_name" class="form-label">@lang('core::core.advanced_view.view_name')</label>
                <input class="form-control" name="view_name" value="{{ $viewEdit->view_name }}"  type="text" id="job_title">
            </div>


        </div>

        <div class="col-lg-1 col-sm-1 col-sm-12 p-t-30">
            &nbsp;
        </div>

        <div class="col-lg-5 col-md-5 col-sm-5">

            <div>

                <label>@lang('core::core.advanced_view.visibility')</label>
                <div>
                    <select name="visibility" class="form-control select_material" id="visibility">
                        <option @if($viewEdit->is_public) selected="selected" @endif value="public">@lang('core::core.advanced_view.public')</option>
                        <option @if(!$viewEdit->is_public) selected="selected" @endif value="private">@lang('core::core.advanced_view.private')</option>
                    </select>
                </div>

            </div>

        </div>
    </div>

    <div class="row">

        <div class="col-lg-5 col-md-5 col-sm-12">
            <label>@lang('core::core.advanced_view.module_fields')</label>
            <div>
                <select name="all_module_fileds[]" multiple class="form-control select_material" style="height: 200px;" id="all_module_fileds">
                    @foreach($availableColumns as $key =>  $column)
                        <option value="{{ $column['data'] }}">{{ $column['title'] }}</option>
                    @endforeach
                </select>
            </div>

        </div>


        <div class="col-lg-1 col-sm-1 col-sm-12 p-t-30">

            <i id="adv-btn-right" title="@lang('core::core.advanced_view.move_right')" class="material-icons pointer">keyboard_arrow_right</i>
            <i id="adv-btn-left" title="@lang('core::core.advanced_view.move_left')" class="material-icons pointer">keyboard_arrow_left</i>

        </div>


        <div class="col-lg-5 col-md-5 col-sm-12">

            <div class="row">
                <div class="col-sm-11">
                    <label>@lang('core::core.advanced_view.selected_fields')</label>
                    <div>
                        <select name="selected_fields[]" multiple class="form-control select_material" style="height: 200px;" id="selected_fileds">
                            @foreach($selectedColumns as $key =>  $column)
                                <option value="{{ $column['data'] }}">{{ $column['title'] }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="col-sm-1 p-t-30">
                    <i id="adv-btn-up" title="@lang('core::core.advanced_view.move_up')" class="material-icons pointer">keyboard_arrow_up</i>
                    <i id="adv-btn-up" title="@lang('core::core.advanced_view.move_down')" class="material-icons pointer">keyboard_arrow_down</i>
                </div>

            </div>

        </div>

        <div class="col-lg-12">
            <label>@lang('core::core.advanced_view.filters')</label>
            <div id="advanced_settings_filters_creator" class="filters">

            </div>
        </div>

        <div class="col-lg-12">
            <input type="submit" class="btn btn-primary m-t-15 waves-effect" value="@lang('core::core.form.save')" />
        </div>

    </div>

</form>

{!! JsValidator::formRequest(\Modules\Platform\Core\Http\Requests\AdvancedViewRequest::class, '#advanced_view_settings_form') !!}


<script type="text/javascript">

    $(document).ready(function(){

        $("#advanced_settings_filters_creator").queryBuilder({
            plugins: ['bt-tooltip-errors','filter-description'],

            filters: window.LaravelDataTablesFilters["{{ $tableId }}"],

            sort_filters : true,
            lang_code : window.APPLICATION_USER_LANGUAGE,

        });

        @if($viewEdit->filter_rules)
            $("#advanced_settings_filters_creator").queryBuilder('setRules', {!! $viewEdit->filter_rules   !!});
        @endif
    });


</script>
