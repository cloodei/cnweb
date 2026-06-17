<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Itinerary;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $totalUsers = User::count();
        $adminUsers = User::where('role', 'admin')->count();
        $totalItineraries = Itinerary::count();
        $totalLocations = Location::count();
        $totalCategories = Category::count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'adminUsers',
            'totalItineraries',
            'totalLocations',
            'totalCategories',
        ));
    }

    public function manageUsers(): View
    {
        $users = User::orderBy('created_at', 'desc')->get();

        return view('admin.users', compact('users'));
    }

    public function manageItineraries(): View
    {
        $itineraries = Itinerary::with('user')->latest()->get();

        return view('admin.itineraries', compact('itineraries'));
    }

    public function destroyUser(User $user): RedirectResponse
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Không thể xóa tài khoản Quản trị viên cao cấp!');
        }

        $user->delete($user->id);
        return back()->with('success', 'Đã xóa tài khoản người dùng!');
    }

    public function destroyItinerary(Itinerary $itinerary): RedirectResponse
    {
        $itinerary->delete($itinerary->id);
        return back()->with('success', 'Đã gỡ bỏ lịch trình vi phạm khỏi hệ thống!');
    }
}
