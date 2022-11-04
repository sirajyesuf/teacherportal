<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tls extends Model
{
    use SoftDeletes;
    
    protected $table = 'tls';
}
