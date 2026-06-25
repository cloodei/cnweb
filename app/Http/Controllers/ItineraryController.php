<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupLocation;
use App\Models\Itinerary;
use App\Models\Location;
use App\Models\ScheduledStop;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ItineraryController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('groups.index');
    }

    public function groupIndex(Request $request, Group $group): View
    {
        Gate::authorize('view', $group);

        $query = $group->itineraries()
            ->with(['creator', 'primaryLocation', 'primaryGroupLocation'])
            ->withCount('scheduledStops');

        if ($request->filled('search')) {
            $search = (string) $request->string('search');

            $query->where(function ($itineraryQuery) use ($search) {
                $itineraryQuery->where('title', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        match ($request->query('status')) {
            'upcoming' => $query->whereDate('start_date', '>', today()),
            'active' => $query->whereDate('start_date', '<=', today())->whereDate('end_date', '>=', today()),
            'past' => $query->whereDate('end_date', '<', today()),
            default => null,
        };

        $itineraries = $query
            ->orderBy('start_date')
            ->paginate(9)
            ->withQueryString();
        $membershipRole = $group->roleFor(Auth::user());

        return view('itineraries.index', compact('group', 'itineraries', 'membershipRole'));
    }

    public function create(Group $group): View
    {
        Gate::authorize('createItinerary', $group);

        [$groupLocations, $sharedLocations] = $this->destinationChoices($group);

        return view('itineraries.create', compact('group', 'groupLocations', 'sharedLocations'));
    }

    public function store(Request $request, Group $group): RedirectResponse
    {
        Gate::authorize('createItinerary', $group);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'destination_ref' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ], [
            'end_date.after_or_equal' => 'Ngày kết thúc không được nhỏ hơn ngày bắt đầu.',
        ]);
        $destination = $this->resolveOptionalDestination($group, $validated['destination_ref'] ?? null);
        unset($validated['destination_ref']);

        $itinerary = $group->itineraries()->create([
            ...$validated,
            ...$destination,
            'user_id' => Auth::id(),
        ]);

        return redirect()
            ->route('groups.itineraries.show', [$group, $itinerary])
            ->with('success', 'Đã tạo lịch trình mới!');
    }

    public function destroy(Group $group, Itinerary $itinerary): RedirectResponse
    {
        $this->assertItineraryInGroup($group, $itinerary);
        Gate::authorize('delete', $itinerary);

        $itinerary->delete();

        return redirect()
            ->route('groups.itineraries.index', $group)
            ->with('success', 'Đã xóa lịch trình!');
    }

    public function show(Group $group, Itinerary $itinerary): View
    {
        $this->assertItineraryInGroup($group, $itinerary);
        Gate::authorize('view', $itinerary);

        $scheduledStops = $itinerary->scheduledStops()
            ->with(['location', 'groupLocation'])
            ->orderBy('visit_time')
            ->get();
        $itinerary->loadMissing(['primaryLocation', 'primaryGroupLocation']);
        $membershipRole = $group->roleFor(Auth::user());

        return view('itineraries.show', compact('group', 'itinerary', 'scheduledStops', 'membershipRole'));
    }

    public function createStop(Request $request, Group $group, Itinerary $itinerary): View
    {
        $this->assertItineraryInGroup($group, $itinerary);
        Gate::authorize('manageStops', $itinerary);

        $search = (string) $request->string('search');
        $groupLocationQuery = $group->groupLocations()->orderBy('name');
        $sharedLocationQuery = Location::orderBy('name');

        if ($search !== '') {
            $groupLocationQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('address', 'like', '%'.$search.'%');
            });
            $sharedLocationQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('address', 'like', '%'.$search.'%');
            });
        }

        $groupLocations = $groupLocationQuery->get();
        $sharedLocations = $sharedLocationQuery->limit(30)->get();
        $membershipRole = $group->roleFor(Auth::user());

        return view('itineraries.add-stop', compact('group', 'itinerary', 'groupLocations', 'sharedLocations', 'membershipRole'));
    }

    public function addLocation(Request $request, Group $group, Itinerary $itinerary): RedirectResponse
    {
        $this->assertItineraryInGroup($group, $itinerary);
        Gate::authorize('manageStops', $itinerary);

        $validated = $request->validate([
            'destination_ref' => 'required|string',
            'visit_time' => 'nullable|date',
            'note' => 'nullable|string|max:2000',
        ]);

        $destination = $this->resolveDestination($group, $validated['destination_ref']);

        ScheduledStop::create([
            'itinerary_id' => $itinerary->id,
            'location_id' => $destination['location_id'],
            'group_location_id' => $destination['group_location_id'],
            'visit_time' => $validated['visit_time'] ?? null,
            'note' => $validated['note'] ?? null,
        ]);

        return redirect()
            ->route('groups.itineraries.show', [$group, $itinerary])
            ->with('success', 'Đã thêm địa điểm vào lộ trình chuyến đi!');
    }

    public function removeStop(Group $group, Itinerary $itinerary, int $stop): RedirectResponse
    {
        $this->assertItineraryInGroup($group, $itinerary);
        Gate::authorize('manageStops', $itinerary);

        $itinerary->scheduledStops()->findOrFail($stop)->delete();

        return redirect()->back()->with('success', 'Đã gỡ địa điểm khỏi lộ trình!');
    }

    public function downloadPdf(Group $group, Itinerary $itinerary)
    {
        $this->assertItineraryInGroup($group, $itinerary);
        Gate::authorize('downloadPdf', $itinerary);

        $scheduledStops = $itinerary->scheduledStops()
            ->with(['location', 'groupLocation'])
            ->orderBy('visit_time')
            ->get();
        $itinerary->loadMissing(['primaryLocation', 'primaryGroupLocation']);

        $pdf = Pdf::loadView('itineraries.pdf', compact('group', 'itinerary', 'scheduledStops'));
        $fileName = 'Lich-trinh-'.Str::slug($itinerary->title).'.pdf';

        return $pdf->download($fileName);
    }

    public function edit(Group $group, Itinerary $itinerary): View
    {
        $this->assertItineraryInGroup($group, $itinerary);
        Gate::authorize('update', $itinerary);

        [$groupLocations, $sharedLocations] = $this->destinationChoices($group);

        return view('itineraries.edit', compact('group', 'itinerary', 'groupLocations', 'sharedLocations'));
    }

    public function update(Request $request, Group $group, Itinerary $itinerary): RedirectResponse
    {
        $this->assertItineraryInGroup($group, $itinerary);
        Gate::authorize('update', $itinerary);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'destination_ref' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ], [
            'end_date.after_or_equal' => 'Ngày kết thúc không được nhỏ hơn ngày bắt đầu.',
        ]);
        $destination = $this->resolveOptionalDestination($group, $validated['destination_ref'] ?? null);
        unset($validated['destination_ref']);

        $itinerary->update([
            ...$validated,
            ...$destination,
        ]);

        return redirect()
            ->route('groups.itineraries.show', [$group, $itinerary])
            ->with('success', 'Đã cập nhật thông tin chuyến đi!');
    }

    private function assertItineraryInGroup(Group $group, Itinerary $itinerary): void
    {
        abort_unless($itinerary->group_id === $group->id, 404);
    }

    private function destinationChoices(Group $group): array
    {
        return [
            $group->groupLocations()->orderBy('name')->get(),
            Location::orderBy('name')->limit(100)->get(),
        ];
    }

    private function resolveOptionalDestination(Group $group, ?string $destinationRef): array
    {
        if (! filled($destinationRef)) {
            return [
                'location_id' => null,
                'group_location_id' => null,
            ];
        }

        return $this->resolveDestination($group, $destinationRef);
    }

    private function resolveDestination(Group $group, string $destinationRef): array
    {
        if (! str_contains($destinationRef, ':')) {
            throw ValidationException::withMessages([
                'destination_ref' => 'Vui lòng chọn địa điểm hợp lệ.',
            ]);
        }

        [$source, $id] = explode(':', $destinationRef, 2);
        $id = (int) $id;

        if ($source === 'shared' && Location::whereKey($id)->exists()) {
            return [
                'location_id' => $id,
                'group_location_id' => null,
            ];
        }

        if ($source === 'group' && GroupLocation::where('group_id', $group->id)->whereKey($id)->exists()) {
            return [
                'location_id' => null,
                'group_location_id' => $id,
            ];
        }

        throw ValidationException::withMessages([
            'destination_ref' => 'Địa điểm không tồn tại hoặc không thuộc nhóm này.',
        ]);
    }
}
