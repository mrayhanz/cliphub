<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if user already exists
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // User exists, login directly
                Auth::login($user);

                return $this->redirectBasedOnRole($user);
            }

            // User doesn't exist, store Google data in session and redirect to role selection
            session([
                'google_user' => [
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]
            ]);

            return redirect()->route('auth.google.role');
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Terjadi kesalahan saat login dengan Google. Silakan coba lagi.'
            ]);
        }
    }

    /**
     * Show role selection page for new Google users.
     */
    public function showRoleSelection()
    {
        if (!session()->has('google_user')) {
            return redirect()->route('login');
        }

        return view('auth.select-role');
    }

    /**
     * Handle role selection and create new user.
     */
    public function handleRoleSelection(Request $request)
    {
        if (!session()->has('google_user')) {
            return redirect()->route('login');
        }

        $request->validate([
            'role' => ['required', 'in:kreator,brand'],
        ]);

        $googleUser = session('google_user');

        // Create new user
        $user = User::create([
            'name' => $googleUser['name'],
            'email' => $googleUser['email'],
            'role' => $request->role,
            'password' => bcrypt(str()->random(32)), // Random password since they use Google login
            'google_id' => $googleUser['google_id'],
            'avatar' => $googleUser['avatar'],
        ]);

        // Clear session
        session()->forget('google_user');

        // Login user
        Auth::login($user);

        return $this->redirectBasedOnRole($user);
    }

    /**
     * Redirect user to the appropriate dashboard based on their role.
     */
    protected function redirectBasedOnRole($user)
    {
        return match ($user->role) {
            'admin'   => redirect('/admin/dashboard'),
            'brand'   => redirect('/brand/dashboard'),
            default   => redirect('/kreator/dashboard'),
        };
    }
}
