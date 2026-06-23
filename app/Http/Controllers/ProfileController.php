<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     * Admin accounts are redirected to the admin dashboard —
     * they manage users through the admin console, not this form.
     */
    public function edit(Request $request): View|RedirectResponse
    {
        if ($request->user()->isAdmin()) {
            return Redirect::route('admin.dashboard');
        }

        $user           = $request->user();
        $itineraryCount = $user->itineraries()->count();
        $locationCount  = $user->locations()->count();

        return view('profile.edit', compact('user', 'itineraryCount', 'locationCount'));
    }

    /**
     * Update the user's profile information.
     * Clears email_verified_at when the email address changes so the new
     * address must be re-verified (if verification is enabled in future).
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        if ($request->user()->isAdmin()) {
            return Redirect::route('admin.dashboard');
        }

        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     * Requires the current password for confirmation.
     * Cascades to owned itineraries (see schema delete behavior in CODEBASE_GUIDE.md).
     */
    public function destroy(Request $request): RedirectResponse
    {
        if ($request->user()->isAdmin()) {
            abort(403, 'Tài khoản admin không dùng luồng hồ sơ cá nhân.');
        }

        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ], [
            'password.required'        => 'Vui lòng nhập mật khẩu để xác nhận.',
            'password.current_password' => 'Mật khẩu không đúng.',
        ]);

        $user = $request->user();

        Log::info('User account deleted.', [
            'user_id' => $user->id,
            'email'   => $user->email,
        ]);

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
