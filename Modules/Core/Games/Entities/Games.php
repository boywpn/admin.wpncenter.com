<?php

namespace Modules\Core\Games\Entities;

use GeneaLabs\LaravelModelCaching\CachedModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Boards\Entities\Boards;
use Modules\Platform\Core\Traits\Commentable;
use Spatie\Activitylog\Traits\LogsActivity;

class Games extends Model
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
        'is_commission',
        'taking',
    ];

    public $table = 'core_games';

    public $fillable = [
    ];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    public static function getSelectOption(){
        $main = Games::all();

        $data = [];
        foreach ($main as $main){
            $data[] = [
                'value' => $main->id,
                'label' => $main->name
            ];
        }

        return $data;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function typesGame()
    {
        return $this->hasMany(GamesTypes::class, 'game_id');
    }
    public function boardsGame()
    {
        return $this->hasMany(Boards::class, 'game_id');
    }


    public static function getGameComm(){

        $game = self::where('is_active', 1)
            ->where('is_commission', 1)
            ->with(['typesGame' => function($query){
                $query->where('is_active', 1)->where('is_commission', 1)->select('*');
            }])
            ->get();

        return $game->toArray();

    }

    public static function getGamesById($id, $where){

        $game = self::where('id', $id)
            ->where('is_active', 1)
            ->with(['typesGame' => function($query) use ($where){
                $query->where('is_active', 1)->where($where)->select('*');
            }])
            ->first();

        return $game->toArray();

    }
}
