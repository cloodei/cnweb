<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Itinerary;
use Illuminate\Http\Request;
class AdminController extends Controller
{
    public function manageUsers()
    {
        $users = User::latest()->get();
        return view('admin.users', compact('users'));
    }
    public function manageItineraries()
    {
        $itineraries = Itinerary::with('user')->latest()->get();
        return view('admin.itineraries', compact('itineraries'));
    }
    public function destroyUser(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Không thể xóa tài khoản Quản trị viên cao cấp!');
        }
        $user->delete();
        return back()->with('success', 'Đã xóa tài khoản người dùng!');
    }
    public function destroyItinerary(Itinerary $itinerary)
    {
        $itinerary->delete();
        return back()->with('success', 'Đã gỡ bỏ lịch trình vi phạm khỏi hệ thống!');
    }
}