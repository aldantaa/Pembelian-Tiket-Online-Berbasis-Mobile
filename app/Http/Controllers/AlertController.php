<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    /**
     * Menampilkan notifikasi milik pengguna yang sedang login.
     */
    public function index(Request $request)
    {
        $alerts = Alert::where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        $unreadCount = Alert::where('user_id', $request->user()->id)
            ->whereNull('read_at')
            ->count();

        return response()->json([
            'message' => 'Berhasil mengambil notifikasi.',
            'unread_count' => $unreadCount,
            'data' => $alerts,
        ]);
    }

    /**
     * Menandai satu notifikasi sebagai sudah dibaca.
     */
    public function markAsRead(Request $request, int $id)
    {
        $alert = Alert::where('user_id', $request->user()->id)
            ->findOrFail($id);

        if ($alert->read_at === null) {
            $alert->update([
                'read_at' => now(),
            ]);
        }

        return response()->json([
            'message' => 'Notifikasi berhasil ditandai sebagai dibaca.',
            'data' => $alert->fresh(),
        ]);
    }

    /**
     * Menandai seluruh notifikasi pengguna sebagai sudah dibaca.
     */
    public function markAllAsRead(Request $request)
    {
        $updated = Alert::where('user_id', $request->user()->id)
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
            ]);

        return response()->json([
            'message' => 'Seluruh notifikasi berhasil ditandai sebagai dibaca.',
            'updated_count' => $updated,
        ]);
    }
}
