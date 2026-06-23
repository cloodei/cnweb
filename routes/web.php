<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupInviteController;
use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProfileController;
use App\Models\Itinerary;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $totalLocations = Location::count();
        $totalGroups = Auth::user()->groups()->count();
        $totalItineraries = Itinerary::whereIn(
            'group_id',
            Auth::user()->groups()->select('groups.id'),
        )->count();

        return view('dashboard', compact('totalLocations', 'totalGroups', 'totalItineraries'));
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

    Route::resource('locations', LocationController::class);
    Route::resource('groups', GroupController::class);
    Route::get('itineraries', [ItineraryController::class, 'index'])->name('itineraries.index');

    Route::prefix('groups/{group}')->name('groups.')->group(function () {
        Route::post('invites', [GroupInviteController::class, 'store'])->name('invites.store');
        Route::delete('invites/{invite}', [GroupInviteController::class, 'destroy'])->name('invites.destroy');

        Route::get('itineraries/create', [ItineraryController::class, 'create'])->name('itineraries.create');
        Route::post('itineraries', [ItineraryController::class, 'store'])->name('itineraries.store');
        Route::get('itineraries/{itinerary}', [ItineraryController::class, 'show'])->name('itineraries.show');
        Route::get('itineraries/{itinerary}/edit', [ItineraryController::class, 'edit'])->name('itineraries.edit');
        Route::match(['put', 'patch'], 'itineraries/{itinerary}', [ItineraryController::class, 'update'])->name('itineraries.update');
        Route::delete('itineraries/{itinerary}', [ItineraryController::class, 'destroy'])->name('itineraries.destroy');
        Route::post('itineraries/{itinerary}/add-location', [ItineraryController::class, 'addLocation'])->name('itineraries.add-location');
        Route::delete('itineraries/{itinerary}/remove-stop/{stop}', [ItineraryController::class, 'removeStop'])
            ->whereNumber('stop')
            ->name('itineraries.remove-stop');
        Route::get('itineraries/{itinerary}/pdf', [ItineraryController::class, 'downloadPdf'])->name('itineraries.pdf');
    });

    Route::get('group-invites/{token}', [GroupInviteController::class, 'show'])->name('group-invites.show');
    Route::post('group-invites/{token}', [GroupInviteController::class, 'accept'])->name('group-invites.accept');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'manageUsers'])->name('admin.users');
    Route::get('/admin/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::patch('/admin/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::get('/admin/categories', [CategoryController::class, 'manage'])->name('admin.categories');
    Route::post('/admin/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::patch('/admin/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
    Route::get('/admin/itineraries', [AdminController::class, 'manageItineraries'])->name('admin.itineraries');
    Route::delete('/admin/itineraries/{itinerary}', [AdminController::class, 'destroyItinerary'])->name('admin.itineraries.destroy');
});

require __DIR__.'/auth.php';
