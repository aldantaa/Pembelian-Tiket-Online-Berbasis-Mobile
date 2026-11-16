<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


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
     * Aturan validasi jadwal.
     */
    private function scheduleRules(): array
    {
        return [
            'bus' => [
                'required',
                'string',
                'max:100',
                Rule::exists('buses', 'nama_bus')
                    ->where(fn ($query) => $query->where('status', 'aktif')),
            ],
            'from' => ['required', 'string', 'max:255'],
            'to' => ['required', 'string', 'max:255', 'different:from'],
            'depart' => ['required', 'date'],
            'arrive' => ['required', 'date', 'after:depart'],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * Membuat daftar 40 kursi.
     */
    private function generateSeats(int $scheduleId): void
    {
        $existingSeats = DB::table('seats')
            ->where('schedule_id', $scheduleId)
            ->pluck('seat_number')
            ->all();

        $seats = [];
        $existingSeats = array_flip($existingSeats);

        foreach (range('A', 'J') as $row) {
            foreach (range(1, 4) as $number) {
                $seatNumber = $row . $number;

                if (isset($existingSeats[$seatNumber])) {
                    continue;
                }

                $seats[] = [
                    'schedule_id' => $scheduleId,
                    'seat_number' => $seatNumber,
                    'is_booked' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (!empty($seats)) {
            DB::table('seats')->insert($seats);
        }
    }

    /**
     * Menyimpan jadwal baru beserta 40 kursinya.
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->scheduleRules());

        $bus = Bus::where('nama_bus', $validated['bus'])
            ->where('status', 'aktif')
            ->firstOrFail();

        DB::transaction(function () use ($validated, $bus) {
            $scheduleId = DB::table('schedules')->insertGetId([
                'bus_id' => $bus->id,
                'nama_bus' => $bus->nama_bus,
                'kota_asal' => $validated['from'],
                'kota_tujuan' => $validated['to'],
                'waktu_keberangkatan' => $validated['depart'],
                'waktu_tiba' => $validated['arrive'],
                'harga' => $validated['price'],
                'kapasitas' => 40,
                'kursi_tersedia' => 40,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->generateSeats($scheduleId);
        });

        return redirect()
            ->route('admin.schedules')
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    /**
     * Memperbarui jadwal tanpa menghapus kursi yang sudah dipesan.
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate($this->scheduleRules());

        $bus = Bus::where('nama_bus', $validated['bus'])
            ->where('status', 'aktif')
            ->firstOrFail();

        DB::transaction(function () use ($validated, $bus, $id) {
            $oldSchedule = DB::table('schedules')
                ->where('id', $id)
                ->lockForUpdate()
                ->first();

            abort_if(!$oldSchedule, 404, 'Jadwal tidak ditemukan.');

            // Bandingkan data jadwal lama dan baru.
            $changes = [];

            $fields = [
                'bus_id' => [
                    'label' => 'Bus',
                    'old' => $oldSchedule->bus_id,
                    'new' => $bus->id,
                ],
                'nama_bus' => [
                    'label' => 'Nama bus',
                    'old' => $oldSchedule->nama_bus,
                    'new' => $bus->nama_bus,
                ],
                'kota_asal' => [
                    'label' => 'Kota asal',
                    'old' => $oldSchedule->kota_asal,
                    'new' => $validated['from'],
                ],
                'kota_tujuan' => [
                    'label' => 'Kota tujuan',
                    'old' => $oldSchedule->kota_tujuan,
                    'new' => $validated['to'],
                ],
                'waktu_keberangkatan' => [
                    'label' => 'Waktu keberangkatan',
                    'old' => $oldSchedule->waktu_keberangkatan,
                    'new' => $validated['depart'],
                ],
                'waktu_tiba' => [
                    'label' => 'Waktu tiba',
                    'old' => $oldSchedule->waktu_tiba,
                    'new' => $validated['arrive'],
                ],
                'harga' => [
                    'label' => 'Harga tiket',
                    'old' => (float) $oldSchedule->harga,
                    'new' => (float) $validated['price'],
                ],
            ];

            foreach ($fields as $field) {
                if ((string) $field['old'] !== (string) $field['new']) {
                    $changes[] = $field['label']
                        . ': ' . $field['old']
                        . ' menjadi ' . $field['new'];
                }
            }

            // Pertahankan kursi yang sudah ada.
            $this->generateSeats($id);

            $availableSeats = DB::table('seats')
                ->where('schedule_id', $id)
                ->where('is_booked', false)
                ->count();

            // Simpan perubahan jadwal.
            DB::table('schedules')
                ->where('id', $id)
                ->update([
                    'bus_id' => $bus->id,
                    'nama_bus' => $bus->nama_bus,
                    'kota_asal' => $validated['from'],
                    'kota_tujuan' => $validated['to'],
                    'waktu_keberangkatan' => $validated['depart'],
                    'waktu_tiba' => $validated['arrive'],
                    'harga' => $validated['price'],
                    'kapasitas' => 40,
                    'kursi_tersedia' => $availableSeats,
                    'updated_at' => now(),
                ]);

            // Kirim notifikasi jika memang ada perubahan.
            if (!empty($changes)) {
                $userIds = DB::table('bookings')
                    ->where('schedule_id', $id)
                    ->whereIn('status', ['pending', 'paid'])
                    ->distinct()
                    ->pluck('user_id');

                foreach ($userIds as $userId) {
                    Alert::create([
                        'user_id' => $userId,
                        'schedule_id' => $id,
                        'type' => 'schedule_change',
                        'title' => 'Perubahan jadwal perjalanan',
                        'message' => 'Jadwal perjalanan Anda mengalami perubahan. '
                            . implode('; ', $changes),
                    ]);
                }
            }
        });

        return redirect()
            ->route('admin.schedules')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    /**
     * Menghapus jadwal yang belum memiliki pemesanan.
     */
    public function delete(int $id)
    {
        $exists = DB::table('schedules')
            ->where('id', $id)
            ->exists();

        abort_if(!$exists, 404, 'Jadwal tidak ditemukan.');

        $hasBookings = DB::table('bookings')
            ->where('schedule_id', $id)
            ->exists();

        if ($hasBookings) {
            return redirect()
                ->route('admin.schedules')
                ->with('error', 'Jadwal tidak dapat dihapus karena sudah memiliki pemesanan.');
        }

        DB::table('schedules')
            ->where('id', $id)
            ->delete();

        return redirect()
            ->route('admin.schedules')
            ->with('success', 'Jadwal berhasil dihapus.');
    }
}
