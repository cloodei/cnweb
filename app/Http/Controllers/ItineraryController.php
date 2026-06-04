<?php

namespace App\Http\Controllers;

use App\Models\Itinerary;
use App\Models\Location;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ItineraryController extends Controller
{
    public function index(): View
    {
        $itineraries = Auth::user()->itineraries()->latest()->get();
        return view('itineraries.index', compact('itineraries'));
    }

    public function create(): View
    {
        return view('itineraries.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ], [
            'end_date.after_or_equal' => 'Ngày kết thúc không được nhỏ hơn ngày bắt đầu.',
        ]);

        Auth::user()->itineraries()->create($validated);
        return redirect()->route('itineraries.index')->with('success', 'Đã tạo lịch trình mới!');
    }

    public function destroy(Itinerary $itinerary): RedirectResponse
    {
        $this->authorizeItineraryOwner($itinerary, 'Bạn không có quyền xóa lịch trình này.');

        $itinerary->delete($itinerary->id);

        return redirect()->route('itineraries.index')->with('success', 'Đã xóa lịch trình!');
    }

    public function show(Itinerary $itinerary): View
    {
        $this->authorizeItineraryOwner($itinerary, 'Bạn không có quyền xem lịch trình này.');

        $scheduledLocations = $itinerary->locations()->orderBy('pivot_visit_time', 'asc')->get();
        $allLocations = Location::orderBy('name', 'asc')->get();

        return view('itineraries.show', compact('itinerary', 'scheduledLocations', 'allLocations'));
    }

    public function addLocation(Request $request, Itinerary $itinerary): RedirectResponse
    {
        $this->authorizeItineraryOwner($itinerary);

        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'visit_time' => 'nullable|date',
            'note' => 'nullable|string|max:2000',
        ]);

        $itinerary->locations()->attach($validated['location_id'], [
            'visit_time' => $validated['visit_time'] ?? null,
            'note' => $validated['note'] ?? null,
        ]);

        return redirect()->route('itineraries.show', $itinerary)->with('success', 'Đã thêm địa điểm vào lộ trình chuyến đi!');
    }

    public function removeStop(Itinerary $itinerary, int $stop): RedirectResponse
    {
        $this->authorizeItineraryOwner($itinerary);

        $deleted = DB::table('itinerary_location')
            ->where('id', $stop)
            ->where('itinerary_id', $itinerary->id)
            ->delete();

        if ($deleted === 0) {
            abort(404);
        }

        return redirect()->back()->with('success', 'Đã gỡ địa điểm khỏi lộ trình!');
    }

    public function downloadPdf(Itinerary $itinerary)
    {
        $this->authorizeItineraryOwner($itinerary);

        $scheduledLocations = $itinerary->locations()->orderBy('pivot_visit_time', 'asc')->get();

        $pdf = Pdf::loadView('itineraries.pdf', compact('itinerary', 'scheduledLocations'));
        $fileName = 'Lich-trinh-'.Str::slug($itinerary->title).'.pdf';

        return $pdf->download($fileName);
    }

    public function edit(Itinerary $itinerary): View
    {
        $this->authorizeItineraryOwner($itinerary, 'Bạn không có quyền sửa lịch trình này.');

        return view('itineraries.edit', compact('itinerary'));
    }

    public function update(Request $request, Itinerary $itinerary): RedirectResponse
    {
        $this->authorizeItineraryOwner($itinerary);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ], [
            'end_date.after_or_equal' => 'Ngày kết thúc không được nhỏ hơn ngày bắt đầu.',
        ]);

        $itinerary->update($validated);

        return redirect()->route('itineraries.show', $itinerary)->with('success', 'Đã cập nhật thông tin chuyến đi!');
    }

    public function shared(Itinerary $itinerary): View
    {
        $scheduledLocations = $itinerary->locations()->orderBy('pivot_visit_time', 'asc')->get();

        return view('itineraries.shared', compact('itinerary', 'scheduledLocations'));
    }

    private function authorizeItineraryOwner(Itinerary $itinerary, ?string $message = null): void
    {
        if ($itinerary->user_id !== Auth::id()) {
            abort(403, $message ?? 'Bạn không có quyền thao tác với lịch trình này.');
        }
    }
}
