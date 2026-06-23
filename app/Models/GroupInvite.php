<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupInvite extends Model
{
    protected $fillable = [
        'group_id',
        'created_by',
        'token_hash',
        'role',
        'expires_at',
        'max_uses',
        'uses_count',
        'revoked_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    protected $hidden = [
        'token_hash',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function acceptances()
    {
        return $this->hasMany(GroupInviteAcceptance::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isRevoked(): bool
    {
        return $this->revoked_at !== null;
    }

    public function hasUsesRemaining(): bool
    {
        return $this->uses_count < $this->max_uses;
    }

    public function isAcceptable(): bool
    {
        return ! $this->isExpired()
            && ! $this->isRevoked()
            && $this->hasUsesRemaining();
    }
}
