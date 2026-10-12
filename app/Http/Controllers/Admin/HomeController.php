<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Tahun yang dipilih di dropdown chart (default: tahun ini)
        $year = (int) $request->query('year', now()->year);

        return Inertia::render('Admin/Home', [
            // Dibungkus fn () supaya query ini dilewati saat Vue hanya meminta ulang 'chart'
            'tickets' => fn () => Ticket::with(['department', 'creator', 'technician'])
                ->latest()
                ->get(),

            'chart' => [
                'year'        => $year,
                'years'       => $this->availableYears(),
                'monthly'     => $this->ticketsPerMonth($year),
                'departments' => $this->topDepartments($year),
            ],
        ]);
    }

    /**
     * Jumlah tiket per bulan (Jan-Des). Bulan tanpa tiket bernilai 0.
     * Hasil: [3, 0, 5, 1, ...] (selalu 12 angka)
     */
    private function ticketsPerMonth(int $year): Collection
    {
        $countByMonth = Ticket::whereYear('created_at', $year)
            ->get(['created_at'])
            ->groupBy(fn ($ticket) => $ticket->created_at->month)
            ->map->count();

        return collect(range(1, 12))
            ->map(fn ($month) => $countByMonth->get($month, 0))
            ->values();
    }

    /**
     * 5 departemen yang paling banyak mengajukan tiket pada tahun tersebut.
     */
    private function topDepartments(int $year): Collection
    {
        return Ticket::with('department:id,department_name')
            ->whereYear('created_at', $year)
            ->selectRaw('department_id, COUNT(*) as total')
            ->groupBy('department_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(fn ($row) => [
                'name'  => $row->department?->department_name ?? 'Tanpa departemen',
                'total' => (int) $row->total,
            ]);
    }

    /**
     * Daftar tahun untuk dropdown (selalu memuat tahun berjalan), terbaru di atas.
     */
    private function availableYears(): Collection
    {
        return Ticket::pluck('created_at')
            ->map(fn ($date) => $date->year)
            ->push(now()->year)
            ->unique()
            ->sortDesc()
            ->values();
    }
}
