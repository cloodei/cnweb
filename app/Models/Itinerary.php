<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    protected $fillable = [
        'user_id', 
        'title', 
        'description', 
        'start_date', 
        'end_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'itinerary_location')
                    ->withPivot('visit_time', 'note')
                    ->withTimestamps();
    }
}