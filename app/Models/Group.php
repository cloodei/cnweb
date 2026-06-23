<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public const ROLE_OWNER = 'owner';

    public const ROLE_EDITOR = 'editor';

    public const ROLE_VIEWER = 'viewer';

    protected $fillable = [
        'owner_id',
        'name',
        'description',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function itineraries()
    {
        return $this->hasMany(Itinerary::class);
    }

    public function invites()
    {
        return $this->hasMany(GroupInvite::class);
    }

    public function roleFor(User $user): ?string
    {
        if ($this->relationLoaded('members')) {
            $member = $this->members->firstWhere('id', $user->id);

            return $member?->pivot?->role;
        }

        return $this->members()
            ->where('users.id', $user->id)
            ->value('group_user.role');
    }

    public function hasMember(User $user): bool
    {
        return $this->roleFor($user) !== null;
    }

    public function canEditItineraries(User $user): bool
    {
        return in_array($this->roleFor($user), [self::ROLE_OWNER, self::ROLE_EDITOR], true);
    }
}
