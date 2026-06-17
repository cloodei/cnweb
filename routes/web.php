<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProfileController;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/shared/itineraries/{itinerary}', [ItineraryController::class, 'shared'])->name('itineraries.shared');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $totalCategories = Category::count();
        $totalLocations = Location::count();
        $totalItineraries = Auth::user()->itineraries()->count();

        return view('dashboard', compact('totalCategories', 'totalLocations', 'totalItineraries'));
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

    Route::resource('locations', LocationController::class);
    Route::resource('itineraries', ItineraryController::class);
    Route::post('itineraries/{itinerary}/add-location', [ItineraryController::class, 'addLocation'])->name('itineraries.add-location');
    Route::delete('itineraries/{itinerary}/remove-stop/{stop}', [ItineraryController::class, 'removeStop'])->whereNumber('stop')->name('itineraries.remove-stop');
    Route::get('itineraries/{itinerary}/pdf', [ItineraryController::class, 'downloadPdf'])->name('itineraries.pdf');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'manageUsers'])->name('admin.users');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    Route::get('/admin/itineraries', [AdminController::class, 'manageItineraries'])->name('admin.itineraries');
    Route::delete('/admin/itineraries/{itinerary}', [AdminController::class, 'destroyItinerary'])->name('admin.itineraries.destroy');
});

require __DIR__.'/auth.php';
