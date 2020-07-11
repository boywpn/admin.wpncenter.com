@if($datatable->hasQueryFilters)
    <a data-filter-id="queryFilter_{{ $datatable->getTableAttribute('id') }}" href="#" title="@lang('core::core.advanced_view.advanced_filters')" class="btn btn-primary btn-xs btn-crud btn-show-filters">@lang('core::core.advanced_view.advanced_filters')</a>

    <div data-table-id="{{ $datatable->getTableAttribute('id') }}" style="display: none" class="query-filter-builder" id="queryFilter_{{ $datatable->getTableAttribute('id') }}">
        <button class="btn btn-primary btn-xs btn-get">@lang('core::core.apply_filters')</button>
        <button class="btn btn-warning btn-xs btn-reset">@lang('core::core.reset')</button>
        <button class="btn btn-warning btn-xs btn-hide" >@lang('core::core.close')</button>
    </div>

@endif
