<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupInvite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GroupInviteController extends Controller
{
    public function store(Request $request, Group $group): RedirectResponse
    {
        Gate::authorize('manageInvites', $group);

        $validated = $request->validate([
            'role' => ['required', Rule::in([Group::ROLE_EDITOR, Group::ROLE_VIEWER])],
            'duration' => ['required', Rule::in(['1_hour', '24_hours', '7_days', '30_days'])],
            'max_uses' => 'required|integer|min:1|max:100',
        ]);

        $token = Str::random(48);
        $expiresAt = match ($validated['duration']) {
            '1_hour' => now()->addHour(),
            '24_hours' => now()->addDay(),
            '7_days' => now()->addDays(7),
            '30_days' => now()->addDays(30),
        };

        $group->invites()->create([
            'created_by' => Auth::id(),
            'token_hash' => hash('sha256', $token),
            'role' => $validated['role'],
            'expires_at' => $expiresAt,
            'max_uses' => $validated['max_uses'],
        ]);

        return back()
            ->with('success', 'Đã tạo link mời nhóm.')
            ->with('createdInviteUrl', route('group-invites.show', $token));
    }

    public function destroy(Group $group, GroupInvite $invite): RedirectResponse
    {
        Gate::authorize('manageInvites', $group);
        abort_unless($invite->group_id === $group->id, 404);

        $invite->update(['revoked_at' => now()]);

        return back()->with('success', 'Đã thu hồi link mời.');
    }

    public function show(string $token): View
    {
        $invite = $this->findInviteByToken($token);
        $invite->load(['group.owner']);
        $user = Auth::user();
        $alreadyMember = $invite->group->hasMember($user);
        $canAccept = $invite->isAcceptable() && ! $alreadyMember;

        return view('groups.invite', compact('invite', 'token', 'alreadyMember', 'canAccept'));
    }

    public function accept(string $token): RedirectResponse
    {
        $tokenHash = hash('sha256', $token);
        $user = Auth::user();

        $group = DB::transaction(function () use ($tokenHash, $user) {
            $invite = GroupInvite::where('token_hash', $tokenHash)
                ->lockForUpdate()
                ->firstOrFail();

            $invite->load('group');

            if ($invite->group->hasMember($user)) {
                return $invite->group;
            }

            if (! $invite->isAcceptable()) {
                return null;
            }

            $invite->group->members()->attach($user->id, [
                'role' => $invite->role,
            ]);

            $invite->increment('uses_count');

            $invite->acceptances()->create([
                'user_id' => $user->id,
                'accepted_at' => now(),
            ]);

            return $invite->group;
        });

        if (! $group) {
            return redirect()
                ->route('groups.index')
                ->with('error', 'Link mời đã hết hạn, bị thu hồi hoặc đã dùng hết lượt.');
        }

        return redirect()
            ->route('groups.show', $group)
            ->with('success', 'Bạn đã tham gia nhóm.');
    }

    private function findInviteByToken(string $token): GroupInvite
    {
        return GroupInvite::where('token_hash', hash('sha256', $token))->firstOrFail();
    }
}
