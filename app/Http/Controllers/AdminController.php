<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminUpdateUserRequest;
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

    public function editUser(User $user): View
    {
        return view('admin.users-edit', compact('user'));
    }

    public function updateUser(AdminUpdateUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        if ($user->isAdmin() && $validated['role'] !== 'admin' && User::where('role', 'admin')->count() === 1) {
            return back()
                ->withInput()
                ->with('error', 'Hệ thống phải luôn có ít nhất một tài khoản quản trị.');
        }

        $emailChanged = $user->email !== $validated['email'];

        $user->forceFill([
            ...$validated,
            'email_verified_at' => $emailChanged ? null : $user->email_verified_at,
        ])->save();

        return redirect()
            ->route('admin.users')
            ->with('success', 'Đã cập nhật thông tin người dùng.');
    }

    public function manageItineraries(): View
    {
        $itineraries = Itinerary::with('user')->latest()->get();

        return view('admin.itineraries', compact('itineraries'));
    }

    public function destroyItinerary(Itinerary $itinerary): RedirectResponse
    {
        $itinerary->delete($itinerary->id);

        return back()->with('success', 'Đã gỡ bỏ lịch trình vi phạm khỏi hệ thống!');
    }
}
