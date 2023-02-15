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
}
