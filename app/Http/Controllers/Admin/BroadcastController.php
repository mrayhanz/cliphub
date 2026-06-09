<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Broadcast;
use App\Models\User;
use App\Notifications\BroadcastAnnouncement;
use Illuminate\Http\Request;

class BroadcastController extends Controller
{
    /**
     * Tampilkan halaman broadcast + riwayat.
     */
    public function index()
    {
        $broadcasts = Broadcast::with('admin')
            ->latest()
            ->paginate(10);

        $stats = [
            'total'   => Broadcast::count(),
            'kreator' => User::where('role', 'kreator')->count(),
            'brand'   => User::where('role', 'brand')->count(),
        ];

        return view('admin.broadcasts.index', compact('broadcasts', 'stats'));
    }

    /**
     * Kirim broadcast ke pengguna sesuai target.
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:150'],
            'message'      => ['required', 'string', 'max:1000'],
            'type'         => ['required', 'in:info,warning,important,promo'],
            'target'       => ['required', 'in:all,kreator,brand'],
            'scheduled_at' => ['nullable', 'date', 'after:now'],
        ]);

        // Tentukan penerima
        $query = User::query()->where('role', '!=', 'admin');
        if ($validated['target'] !== 'all') {
            $query->where('role', $validated['target']);
        }
        $recipients = $query->get();

        // Simpan broadcast record
        $broadcast = Broadcast::create([
            'admin_id'        => auth()->id(),
            'title'           => $validated['title'],
            'message'         => $validated['message'],
            'type'            => $validated['type'],
            'target'          => $validated['target'],
            'recipient_count' => $recipients->count(),
            'scheduled_at'    => $validated['scheduled_at'] ?? null,
            'sent_at'         => empty($validated['scheduled_at']) ? now() : null,
        ]);

        // Kirim notifikasi langsung (jika tidak dijadwalkan)
        if (empty($validated['scheduled_at'])) {
            $broadcast->load('admin');
            foreach ($recipients as $user) {
                $user->notify(new BroadcastAnnouncement($broadcast));
            }
        }

        $msg = empty($validated['scheduled_at'])
            ? "Broadcast berhasil dikirim ke {$recipients->count()} pengguna!"
            : "Broadcast dijadwalkan pada " . \Carbon\Carbon::parse($validated['scheduled_at'])->format('d M Y, H:i') . " WIB.";

        return back()->with('success', $msg);
    }

    /**
     * Hapus broadcast dari riwayat.
     */
    public function destroy(Broadcast $broadcast)
    {
        $broadcast->delete();
        return back()->with('success', 'Broadcast berhasil dihapus dari riwayat.');
    }
}
