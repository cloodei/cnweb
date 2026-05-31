<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ItineraryController;
Route::get('/', function () { return view('welcome'); });
Route::get('/shared/itineraries/{itinerary}', [ItineraryController::class, 'shared'])->name('itineraries.shared');
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $totalCategories = \App\Models\Category::count();
        $totalLocations = \App\Models\Location::count();
        $totalItineraries = \Illuminate\Support\Facades\Auth::user()->itineraries()->count();
        return view('dashboard', compact('totalCategories', 'totalLocations', 'totalItineraries'));
    })->middleware('verified')->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('categories', \App\Http\Controllers\CategoryController::class)->only(['index', 'show']);
    Route::resource('locations', \App\Http\Controllers\LocationController::class);
    Route::resource('itineraries', ItineraryController::class);
    Route::post('itineraries/{itinerary}/add-location', [ItineraryController::class, 'addLocation'])->name('itineraries.add-location');
    Route::delete('itineraries/{itinerary}/remove-location/{location}', [ItineraryController::class, 'removeLocation'])->name('itineraries.remove-location');
    Route::get('itineraries/{itinerary}/pdf', [ItineraryController::class, 'downloadPdf'])->name('itineraries.pdf');
});
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('categories', CategoryController::class)->except(['index', 'show']);
    Route::get('/admin/users', [\App\Http\Controllers\AdminController::class, 'manageUsers'])->name('admin.users');
    Route::delete('/admin/users/{user}', [\App\Http\Controllers\AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    Route::get('/admin/itineraries', [\App\Http\Controllers\AdminController::class, 'manageItineraries'])->name('admin.itineraries');
    Route::delete('/admin/itineraries/{itinerary}', [\App\Http\Controllers\AdminController::class, 'destroyItinerary'])->name('admin.itineraries.destroy');
});

require __DIR__.'/auth.php';