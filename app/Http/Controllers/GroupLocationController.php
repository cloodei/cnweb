<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupLocation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class GroupLocationController extends Controller
{
    public function index(Request $request, Group $group): View
    {
        Gate::authorize('view', $group);

        $query = $group->groupLocations()->with('creator')->latest();

        if ($request->filled('search')) {
            $search = (string) $request->string('search');

            $query->where(function ($locationQuery) use ($search) {
                $locationQuery->where('name', 'like', '%'.$search.'%')
                    ->orWhere('address', 'like', '%'.$search.'%');
            });
        }

        $locations = $query->paginate(9)->withQueryString();
        $membershipRole = $group->roleFor(Auth::user());

        return view('groups.destinations.index', compact('group', 'locations', 'membershipRole'));
    }

    public function create(Group $group): View
    {
        Gate::authorize('manageDestinations', $group);

        return view('groups.destinations.create', [
            'group' => $group,
            'googleMapsKey' => config('services.google_maps.browser_key'),
        ]);
    }

    public function store(Request $request, Group $group): RedirectResponse
    {
        Gate::authorize('manageDestinations', $group);

        $validated = $this->validateLocation($request);

        $group->groupLocations()->create([
            ...$validated,
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('groups.destinations.index', $group)
            ->with('success', 'Đã thêm địa điểm riêng cho nhóm.');
    }

    public function edit(Group $group, GroupLocation $groupLocation): View
    {
        $this->assertLocationInGroup($group, $groupLocation);
        Gate::authorize('manageDestinations', $group);

        return view('groups.destinations.edit', [
            'group' => $group,
            'groupLocation' => $groupLocation,
            'googleMapsKey' => config('services.google_maps.browser_key'),
        ]);
    }

    public function update(Request $request, Group $group, GroupLocation $groupLocation): RedirectResponse
    {
        $this->assertLocationInGroup($group, $groupLocation);
        Gate::authorize('manageDestinations', $group);

        $groupLocation->update($this->validateLocation($request));

        return redirect()
            ->route('groups.destinations.index', $group)
            ->with('success', 'Đã cập nhật địa điểm riêng của nhóm.');
    }

    public function destroy(Group $group, GroupLocation $groupLocation): RedirectResponse
    {
        $this->assertLocationInGroup($group, $groupLocation);
        Gate::authorize('manageDestinations', $group);

        $groupLocation->delete();

        return redirect()
            ->route('groups.destinations.index', $group)
            ->with('success', 'Đã xóa địa điểm riêng của nhóm.');
    }

    private function validateLocation(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'google_place_id' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);
    }

    private function assertLocationInGroup(Group $group, GroupLocation $groupLocation): void
    {
        abort_unless($groupLocation->group_id === $group->id, 404);
    }
}
