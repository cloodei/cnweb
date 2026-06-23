<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    protected $fillable = [
        'group_id',
        'user_id',
        'title',
        'description',
        'start_date',
        'end_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'itinerary_location')
            ->withPivot('id', 'visit_time', 'note')
            ->withTimestamps();
    }
}
