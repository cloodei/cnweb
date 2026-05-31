<?php
namespace App\Http\Controllers;
use App\Models\Location;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class LocationController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = Location::with('category')->latest();

       
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $locations = $query->paginate(5)->withQueryString();
        
        
        $categories = Category::all();

        return view('locations.index', compact('locations', 'categories'));
    }

   
    public function create()
    {
       
        $categories = Category::all();
        return view('locations.create', compact('categories'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        $imagePath = null;
        
        if ($request->hasFile('image')) {
           
            $imagePath = $request->file('image')->store('locations', 'public');
        }

        Location::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'address' => $request->address,
            'image' => $imagePath,
        ]);

        return redirect()->route('locations.index')->with('success', 'Đã thêm địa điểm thành công!');
    }
    public function edit(Location $location)
    {
        
        if (\Illuminate\Support\Facades\Auth::user()->role !== 'admin' && $location->user_id !== \Illuminate\Support\Facades\Auth::id()) {
            abort(403, 'Bạn không có quyền thay đổi địa điểm của người khác.');
        }
       
        $categories = Category::all();
        return view('locations.edit', compact('location', 'categories'));
    }

    public function update(Request $request, Location $location)
    {
        
        if (\Illuminate\Support\Facades\Auth::user()->role !== 'admin' && $location->user_id !== \Illuminate\Support\Facades\Auth::id()) {
            abort(403, 'Bạn không có quyền thay đổi địa điểm của người khác.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        $imagePath = $location->image; 

        if ($request->hasFile('image')) {
            if ($location->image) {
                Storage::disk('public')->delete($location->image);
            }
          
            $imagePath = $request->file('image')->store('locations', 'public');
        }

  
        $location->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'address' => $request->address,
            'image' => $imagePath, 
        ]);

        return redirect()->route('locations.index')->with('success', 'Đã cập nhật địa điểm thành công!');
    }
   
    public function destroy(Location $location)
    {
     
        if (\Illuminate\Support\Facades\Auth::user()->role !== 'admin' && $location->user_id !== \Illuminate\Support\Facades\Auth::id()) {
            abort(403, 'Bạn không có quyền thay đổi địa điểm của người khác.');
        }
        if ($location->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($location->image);
        }
        
        $location->delete();
        
        return redirect()->route('locations.index')->with('success', 'Đã xóa địa điểm thành công!');
    }


    public function show(Location $location)
    {
        return view('locations.show', compact('location'));
    }
}