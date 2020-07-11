<div class="row">
    @widget('Modules\Platform\Core\Widgets\AutoGroupDictWidget',
    [
    'coll_class' => 'col-lg-2 col-md-2 col-sm-6 col-xs-6',
    'dict' =>'Modules\Campaigns\Entities\CampaignStatus',
    'moduleTable' =>'campaigns',
    'groupBy' => 'campaign_status_id'
    ]
    )
</div>
