<?php

namespace App\Models\Old;

use GeneaLabs\LaravelModelCaching\CachedModel;

class Domains extends CachedModel
{

    protected $connection = 'mysql2';

    public $table = 'domain';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function domainAgent()
    {
        return $this->hasMany(Agents::class, 'domain');
    }

}
