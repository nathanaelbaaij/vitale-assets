<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogbookReverted extends Model
{
    protected $table = 'logbook_reverteds';

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
