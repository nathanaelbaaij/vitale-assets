<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    protected $table = 'logbooks';

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
