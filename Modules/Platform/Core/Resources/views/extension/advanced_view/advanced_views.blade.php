@if($hasQueryFilters)

<div class="advanced_views">



    <i title="@lang('core::core.advanced_view.advanced_filters')" data-filter-id="queryFilter_{{ $dataTable->getTableAttribute('id') }}" class="material-icons btn-show-filters">filter_list</i>


    @if($advancedViewsEnabled)
    <i data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" title="@lang('core::core.advanced_view.list_view_settings')" class="dropdown-toggle material-icons">view_column</i>

    <ul class="dropdown-menu pull-right">
        <li><a module-name="{{$moduleName}}" table-id="{{ $dataTable->getTableAttribute('id') }}" data-table-type="{{ get_class($dataTableDef) }}" class="advanced_views_settings" href="javascript:void(0);">@lang('core::core.advanced_view.create_new_view')</a></li>

        @if($currentView > 0 )

            @if(!empty($currentAdvView) && $currentAdvView->is_public && Auth::user()->hasPermissionTo('advanced_view.manage_public'))
                <li><a table-id="{{ $dataTable->getTableAttribute('id') }}" data-table-type="{{ get_class($dataTableDef) }}" data-id="{{ $currentView }}" class="edit-list-view" href="javascript:void(0);">@lang('core::core.advanced_view.edit_view')</a></li>
                <li><a data-id="{{ $currentView }}" class="delete-list-view" href="javascript:void(0);">@lang('core::core.advanced_view.delete')</a></li>
            @endif
            @if(!empty($currentAdvView) && !$currentAdvView->is_public)
                    <li><a table-id="{{ $dataTable->getTableAttribute('id') }}" data-table-type="{{ get_class($dataTableDef) }}" data-id="{{ $currentView }}" class="edit-list-view" href="javascript:void(0);">@lang('core::core.advanced_view.edit_view')</a></li>
                    <li><a data-id="{{ $currentView }}" class="delete-list-view" href="javascript:void(0);">@lang('core::core.advanced_view.delete')</a></li>
            @endif
        @endif

    </ul>

    <div class="view-list" >


        <select id="advanced_views_select" related-table="{{ $dataTable->getTableAttribute('id') }}" name="views" class="select2 advanced_views_select">

            <optgroup label="Public views">
                <option value="0">@lang('core::core.all_columns')</option>
                @foreach($listViews['public'] as $record)
                    <option @if($record->id == $currentView) selected="selected"  @endif value="{{ $record->id }}">{{ $record->view_name }}</option>
                @endforeach
            </optgroup>

            @if(count($listViews['private']) > 0 )
            <optgroup label="Private views">
                @foreach($listViews['private'] as $record)
                    <option @if($record->id == $currentView) selected="selected"  @endif value="{{ $record->id }}">{{ $record->view_name }}</option>
                @endforeach
            </optgroup>

            @endif

        </select>
    </div>



    @endif

</div>

@endif