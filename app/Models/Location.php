<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'description',
        'image',
        'address',
        'place_provider',
        'place_id',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mapSearchQuery(): string
    {
        if ($this->latitude !== null && $this->longitude !== null) {
            return $this->latitude.','.$this->longitude;
        }

        return collect([$this->name, $this->address])
            ->filter(fn ($value) => filled($value))
            ->implode(' ');
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

    public function mapEmbedUrl(): ?string
    {
        if (config('services.maps.provider') === 'google') {
            return 'https://maps.google.com/maps?'.http_build_query([
                'q' => $this->mapSearchQuery(),
                't' => '',
                'z' => 15,
                'ie' => 'UTF8',
                'iwloc' => '',
                'output' => 'embed',
            ]);
        }

        if ($this->latitude === null || $this->longitude === null) {
            return null;
        }

        $latitude = (float) $this->latitude;
        $longitude = (float) $this->longitude;
        $delta = 0.01;

        return 'https://www.openstreetmap.org/export/embed.html?'.http_build_query([
            'bbox' => implode(',', [
                $longitude - $delta,
                $latitude - $delta,
                $longitude + $delta,
                $latitude + $delta,
            ]),
            'layer' => 'mapnik',
            'marker' => $latitude.','.$longitude,
        ]);
    }
}
