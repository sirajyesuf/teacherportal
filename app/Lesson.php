<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $table = 'lessons';

    public function lessonHourLogs()
    {
        return $this->hasOne(LessonLog::class, 'lesson_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'updated_by');
    }

    const SIFT = 1;
    const BTLANG = 2;
    const IM = 3;
    const SAND = 4;    
}
