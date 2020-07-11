<div class="table-responsive col-lg-12 col-md-12">
<table class="table table-striped table-condensed">
    <tr>
        <th>@lang('core::core.table.updated_by')</th>
        <th colspan="2">{{ $entity->causer->name }} @lang('core::core.table.@') {{ \Modules\Platform\Core\Helper\UserHelper::formatUserDateTime($entity->created_at) }}</th>
    </tr>

    @if(count($entity->changes()) > 0 )

        <tr>
            <th>
                {{ trans('core::core.table.activity_log_property') }}
            </th>


            <th>
                {{ trans('core::core.table.activity_log_old') }}
            </th>

            <th>
                {{ trans('core::core.table.activity_log_after') }}
            </th>
        </tr>
        @foreach($entity->changes()->get('attributes') as $key => $value)
            <tr>
                <td>
                    {{ $key  }}
                </td>

                <td>
                    {{ $entity->changes()->get('old')[$key] }}


                </td>
                <td>
                    @if($value != $entity->changes()->get('old')[$key] )
                        <span class="color-red">
                              {{ $value }}
                        </span>
                    @else
                        {{ $value }}
                    @endif
                </td>
            </tr>
        @endforeach

    @endif


</table>
</div>

