<?php

namespace Modules\Report\Commission\Entities;

use GeneaLabs\LaravelModelCaching\CachedModel;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Games\Entities\Games;
use Modules\Core\Username\Entities\Username;
use Modules\Member\Members\Entities\Members;
use Modules\Platform\Core\Traits\FunctionalTrait;
use Modules\Report\Betlists\Entities\BetlistsResults;

class Commission extends Model
{
    use FunctionalTrait;

    const COLORS = [
        0 => 'bg-red',
        1 => 'bg-green'
    ];

    /**
     * For Remove Form
    */
    const FORM_REMOVE_CREATE = [

    ];
    const FORM_REMOVE_EDIT = [
        // 'password'
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    protected static $logAttributes = [

    ];

    public $table = 'report_commissions';

    public $fillable = [

    ];

    protected $dates = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function commMember()
    {
        return $this->belongsTo(Members::class, 'member_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function commUsername()
    {
        return $this->belongsTo(Username::class, 'username_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function commGame()
    {
        return $this->belongsTo(Games::class, 'game_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function commResult()
    {
        return $this->hasMany(BetlistsResults::class, 'trans_commission_id');
    }
    public function commResults()
    {
        return $this->hasMany(BetlistsResults::class, 'betlist_id','betlist_id');
    }

}
