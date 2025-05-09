<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\AnnouncementRecipient;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password','role','role_type','phone','color'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Always hash the password when we save it to the database
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('first_name', 'LIKE', "%$search%");
    }

    /**
     * Scope to only include students.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStudents($query)
    {
        return $query->where('role_type', 3);
    }

    /**
     * Scope to only include staff users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStaff($query)
    {
        return $query->where('role_type', 2);
    }

    public function receivedAnnouncements()
    {
        return $this->hasMany(AnnouncementRecipient::class, 'user_id')
            ->orderByDesc('created_at')
            ->with('announcement');
    }

    public function createdAnnouncements()
    {
        return $this->hasMany(Announcement::class, 'trainer_id')
            ->orderByDesc('created_at');            
    }
}
