<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupLocation extends Model
{
    protected $fillable = [
        'group_id',
        'created_by',
        'name',
        'address',
        'description',
        'google_place_id',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scheduledStops()
    {
        return $this->hasMany(ScheduledStop::class);
    }

    public function mapSearchQuery(): string
    {
        if ($this->latitude !== null && $this->longitude !== null) {
            return $this->latitude.','.$this->longitude;
        }

        return trim($this->name.' '.($this->address ?? ''));
    }
}
