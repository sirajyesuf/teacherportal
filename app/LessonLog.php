<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LessonLog extends Model
{
    use SoftDeletes;
    
    protected $table = 'lesson_hour_logs';

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'created_by');
    }
}
