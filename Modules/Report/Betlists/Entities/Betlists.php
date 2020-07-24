<?php

namespace Modules\Report\Betlists\Entities;

use GeneaLabs\LaravelModelCaching\CachedModel;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Agents\Entities\Agents;
use Modules\Core\Boards\Entities\Boards;
use Modules\Core\Games\Entities\Games;
use Modules\Core\Games\Entities\GamesTypes;
use Modules\Core\Username\Entities\Username;
use Modules\Member\Members\Entities\Members;
use Modules\Platform\Core\Traits\Commentable;
use Modules\Platform\Core\Traits\FunctionalTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class Betlists extends Model
{
    use SoftDeletes, BelongsToTenants, FunctionalTrait;

    protected $connection = 'db_report';

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

    public $table = 'report_betlists';

    public $fillable = [

    ];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function listsMember()
    {
        return $this->belongsTo(Members::class, 'member_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function listsUsername()
    {
        return $this->belongsTo(Username::class, 'username_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function listsGame()
    {
        return $this->belongsTo(Games::class, 'board_game_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function listsGameType()
    {
        return $this->belongsTo(GamesTypes::class, 'game_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function listsResult()
    {
        return $this->hasOne(BetlistsResults::class, 'betlist_id');
    }

}
