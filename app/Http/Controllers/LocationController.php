<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class LocationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Location::with('category')->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $locations = $query->paginate(5)->withQueryString();
        $categories = Category::all();

        return view('locations.index', compact('locations', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::all();

        return view('locations.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('locations', 'public');
        }

        try {
            Location::create([
                ...$validated,
                'user_id' => Auth::id(),
                'image' => $imagePath,
            ]);
        } catch (Throwable $exception) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            throw $exception;
        }

        return redirect()->route('locations.index')->with('success', 'Đã thêm địa điểm thành công!');
    }

    public function edit(Location $location): View
    {
        $this->authorizeLocationMutation($location);
        $categories = Category::all();

        return view('locations.edit', compact('location', 'categories'));
    }

    public function update(Request $request, Location $location): RedirectResponse
    {
        $this->authorizeLocationMutation($location);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        $oldImagePath = $location->image;
        $newImagePath = null;

        if ($request->hasFile('image')) {
            $newImagePath = $request->file('image')->store('locations', 'public');
        }

        try {
            $location->update([
                ...$validated,
                'address' => $request->exists('address') ? ($validated['address'] ?? null) : $location->address,
                'image' => $newImagePath ?? $oldImagePath,
            ]);
        } catch (Throwable $exception) {
            if ($newImagePath) {
                Storage::disk('public')->delete($newImagePath);
            }

            throw $exception;
        }

        if ($newImagePath && $oldImagePath) {
            Storage::disk('public')->delete($oldImagePath);
        }

        return redirect()->route('locations.index')->with('success', 'Đã cập nhật địa điểm thành công!');
    }

    public function destroy(Location $location): RedirectResponse
    {
        $this->authorizeLocationMutation($location);

        $oldImagePath = $location->image;
        $location->delete();

        if ($oldImagePath) {
            Storage::disk('public')->delete($oldImagePath);
        }

        return redirect()->route('locations.index')->with('success', 'Đã xóa địa điểm thành công!');
    }

    public function show(Location $location): View
    {
        return view('locations.show', compact('location'));
    }

    private function authorizeLocationMutation(Location $location): void
    {
        if (!Auth::user()?->isAdmin() && $location->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền thay đổi địa điểm của người khác.');
        }
    }
}
