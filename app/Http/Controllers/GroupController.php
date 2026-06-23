<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class GroupController extends Controller
{
    public function index(): View
    {
        $groups = Auth::user()
            ->groups()
            ->with('owner')
            ->withCount(['members', 'itineraries'])
            ->orderBy('groups.updated_at', 'desc')
            ->get();

        return view('groups.index', compact('groups'));
    }

    public function create(): View
    {
        return view('groups.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
        ]);

        $group = DB::transaction(function () use ($validated) {
            $group = Group::create([
                ...$validated,
                'owner_id' => Auth::id(),
            ]);

            $group->members()->attach(Auth::id(), [
                'role' => Group::ROLE_OWNER,
            ]);

            return $group;
        });

        return redirect()
            ->route('groups.show', $group)
            ->with('success', 'Đã tạo nhóm lập lịch trình.');
    }

    public function show(Group $group): View
    {
        Gate::authorize('view', $group);

        $group->load([
            'owner',
            'members' => fn ($query) => $query
                ->orderByRaw("case group_user.role when 'owner' then 1 when 'editor' then 2 else 3 end")
                ->orderBy('name'),
        ]);

        $itineraries = $group->itineraries()
            ->with('creator')
            ->withCount('locations')
            ->orderBy('start_date')
            ->get();

        $invites = Gate::allows('manageInvites', $group)
            ? $group->invites()->with('createdBy')->latest()->get()
            : collect();

        $membershipRole = $group->roleFor(Auth::user());

        return view('groups.show', compact('group', 'itineraries', 'invites', 'membershipRole'));
    }

    public function edit(Group $group): View
    {
        Gate::authorize('update', $group);

        return view('groups.edit', compact('group'));
    }

    public function update(Request $request, Group $group): RedirectResponse
    {
        Gate::authorize('update', $group);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
        ]);

        $group->update($validated);

        return redirect()
            ->route('groups.show', $group)
            ->with('success', 'Đã cập nhật nhóm.');
    }

    public function destroy(Group $group): RedirectResponse
    {
        Gate::authorize('delete', $group);

        $group->delete();

        return redirect()
            ->route('groups.index')
            ->with('success', 'Đã xóa nhóm và các lịch trình bên trong.');
    }
}
