<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'stars',
        'status',
        'description',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }
}
