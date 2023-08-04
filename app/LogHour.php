<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogHour extends Model
{
    use SoftDeletes;
    
    protected $table = 'add_hour_logs';
}
