<div class="row">
    @widget('Modules\Platform\Core\Widgets\AutoGroupDictWidget',
        [
            'coll_class' => 'col-lg-2 col-md-2 col-sm-6 col-xs-6',
            'dict' =>'Modules\Contacts\Entities\ContactStatus',
            'moduleTable' =>'contacts',
            'groupBy' => 'contact_status_id'
        ]
    )
</div>
