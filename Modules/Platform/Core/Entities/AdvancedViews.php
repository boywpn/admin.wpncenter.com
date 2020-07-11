<?php

namespace Modules\Platform\Core\Entities;

use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Modules\Platform\Companies\Entities\Company;


/**
 * Modules\Platform\Core\Entities\AdvancedViews
 *
 * @property int $id
 * @property string|null $view_name
 * @property string|null $module_name
 * @property int|null $is_public
 * @property int|null $is_accepted
 * @property int|null $is_default
 * @property int $company_id
 * @property string|null $defined_columns
 * @property string|null $filter_rules
 * @property int|null $owner_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Modules\Platform\Companies\Entities\Company $company
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\AdvancedViews whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\AdvancedViews whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\AdvancedViews whereDefinedColumns($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\AdvancedViews whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\AdvancedViews whereFilterRules($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\AdvancedViews whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\AdvancedViews whereIsAccepted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\AdvancedViews whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\AdvancedViews whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\AdvancedViews whereModuleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\AdvancedViews whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\AdvancedViews whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\AdvancedViews whereViewName($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\AdvancedViews newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\AdvancedViews newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\AdvancedViews query()
 */
class AdvancedViews extends Model
{

    use BelongsToTenants;

    protected $fillable = [
        'view_name',
        'module_name',
        'is_public',
        'is_accepted',
        'is_default',
        'company_id',
        'defined_columns',
        'filter_rules',
        'owner_id'
    ];

    public $table = 'bap_advanced_views';

    protected $casts = [

    ];

    public function isVisible(){

        if($this->is_public && $this->is_accepted){
            return true;
        }
        if(!$this->is_public && $this->owner_id == \Auth::user()->id){
            return true;
        }

    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
