<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consequence extends Model
{
    /**
     * @var string
     */
    protected $table = 'consequences';

    /**
     * @var array
     */
    protected $fillable = [
        "id", "description"
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cascades()
    {
        return $this->hasMany('App\Cascade', 'consequence_id', 'id');
    }
}
