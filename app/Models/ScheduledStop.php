<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduledStop extends Model
{
    protected $table = 'itinerary_location';

    protected $fillable = [
        'itinerary_id',
        'location_id',
        'group_location_id',
        'visit_time',
        'note',
    ];

    protected $casts = [
        'visit_time' => 'datetime',
    ];

    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function groupLocation()
    {
        return $this->belongsTo(GroupLocation::class);
    }

    public function destination()
    {
        return $this->groupLocation ?? $this->location;
    }

    public function destinationName(): string
    {
        return $this->destination()?->name ?? 'Địa điểm đã bị xóa';
    }

    public function destinationAddress(): ?string
    {
        return $this->destination()?->address;
    }

    public function destinationDescription(): ?string
    {
        return $this->destination()?->description;
    }

    public function destinationMapQuery(): string
    {
        $destination = $this->destination();

        if (! $destination) {
            return '';
        }

        return method_exists($destination, 'mapSearchQuery')
            ? $destination->mapSearchQuery()
            : trim($destination->name.' '.($destination->address ?? ''));
    }

    public function sourceLabel(): string
    {
        return $this->group_location_id ? 'Riêng của nhóm' : 'Kho chung';
    }
}
