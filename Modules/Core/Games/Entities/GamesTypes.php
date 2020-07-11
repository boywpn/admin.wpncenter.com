<?php

namespace Modules\Core\Games\Entities;

use GeneaLabs\LaravelModelCaching\CachedModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Traits\Commentable;
use Spatie\Activitylog\Traits\LogsActivity;

class GamesTypes extends Model
{
    use SoftDeletes, LogsActivity, Commentable;

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
        'name',
        'code',
        'is_active',
        'start_comm',
        'taking',
    ];

    public $table = 'core_games_types';

    public $fillable = [

    ];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function typesGame()
    {
        return $this->belongsTo(Games::class,'game_id');
    }

    public static function getIdFromCode($code){

        $type = self::where('code', $code)->first();
        if(!$type){
            return 0;
        }

        return $type->id;

    }

    public static function getTypeFromGame($game){

        $type = self::where('game_id', $game)
            ->select('id', 'name', 'code')
            ->get();

        return $type->toArray();

    }

    public static function getTypeByCode($code, $game_id){

        $type = self::where('game_id', $game_id)
            ->where('code', (string)$code)
            ->select('id', 'game_id', 'name', 'code', 'start_comm', 'taking')
            ->first();

        if($type){
            $data = $type->toArray();
        }else{
            $data = false;
        }

        return $data;

    }
}
