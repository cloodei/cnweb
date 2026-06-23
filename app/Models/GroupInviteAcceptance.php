<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupInviteAcceptance extends Model
{
    protected $fillable = [
        'group_invite_id',
        'user_id',
        'accepted_at',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
    ];

    public function invite()
    {
        return $this->belongsTo(GroupInvite::class, 'group_invite_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
