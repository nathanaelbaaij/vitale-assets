<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cascade extends Model
{
    /**
     * @var string
     */
    protected $table = 'cascades';

    /**
     * @var array
     */
    protected $fillable = [
        "id", "asset_id_from", "asset_id_to", "consequence_id"
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assetFrom()
    {
        return $this->belongsTo('App\Asset', 'asset_id_from', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assetTo()
    {
        return $this->belongsTo('App\Asset', 'asset_id_to', 'id');
    }

    public function consequence()
    {
        return $this->belongsTo('App\Consequence', 'consequence_id', 'id');
    }
}
