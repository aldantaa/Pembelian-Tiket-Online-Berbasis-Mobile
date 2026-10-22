<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    /**
     * Menampilkan daftar jadwal dan formulir tambah/edit.
     */
    public function index()
    {
        $rows = DB::table('schedules')
            ->orderByDesc('waktu_keberangkatan')
            ->get();

        $schedules = $rows->map(function ($row) {
            return [
                'id' => $row->id,
                'bus' => $row->nama_bus,
                'from' => $row->kota_asal,
                'to' => $row->kota_tujuan,
                'depart' => $row->waktu_keberangkatan,
                'arrive' => $row->waktu_tiba,
                'price' => $row->harga,
            ];
        })->all();

        $editing = null;

        if (request()->filled('edit')) {
            $row = DB::table('schedules')
                ->where('id', (int) request('edit'))
                ->first();

            abort_if(!$row, 404, 'Jadwal tidak ditemukan.');

            $editing = [
                'id' => $row->id,
                'bus' => $row->nama_bus,
                'from' => $row->kota_asal,
                'to' => $row->kota_tujuan,
                'depart' => $row->waktu_keberangkatan,
                'arrive' => $row->waktu_tiba,
                'price' => $row->harga,
            ];
        }

        return view('admin.schedules', compact('schedules', 'editing'));
    }

    /**
     * Menyimpan jadwal baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bus' => ['required', 'string', 'max:255'],
            'from' => ['required', 'string', 'max:255'],
            'to' => ['required', 'string', 'max:255', 'different:from'],
            'depart' => ['required', 'date'],
            'arrive' => ['required', 'date', 'after:depart'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        DB::table('schedules')->insert([
            'nama_bus' => $validated['bus'],
            'kota_asal' => $validated['from'],
            'kota_tujuan' => $validated['to'],
            'waktu_keberangkatan' => $validated['depart'],
            'waktu_tiba' => $validated['arrive'],
            'harga' => $validated['price'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('admin.schedules')
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    /**
     * Memperbarui jadwal.
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'bus' => ['required', 'string', 'max:255'],
            'from' => ['required', 'string', 'max:255'],
            'to' => ['required', 'string', 'max:255', 'different:from'],
            'depart' => ['required', 'date'],
            'arrive' => ['required', 'date', 'after:depart'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        $exists = DB::table('schedules')->where('id', $id)->exists();

        abort_if(!$exists, 404, 'Jadwal tidak ditemukan.');

        DB::table('schedules')
            ->where('id', $id)
            ->update([
                'nama_bus' => $validated['bus'],
                'kota_asal' => $validated['from'],
                'kota_tujuan' => $validated['to'],
                'waktu_keberangkatan' => $validated['depart'],
                'waktu_tiba' => $validated['arrive'],
                'harga' => $validated['price'],
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('admin.schedules')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    /**
     * Menghapus jadwal yang belum dipakai untuk pemesanan.
     */
    public function delete(int $id)
    {
        $exists = DB::table('schedules')->where('id', $id)->exists();

        abort_if(!$exists, 404, 'Jadwal tidak ditemukan.');

        $hasBookings = DB::table('bookings')
            ->where('schedule_id', $id)
            ->exists();

        if ($hasBookings) {
            return redirect()
                ->route('admin.schedules')
                ->with('error', 'Jadwal tidak dapat dihapus karena sudah memiliki pemesanan.');
        }

        DB::table('schedules')->where('id', $id)->delete();

        return redirect()
            ->route('admin.schedules')
            ->with('success', 'Jadwal berhasil dihapus.');
    }
}
