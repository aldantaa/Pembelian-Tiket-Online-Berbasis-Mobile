<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function dashboard(Request $request)
    {
        $period = $request->query('period', 'harian');

        if (!in_array($period, ['harian', 'bulanan'], true)) {
            $period = 'harian';
        }

        $today = now()->toDateString();
        $month = now()->format('Y-m');

        $totalUsers = DB::table('users')->count();
        $totalBookings = DB::table('bookings')->count();

        $totalRevenue = DB::table('payments')
            ->where('status', 'paid')
            ->sum('amount');

        $stats = [
            'Total pengguna' => $totalUsers,
            'Total pemesanan' => $totalBookings,
            'Total pendapatan' => 'Rp ' . number_format(
                (float) $totalRevenue,
                0,
                ',',
                '.'
            ),
        ];

        // Siapkan grafik dengan label yang tetap tersedia
        // meskipun belum ada transaksi pembayaran.
        if ($period === 'bulanan') {
            $chart = [];

            for ($i = 5; $i >= 0; $i--) {
                $date = now()->startOfMonth()->subMonths($i);
                $chart[$date->format('M Y')] = 0;
            }

            $payments = DB::table('payments')
                ->where('status', 'paid')
                ->whereNotNull('paid_at')
                ->where('paid_at', '>=', now()->startOfMonth()->subMonths(5))
                ->selectRaw("DATE_FORMAT(paid_at, '%Y-%m') as periode, SUM(amount) as total")
                ->groupBy('periode')
                ->get();

            foreach ($payments as $payment) {
                $date = \Carbon\Carbon::createFromFormat(
                    'Y-m',
                    $payment->periode
                );

                $chart[$date->format('M Y')] = (float) $payment->total;
            }
        } else {
            $chart = [];

            for ($i = 6; $i >= 0; $i--) {
                $date = now()->startOfDay()->subDays($i);
                $chart[$date->format('d M')] = 0;
            }

            $payments = DB::table('payments')
                ->where('status', 'paid')
                ->whereNotNull('paid_at')
                ->whereDate('paid_at', '>=', now()->startOfDay()->subDays(6))
                ->selectRaw('DATE(paid_at) as periode, SUM(amount) as total')
                ->groupBy('periode')
                ->get();

            foreach ($payments as $payment) {
                $date = \Carbon\Carbon::parse($payment->periode);
                $chart[$date->format('d M')] = (float) $payment->total;
            }
        }

        $routes = DB::table('payments')
            ->join(
                'bookings',
                'payments.booking_code',
                '=',
                'bookings.booking_code'
            )
            ->join(
                'schedules',
                'bookings.schedule_id',
                '=',
                'schedules.id'
            )
            ->where('payments.status', 'paid')
            ->select(
                'schedules.kota_asal',
                'schedules.kota_tujuan',
                DB::raw('COUNT(DISTINCT bookings.booking_code) as passengers'),
                DB::raw('SUM(payments.amount) as revenue')
            )
            ->groupBy(
                'schedules.kota_asal',
                'schedules.kota_tujuan'
            )
            ->get()
            ->map(function ($row) {
                return [
                    'route' => $row->kota_asal . ' - ' . $row->kota_tujuan,
                    'passengers' => (int) $row->passengers,
                    'revenue' => (float) $row->revenue,
                ];
            });

        return view('admin.dashboard', compact(
            'period',
            'stats',
            'chart',
            'routes'
        ));
    }

    public function export()
    {
        $payments = DB::table('payments')
            ->join(
                'bookings',
                'payments.booking_code',
                '=',
                'bookings.booking_code'
            )
            ->join(
                'schedules',
                'bookings.schedule_id',
                '=',
                'schedules.id'
            )
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

