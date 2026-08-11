<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\Orden;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $esAdmin = $user->esAdministrador();

        $cursosQuery = $esAdmin ? Curso::query() : Curso::where('instructor_id', $user->id);

        $ingresosMes = Orden::where('estado', 'aprobada')
            ->whereMonth('revisado_en', now()->month)
            ->whereYear('revisado_en', now()->year)
            ->when(!$esAdmin, fn ($q) => $q->whereHas('curso', fn ($c) => $c->where('instructor_id', $user->id)))
            ->sum('monto');

        $alumnosNuevosMes = Matricula::where('estado', '!=', 'pendiente_pago')
            ->whereMonth('matriculado_en', now()->month)
            ->whereYear('matriculado_en', now()->year)
            ->when(!$esAdmin, fn ($q) => $q->whereHas('curso', fn ($c) => $c->where('instructor_id', $user->id)))
            ->count();

        $ordenesPendientes = Orden::whereIn('estado', ['pendiente', 'en_revision'])
            ->when(!$esAdmin, fn ($q) => $q->whereHas('curso', fn ($c) => $c->where('instructor_id', $user->id)))
            ->count();

        $totalCursos = (clone $cursosQuery)->count();
        $totalAlumnos = Matricula::where('estado', '!=', 'pendiente_pago')
            ->when(!$esAdmin, fn ($q) => $q->whereHas('curso', fn ($c) => $c->where('instructor_id', $user->id)))
            ->count();

        $cursosTop = (clone $cursosQuery)
            ->withCount(['matriculas' => fn ($q) => $q->where('estado', '!=', 'pendiente_pago')])
            ->orderByDesc('matriculas_count')
            ->limit(5)
            ->get();

        $ultimasOrdenes = Orden::with('curso', 'estudiante')
            ->when(!$esAdmin, fn ($q) => $q->whereHas('curso', fn ($c) => $c->where('instructor_id', $user->id)))
            ->latest()
            ->limit(6)
            ->get();

        return view('admin.dashboard', compact(
            'ingresosMes', 'alumnosNuevosMes', 'ordenesPendientes',
            'totalCursos', 'totalAlumnos', 'cursosTop', 'ultimasOrdenes'
        ));
    }
}
