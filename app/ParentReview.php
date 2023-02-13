<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParentReview extends Model
{
    use SoftDeletes;
    
    protected $table = 'parent_review_session';

    public function user()
    {
        return $this->belongsTo(User::class,'trainer_id');
    }
}
