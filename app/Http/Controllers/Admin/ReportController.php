<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
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

        $now = now();

        // Ringkasan dashboard
        $totalUsers = DB::table('users')->count();
        $totalBookings = DB::table('bookings')->count();

        $totalRevenue = DB::table('payments')
            ->where('status', 'success')
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

        // Grafik pendapatan: 7 hari atau 6 bulan
        $chart = [];

        if ($period === 'bulanan') {
            $start = $now->copy()->startOfMonth()->subMonths(5);

            for ($i = 0; $i < 6; $i++) {
                $date = $start->copy()->addMonths($i);
                $chart[$date->format('M Y')] = 0;
            }

            $payments = DB::table('payments')
                ->where('status', 'success')
                ->whereNotNull('paid_at')
                ->where('paid_at', '>=', $start)
                ->where('paid_at', '<=', $now)
                ->get(['paid_at', 'amount']);

            foreach ($payments as $payment) {
                $label = Carbon::parse($payment->paid_at)->format('M Y');

                if (array_key_exists($label, $chart)) {
                    $chart[$label] += (float) $payment->amount;
                }
            }
        } else {
            $start = $now->copy()->startOfDay()->subDays(6);

            for ($i = 0; $i < 7; $i++) {
                $date = $start->copy()->addDays($i);
                $chart[$date->format('d M')] = 0;
            }

            $payments = DB::table('payments')
                ->where('status', 'success')
                ->whereNotNull('paid_at')
                ->where('paid_at', '>=', $start)
                ->where('paid_at', '<=', $now)
                ->get(['paid_at', 'amount']);

            foreach ($payments as $payment) {
                $label = Carbon::parse($payment->paid_at)->format('d M');

                if (array_key_exists($label, $chart)) {
                    $chart[$label] += (float) $payment->amount;
                }
            }
        }

        // Kelompokkan booking berdasarkan kode pesanan.
        // Satu kode booking bisa memiliki beberapa kursi.
        $bookingGroups = DB::table('bookings')
            ->select(
                'booking_code',
                'schedule_id',
                DB::raw('COUNT(*) as passengers')
            )
            ->groupBy('booking_code', 'schedule_id');

        // Setiap pembayaran hanya dihitung satu kali.
        $routes = DB::table('payments')
            ->joinSub($bookingGroups, 'booking_groups', function ($join) {
                $join->on(
                    'payments.booking_code',
                    '=',
                    'booking_groups.booking_code'
                );
            })
            ->join(
                'schedules',
                'booking_groups.schedule_id',
                '=',
                'schedules.id'
            )
            ->where('payments.status', 'success')
            ->select(
                'schedules.kota_asal',
                'schedules.kota_tujuan',
                DB::raw('SUM(booking_groups.passengers) as passengers'),
                DB::raw('SUM(payments.amount) as revenue')
            )
            ->groupBy(
                'schedules.kota_asal',
                'schedules.kota_tujuan'
            )
            ->get()
            ->map(function ($item) {
                return [
                    'route' => $item->kota_asal . ' - ' . $item->kota_tujuan,
                    'passengers' => (int) $item->passengers,
                    'revenue' => (float) $item->revenue,
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

