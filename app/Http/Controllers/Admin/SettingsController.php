<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    /**
     * Tampilkan halaman pengaturan.
     */
    public function index()
    {
        $commission = Setting::getGroup('commission');
        $maintenance = Setting::getGroup('maintenance');

        return view('admin.settings.index', compact('commission', 'maintenance'));
    }

    /**
     * Update profil admin yang sedang login.
     */
    public function updateProfile(Request $request)
    {
        /** @var User $admin */
        $admin = Auth::user();

        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', Rule::unique('users')->ignore($admin->id)],
            'current_password'      => ['nullable', 'string'],
            'password'              => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Verifikasi password lama jika ingin ganti password
        if (!empty($validated['password'])) {
            if (empty($validated['current_password']) || !Hash::check($validated['current_password'], $admin->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])->withInput();
            }
            $admin->password = Hash::make($validated['password']);
        }

        $admin->name  = $validated['name'];
        $admin->email = $validated['email'];
        $admin->save();

        return back()->with('success_tab', 'profile')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update pengaturan komisi & fee platform.
     */
    public function updateCommission(Request $request)
    {
        $validated = $request->validate([
            'commission_brand'    => ['required', 'numeric', 'min:0', 'max:100'],
            'commission_kreator'  => ['required', 'numeric', 'min:0', 'max:100'],
            'min_payout'          => ['required', 'numeric', 'min:0'],
            'max_campaign_budget' => ['required', 'numeric', 'min:0'],
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value, 'commission');
        }

        return back()->with('success_tab', 'commission')->with('success', 'Pengaturan komisi berhasil disimpan!');
    }

    /**
     * Update pengaturan maintenance mode.
     */
    public function updateMaintenance(Request $request)
    {
        $validated = $request->validate([
            'maintenance_mode'    => ['required', 'in:0,1'],
            'maintenance_message' => ['required', 'string', 'max:500'],
        ]);

        Setting::set('maintenance_mode', $validated['maintenance_mode'], 'maintenance');
        Setting::set('maintenance_message', $validated['maintenance_message'], 'maintenance');

        $status = $validated['maintenance_mode'] === '1' ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success_tab', 'maintenance')->with('success', "Maintenance mode berhasil {$status}!");
    }
}
