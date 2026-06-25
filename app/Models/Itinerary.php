<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    protected $fillable = [
        'group_id',
        'user_id',
        'location_id',
        'group_location_id',
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

    public function primaryLocation()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function primaryGroupLocation()
    {
        return $this->belongsTo(GroupLocation::class, 'group_location_id');
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'itinerary_location')
            ->withPivot('id', 'visit_time', 'note')
            ->withTimestamps();
    }

    public function scheduledStops()
    {
        return $this->hasMany(ScheduledStop::class);
    }

    public function destination()
    {
        return $this->primaryGroupLocation ?? $this->primaryLocation;
    }

    public function destinationRef(): ?string
    {
        if ($this->group_location_id) {
            return 'group:'.$this->group_location_id;
        }

        if ($this->location_id) {
            return 'shared:'.$this->location_id;
        }

        return null;
    }

    public function destinationName(): ?string
    {
        return $this->destination()?->name;
    }

    public function destinationAddress(): ?string
    {
        return $this->destination()?->address;
    }

    public function destinationMapQuery(): string
    {
        $destination = $this->destination();

        return $destination?->mapSearchQuery() ?? '';
    }

    public function destinationMapUrl(): string
    {
        $destination = $this->destination();

        if (! $destination) {
            return '';
        }

        return method_exists($destination, 'mapUrl')
            ? $destination->mapUrl()
            : '';
    }

    public function destinationSourceLabel(): ?string
    {
        if ($this->group_location_id) {
            return 'Riêng của nhóm';
        }

        if ($this->location_id) {
            return 'Kho chung';
        }

        return null;
    }
}
