<?php

namespace App\Models\Old;

use GeneaLabs\LaravelModelCaching\CachedModel;
use Illuminate\Database\Eloquent\Model;

class Members extends Model
{

    protected $connection = 'mysql2';

    public $timestamps = false;

    public $table = 'member';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function membersDomain()
    {
        return $this->belongsTo(Domains::class, 'domain');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function membersAgent()
    {
        return $this->belongsTo(Agents::class, 'agent');
    }

    public function order()
    {
        return $this->hasMany(OrdersLog::class, 'member_id', 'id');
    }

    public function username()
    {
        return $this->hasMany(Username::class, 'member_id', 'id');
    }
}
