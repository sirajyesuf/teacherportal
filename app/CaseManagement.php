<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CaseManagement extends Model
{
    use SoftDeletes;
    
    protected $table = 'case_management_meeting';

    public function user()
    {
        return $this->belongsTo(User::class,'trainer_id');
    }
}
