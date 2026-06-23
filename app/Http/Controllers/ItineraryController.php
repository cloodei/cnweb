<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Itinerary;
use App\Models\Location;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ItineraryController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('groups.index');
    }

    public function create(Group $group): View
    {
        Gate::authorize('createItinerary', $group);

        return view('itineraries.create', compact('group'));
    }

    public function store(Request $request, Group $group): RedirectResponse
    {
        Gate::authorize('createItinerary', $group);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ], [
            'end_date.after_or_equal' => 'Ngày kết thúc không được nhỏ hơn ngày bắt đầu.',
        ]);

        $itinerary = $group->itineraries()->create([
            ...$validated,
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
            ->route('groups.show', $group)
            ->with('success', 'Đã xóa lịch trình!');
    }

    public function show(Group $group, Itinerary $itinerary): View
    {
        $this->assertItineraryInGroup($group, $itinerary);
        Gate::authorize('view', $itinerary);

        $scheduledLocations = $itinerary->locations()->orderBy('pivot_visit_time', 'asc')->get();
        $allLocations = Location::orderBy('name', 'asc')->get();
        $membershipRole = $group->roleFor(Auth::user());

        return view('itineraries.show', compact('group', 'itinerary', 'scheduledLocations', 'allLocations', 'membershipRole'));
    }

    public function addLocation(Request $request, Group $group, Itinerary $itinerary): RedirectResponse
    {
        $this->assertItineraryInGroup($group, $itinerary);
        Gate::authorize('manageStops', $itinerary);

        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'visit_time' => 'nullable|date',
            'note' => 'nullable|string|max:2000',
        ]);

        $itinerary->locations()->attach($validated['location_id'], [
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

        $deleted = DB::table('itinerary_location')
            ->where('id', $stop)
            ->where('itinerary_id', $itinerary->id)
            ->delete();

        if ($deleted === 0) {
            abort(404);
        }

        return redirect()->back()->with('success', 'Đã gỡ địa điểm khỏi lộ trình!');
    }

    public function downloadPdf(Group $group, Itinerary $itinerary)
    {
        $this->assertItineraryInGroup($group, $itinerary);
        Gate::authorize('downloadPdf', $itinerary);

        $scheduledLocations = $itinerary->locations()->orderBy('pivot_visit_time', 'asc')->get();

        $pdf = Pdf::loadView('itineraries.pdf', compact('group', 'itinerary', 'scheduledLocations'));
        $fileName = 'Lich-trinh-'.Str::slug($itinerary->title).'.pdf';

        return $pdf->download($fileName);
    }

    public function edit(Group $group, Itinerary $itinerary): View
    {
        $this->assertItineraryInGroup($group, $itinerary);
        Gate::authorize('update', $itinerary);

        return view('itineraries.edit', compact('group', 'itinerary'));
    }

    public function update(Request $request, Group $group, Itinerary $itinerary): RedirectResponse
    {
        $this->assertItineraryInGroup($group, $itinerary);
        Gate::authorize('update', $itinerary);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ], [
            'end_date.after_or_equal' => 'Ngày kết thúc không được nhỏ hơn ngày bắt đầu.',
        ]);

        $itinerary->update($validated);

        return redirect()
            ->route('groups.itineraries.show', [$group, $itinerary])
            ->with('success', 'Đã cập nhật thông tin chuyến đi!');
    }

    private function assertItineraryInGroup(Group $group, Itinerary $itinerary): void
    {
        abort_unless($itinerary->group_id === $group->id, 404);
    }
}
