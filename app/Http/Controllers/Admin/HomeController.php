<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request){
    $year = (int) $request->query('year', now()->year);

    // Tiket per bulan (Jan-Des), bulan tanpa tiket bernilai 0
    $perMonth = Ticket::whereYear('created_at', $year)
        ->get(['created_at'])
        ->groupBy(fn ($ticket) => $ticket->created_at->month)
        ->map->count();

    $monthly = collect(range(1, 12))
        ->map(fn ($month) => $perMonth->get($month, 0))
        ->values();

    // 5 departemen yang paling banyak mengajukan tiket
    $departments = Ticket::with('department:id,department_name')
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

    // Daftar tahun untuk dropdown (selalu memuat tahun berjalan)
    $years = Ticket::pluck('created_at')
        ->map(fn ($date) => $date->year)
        ->push(now()->year)
        ->unique()
        ->sortDesc()
        ->values();

    $tickets = Ticket::with(['department', 'creator','technician'])->latest()->get();
        return Inertia::render('Admin/Home',[
            'tickets' => $tickets,
            'chart' => [
            'year'        => $year,
            'years'       => $years,
            'monthly'     => $monthly,
            'departments' => $departments,
        ],
        ]);
    }
}
