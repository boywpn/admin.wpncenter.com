
    <div class="row">
        @widget('Modules\Platform\Core\Widgets\AutoGroupDictWidget',
        [
        'coll_class' => 'col-lg-2 col-md-2 col-sm-6 col-xs-6',
        'dict' =>'Modules\Leads\Entities\LeadStatus',
        'moduleTable' =>'leads',
        'groupBy' => 'lead_status_id',
        'max' => 6
        ]
        )
    </div>


