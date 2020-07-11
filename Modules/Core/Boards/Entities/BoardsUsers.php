<?php

namespace Modules\Core\Boards\Entities;

use GeneaLabs\LaravelModelCaching\CachedModel;
use Spatie\Activitylog\Traits\LogsActivity;

class BoardsUsers extends CachedModel
{
    use LogsActivity;

    const COLORS = [
        0 => 'bg-red',
        1 => 'bg-green'
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
        'username',
        'password',
        'code',
        'usersBoard.name',
        'is_active',
    ];

    public $table = 'core_boards_users';

    public $fillable = [
        'username',
        'password',
        'code',
        'board_id',
        'is_active',
    ];

    protected $dates = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usersBoard()
    {
        return $this->belongsTo(Boards::class,'board_id');
    }

}
