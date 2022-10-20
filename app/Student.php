<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone',
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'LIKE', "%$search%");
    }
}
