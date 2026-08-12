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

        $ingresosMes = $this->ingresosDelMes(now()->month, now()->year, $esAdmin, $user->id);
        $ingresosMesAnterior = $this->ingresosDelMes(now()->subMonth()->month, now()->subMonth()->year, $esAdmin, $user->id);
        $crecimientoIngresos = $this->porcentajeCrecimiento($ingresosMesAnterior, $ingresosMes);

        $alumnosNuevosMes = $this->alumnosNuevosDelMes(now()->month, now()->year, $esAdmin, $user->id);
        $alumnosNuevosMesAnterior = $this->alumnosNuevosDelMes(now()->subMonth()->month, now()->subMonth()->year, $esAdmin, $user->id);
        $crecimientoAlumnos = $this->porcentajeCrecimiento($alumnosNuevosMesAnterior, $alumnosNuevosMes);

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
            'ingresosMes', 'crecimientoIngresos', 'alumnosNuevosMes', 'crecimientoAlumnos',
            'ordenesPendientes', 'totalCursos', 'totalAlumnos', 'cursosTop', 'ultimasOrdenes'
        ));
    }

    private function ingresosDelMes(int $mes, int $anio, bool $esAdmin, int $instructorId): float
    {
        return (float) Orden::where('estado', 'aprobada')
            ->whereMonth('revisado_en', $mes)
            ->whereYear('revisado_en', $anio)
            ->when(!$esAdmin, fn ($q) => $q->whereHas('curso', fn ($c) => $c->where('instructor_id', $instructorId)))
            ->sum('monto');
    }

    private function alumnosNuevosDelMes(int $mes, int $anio, bool $esAdmin, int $instructorId): int
    {
        return Matricula::where('estado', '!=', 'pendiente_pago')
            ->whereMonth('matriculado_en', $mes)
            ->whereYear('matriculado_en', $anio)
            ->when(!$esAdmin, fn ($q) => $q->whereHas('curso', fn ($c) => $c->where('instructor_id', $instructorId)))
            ->count();
    }

    // Devuelve null si no hay dato del mes anterior para comparar (evita divisiones por cero engañosas)
    private function porcentajeCrecimiento(float $anterior, float $actual): ?int
    {
        if ($anterior <= 0) {
            return $actual > 0 ? 100 : null;
        }

        return (int) round((($actual - $anterior) / $anterior) * 100);
    }
}
