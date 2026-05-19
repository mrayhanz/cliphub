<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $profile = $user->brandProfile;

        $otherBrands = \App\Models\User::where('role', 'brand')
            ->where('id', '!=', $user->id)
            ->with('brandProfile')
            ->take(4)
            ->get();

        return view('brand.profile.index', compact('profile', 'otherBrands'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:1000'],
            'website' => ['nullable', 'url', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $profile = $user->brandProfile;

        if ($request->hasFile('logo')) {
            if ($profile?->logo_path) {
                Storage::disk('public')->delete($profile->logo_path);
            }

            $validated['logo_path'] = $request->file('logo')->store('brand/logos', 'public');
        }

        $user->brandProfile()->updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return redirect()->route('brand.profile')->with('success', 'Profil brand berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.current_password' => 'Kata sandi saat ini tidak cocok.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            'password.min' => 'Kata sandi baru minimal 8 karakter.',
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('brand.profile')->with('success', 'Kata sandi berhasil diperbarui.');
    }
}
