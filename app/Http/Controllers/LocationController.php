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
        $query = Location::with('user')->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($locationQuery) use ($search) {
                $locationQuery->where('name', 'like', '%'.$search.'%')
                    ->orWhere('address', 'like', '%'.$search.'%');
            });
        }

        $locations = $query->paginate(6)->withQueryString();

        return view('locations.index', compact('locations'));
    }

    public function create(): View
    {
        return view('locations.create', [
            'googleMapsKey' => config('services.google_maps.browser_key'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'place_provider' => 'nullable|string|max:50',
            'place_id' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
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
                'category_id' => $this->defaultCategoryId(),
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

        return view('locations.edit', [
            'location' => $location,
            'googleMapsKey' => config('services.google_maps.browser_key'),
        ]);
    }

    public function update(Request $request, Location $location): RedirectResponse
    {
        $this->authorizeLocationMutation($location);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'place_provider' => 'nullable|string|max:50',
            'place_id' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'remove_image' => 'nullable|boolean',
        ]);

        $oldImagePath = $location->image;
        $newImagePath = null;
        $removeImage = $request->boolean('remove_image');
        unset($validated['remove_image']);

        if ($request->hasFile('image')) {
            $newImagePath = $request->file('image')->store('locations', 'public');
        }

        try {
            $location->update([
                ...$validated,
                'address' => $request->exists('address') ? ($validated['address'] ?? null) : $location->address,
                'image' => $newImagePath ?? ($removeImage ? null : $oldImagePath),
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

        if ($removeImage && ! $newImagePath && $oldImagePath) {
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
        if (! Auth::user()?->isAdmin() && $location->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền thay đổi địa điểm của người khác.');
        }
    }

    private function defaultCategoryId(): int
    {
        return Category::firstOrCreate(['name' => 'Chưa phân loại'])->id;
    }
}
