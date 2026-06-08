<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            return back()->with('status', 'Jika email terdaftar, link reset password akan dikirim.');
        }

        if ($user->google_id) {
            return back()
                ->withInput($request->only('email'))
                ->with('status', 'Akun ini terhubung dengan Google. Silakan masuk menggunakan tombol Google.');
        }

        try {
            $status = Password::sendResetLink($validated);
        } catch (\Throwable $e) {
            report($e);
            $message = $e->getMessage();

            if (str_contains($message, 'domain is not verified')) {
                return back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => 'Email reset belum terkirim karena domain pengirim belum diverifikasi di Resend. Gunakan onboarding@resend.dev untuk testing, atau verifikasi domain Anda di Resend.']);
            }

            if (str_contains($message, 'only send testing emails to your own email address')) {
                return back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => 'Email reset belum terkirim karena Resend masih mode testing. Dengan onboarding@resend.dev, email hanya bisa dikirim ke email pemilik akun Resend. Untuk kirim ke user lain, verifikasi domain di Resend lalu gunakan email pengirim dari domain itu.']);
            }

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email reset belum terkirim. Pastikan RESEND_API_KEY dan MAIL_FROM_ADDRESS sudah benar.']);
        }

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Link reset password sudah dikirim jika email tersebut dapat digunakan.')
            : back()->withInput($request->only('email'))->withErrors(['email' => $this->passwordStatusMessage($status)]);
    }

    public function showResetPasswordForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $status = Password::reset(
            $validated,
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Password berhasil diubah. Silakan masuk dengan password baru.')
            : back()->withInput($request->only('email'))->withErrors(['email' => $this->passwordStatusMessage($status)]);
    }

    private function passwordStatusMessage(string $status): string
    {
        return match ($status) {
            Password::INVALID_USER => 'Email tidak ditemukan atau belum bisa digunakan untuk reset password.',
            Password::INVALID_TOKEN => 'Link reset password tidak valid atau sudah kedaluwarsa.',
            Password::RESET_THROTTLED => 'Terlalu banyak percobaan. Tunggu sebentar sebelum meminta link baru.',
            default => 'Reset password belum berhasil. Silakan coba lagi.',
        };
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return $this->redirectBasedOnRole(Auth::user());
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan tidak cocok.',
        ])->onlyInput('email');
    }

    /**
     * Handle a new user registration request.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'role'     => ['required', 'in:kreator,brand'],
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create($validated);

        Auth::login($user);
        $request->session()->regenerate();

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

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
