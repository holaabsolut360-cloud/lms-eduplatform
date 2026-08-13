<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClaseEnVivo;
use Illuminate\View\View;

class AgendaController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $esAdmin = $user->esAdministrador();

        $clases = ClaseEnVivo::with('curso.instructor')
            ->when(!$esAdmin, fn ($q) => $q->whereHas('curso', fn ($c) => $c->where('instructor_id', $user->id)))
            ->orderBy('fecha_hora')
            ->get();

        $proximas = $clases->filter(fn ($c) => !$c->yaPaso())->groupBy(fn ($c) => $c->fecha_hora->format('Y-m-d'));
        $pasadas = $clases->filter(fn ($c) => $c->yaPaso())->sortByDesc('fecha_hora')->take(20)->groupBy(fn ($c) => $c->fecha_hora->format('Y-m-d'));

        return view('admin.agenda.index', compact('proximas', 'pasadas', 'esAdmin'));
    }
}
