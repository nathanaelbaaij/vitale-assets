<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'image_url', 'user_id', 'active',
    ];

    /**
     * get the parent category from the current category
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function avatarRelation()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
