<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Itinerary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItineraryController extends Controller
{
    public function index()
    {
        $itineraries = Auth::user()->itineraries()->latest()->get();
        return view('itineraries.index', compact('itineraries'));
    }

  
    public function create()
    {
        return view('itineraries.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date', // Ngày kết thúc phải sau hoặc bằng ngày bắt đầu
        ], [
            'end_date.after_or_equal' => 'Ngày kết thúc không được nhỏ hơn ngày bắt đầu.'
        ]);

        Auth::user()->itineraries()->create([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('itineraries.index')->with('success', 'Đã tạo lịch trình mới!');
    }
    public function destroy(Itinerary $itinerary)
    {
        if ($itinerary->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xóa lịch trình này.');
        }

        $itinerary->delete();
        return redirect()->route('itineraries.index')->with('success', 'Đã xóa lịch trình!');
    }

    public function show(Itinerary $itinerary)
    {
        
        if ($itinerary->user_id !== \Illuminate\Support\Facades\Auth::id()) {
            abort(403, 'Bạn không có quyền xem lịch trình này.');
        }

        $scheduledLocations = $itinerary->locations()->orderBy('pivot_visit_time', 'asc')->get();

        
        $allLocations = \App\Models\Location::all();

        return view('itineraries.show', compact('itinerary', 'scheduledLocations', 'allLocations'));
    }

    public function addLocation(Request $request, Itinerary $itinerary)
    {
        
        if ($itinerary->user_id !== \Illuminate\Support\Facades\Auth::id()) {
            abort(403);
        }

        $request->validate([
            'location_id' => 'required|exists:locations,id',
            'visit_time' => 'nullable|date',
        ]);
        $itinerary->locations()->attach($request->location_id, [
            'visit_time' => $request->visit_time,
            'note' => $request->note,
        ]);

        return redirect()->route('itineraries.show', $itinerary)->with('success', 'Đã thêm địa điểm vào lộ trình chuyến đi!');
    }
    public function removeLocation(Itinerary $itinerary, \App\Models\Location $location)
    {
        
        if ($itinerary->user_id !== \Illuminate\Support\Facades\Auth::id()) {
            abort(403);
        }
        $itinerary->locations()->detach($location->id);

        return redirect()->back()->with('success', 'Đã gỡ địa điểm khỏi lộ trình!');
    }
    public function downloadPdf(Itinerary $itinerary)
    {
        
        if ($itinerary->user_id !== \Illuminate\Support\Facades\Auth::id()) {
            abort(403);
        }

        $scheduledLocations = $itinerary->locations()->orderBy('pivot_visit_time', 'asc')->get();

        $pdf = Pdf::loadView('itineraries.pdf', compact('itinerary', 'scheduledLocations'));
        $fileName = 'Lich-trinh-' . \Illuminate\Support\Str::slug($itinerary->title) . '.pdf';
        return $pdf->download($fileName);
    }
    public function edit(Itinerary $itinerary)
    {
       
        if ($itinerary->user_id !== \Illuminate\Support\Facades\Auth::id()) {
            abort(403, 'Bạn không có quyền sửa lịch trình này.');
        }

        return view('itineraries.edit', compact('itinerary'));
    }


    public function update(Request $request, Itinerary $itinerary)
    {
       
        if ($itinerary->user_id !== \Illuminate\Support\Facades\Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ], [
            'end_date.after_or_equal' => 'Ngày kết thúc không được nhỏ hơn ngày bắt đầu.'
        ]);

        $itinerary->update([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('itineraries.show', $itinerary)->with('success', 'Đã cập nhật thông tin chuyến đi!');
    }

    
    public function shared(Itinerary $itinerary)
    {
        
        $scheduledLocations = $itinerary->locations()->orderBy('pivot_visit_time', 'asc')->get();
        return view('itineraries.shared', compact('itinerary', 'scheduledLocations'));
    }
}