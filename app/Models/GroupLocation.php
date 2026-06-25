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
        'place_provider',
        'place_id',
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

    public function mapUrl(): string
    {
        if (config('services.maps.provider') === 'google') {
            return 'https://www.google.com/maps/search/?'.http_build_query([
                'api' => 1,
                'query' => $this->mapSearchQuery(),
            ]);
        }

        if ($this->latitude !== null && $this->longitude !== null) {
            return sprintf(
                'https://www.openstreetmap.org/?mlat=%s&mlon=%s#map=16/%s/%s',
                $this->latitude,
                $this->longitude,
                $this->latitude,
                $this->longitude,
            );
        }

        return 'https://www.openstreetmap.org/search?'.http_build_query([
            'query' => $this->mapSearchQuery(),
        ]);
    }
}
