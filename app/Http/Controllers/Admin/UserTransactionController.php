<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserTransactionController extends Controller
{
    /**
     * KF-08: Daftar pengguna.
     */
    public function users()
    {
        $users = DB::table('users')
            ->orderByDesc('created_at')
            ->paginate(10);

        $editing = null;

        if (request()->filled('edit')) {
            $editing = DB::table('users')->find(
                (int) request('edit')
            );

            abort_if(!$editing, 404, 'Pengguna tidak ditemukan.');
        }

        return view('admin.users', compact('users', 'editing'));
    }

    /**
     * KF-08: Form edit pengguna.
     */
    public function editUser(int $id)
    {
        $user = DB::table('users')->find($id);

        abort_if(!$user, 404, 'Pengguna tidak ditemukan.');

        $users = DB::table('users')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.users', [
            'users' => $users,
            'editing' => $user,
        ]);
    }

    /**
     * KF-08: Simpan perubahan pengguna.
     */
    public function updateUser(Request $request, int $id)
    {
        $user = DB::table('users')->find($id);

        abort_if(!$user, 404, 'Pengguna tidak ditemukan.');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($id),
            ],
            'role' => ['required', Rule::in(['user', 'admin'])],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        // Admin tidak boleh menurunkan hak akses dirinya sendiri.
        if (
            auth()->id() === $id &&
            $validated['role'] !== 'admin'
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'role' => 'Kamu tidak dapat menghapus hak admin sendiri.',
                ]);
        }

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'updated_at' => now(),
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        DB::table('users')->where('id', $id)->update($data);

        return redirect()
            ->route('admin.users')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * KF-08: Hapus pengguna.
     */
    public function deleteUser(int $id)
    {
        $user = DB::table('users')->find($id);

        abort_if(!$user, 404, 'Pengguna tidak ditemukan.');

        if (auth()->id() === $id) {
            return back()->with(
                'error',
                'Kamu tidak dapat menghapus akun sendiri.'
            );
        }

        if ($user->role === 'admin') {
            return back()->with(
                'error',
                'Akun admin tidak dapat dihapus melalui fitur ini.'
            );
        }

        $hasBookings = DB::table('bookings')
            ->where('user_id', $id)
            ->exists();

        if ($hasBookings) {
            return back()->with(
                'error',
                'Pengguna memiliki riwayat pemesanan. Akun tidak dihapus agar riwayat transaksi tetap terjaga.'
            );
        }

        DB::table('users')->where('id', $id)->delete();

        return redirect()
            ->route('admin.users')
            ->with('success', 'Pengguna berhasil dihapus.');
    }

    /**
     * KF-08: Daftar transaksi.
     */
    public function transactions()
    {
        $transactions = DB::table('payments')
            ->leftJoin(
                'bookings',
                'payments.booking_code',
                '=',
                'bookings.booking_code'
            )
            ->leftJoin('users', 'payments.user_id', '=', 'users.id')
            ->select(
                'payments.id',
                'payments.booking_code',
                'payments.user_id',
                'users.name as user_name',
                'payments.method',
                'payments.amount',
                'payments.status as payment_status',
                'payments.reference',
                'payments.paid_at',
                'payments.created_at',
                'bookings.status as booking_status'
            )
            ->orderByDesc('payments.created_at')
            ->paginate(10);

        return view('admin.transactions', compact('transactions'));
    }

    /**
     * KF-08: Perbarui status transaksi.
     */
    public function updateTransaction(Request $request, int $id)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in(['pending', 'paid', 'failed', 'expired']),
            ],
        ]);

        $payment = DB::table('payments')->find($id);

        abort_if(!$payment, 404, 'Transaksi tidak ditemukan.');

        // Pembayaran yang sudah berhasil tidak boleh diubah
        // secara manual menjadi status lain melalui fitur ini.
        if (
            $payment->status === 'paid' &&
            $validated['status'] !== 'paid'
        ) {
            return back()->with(
                'error',
                'Transaksi yang sudah dibayar tidak dapat dibatalkan melalui halaman ini.'
            );
        }

        DB::transaction(function () use ($payment, $validated) {
            $status = $validated['status'];

            DB::table('payments')
                ->where('id', $payment->id)
                ->update([
                    'status' => $status,
                    'paid_at' => $status === 'paid'
                        ? ($payment->paid_at ?? now())
                        : null,
                    'updated_at' => now(),
                ]);

            // Jangan mengubah status booking menjadi paid
            // tanpa proses pembayaran yang sah.
            if ($status !== 'paid') {
                DB::table('bookings')
                    ->where('booking_code', $payment->booking_code)
                    ->where('status', '!=', 'paid')
                    ->update([
                        'status' => $status,
                        'updated_at' => now(),
                    ]);
            }
        });

        return redirect()
            ->route('admin.transactions')
            ->with('success', 'Status transaksi berhasil diperbarui.');
    }

    public function export()
    {
        $payments = \Illuminate\Support\Facades\DB::table('payments')
            ->join('bookings', 'payments.booking_code', '=', 'bookings.booking_code')
            ->join('schedules', 'bookings.schedule_id', '=', 'schedules.id')
            ->where('payments.status', 'paid')
            ->select(
                'payments.booking_code',
                'payments.amount',
                'payments.paid_at',
                'schedules.kota_asal',
                'schedules.kota_tujuan'
            )
            ->orderByDesc('payments.paid_at')
            ->get();

        $filename = 'laporan-pendapatan-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($payments) {
            $output = fopen('php://output', 'w');

            // BOM agar karakter UTF-8 terbaca dengan baik di Excel.
            fwrite($output, "\xEF\xBB\xBF");

            fputcsv($output, [
                'Kode Booking',
                'Asal',
                'Tujuan',
                'Jumlah Pembayaran',
                'Tanggal Pembayaran',
            ], ';');

            foreach ($payments as $payment) {
                fputcsv($output, [
                    $payment->booking_code,
                    $payment->kota_asal,
                    $payment->kota_tujuan,
                    $payment->amount,
                    $payment->paid_at,
                ], ';');
            }

            fclose($output);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
