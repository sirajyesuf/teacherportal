<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'trainer_id', 'title', 'content','is_all',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function recipients()
    {
        return $this->hasMany(AnnouncementRecipient::class);
    }
}
