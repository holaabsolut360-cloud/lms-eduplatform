<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Orden;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrdenController extends Controller
{
    public function index(Request $request): View
    {
        $ordenes = Orden::with('curso', 'estudiante', 'metodoPago', 'auditoria.admin')
            ->when($request->filled('estado'), fn ($q) => $q->where('estado', $request->estado))
            ->latest()
            ->paginate(20);

        return view('admin.pagos.index', compact('ordenes'));
    }

    public function aprobar(Orden $orden): RedirectResponse
    {
        abort_if($orden->estado === 'aprobada', 400, 'Esta orden ya fue aprobada.');

        $orden->aprobar(auth()->user());

        \Illuminate\Support\Facades\Mail::to($orden->estudiante)->send(new \App\Mail\OrdenAprobadaMail($orden));

        return back()->with('success', "Orden {$orden->codigo} aprobada. La matrícula del alumno ya está activa.");
    }

    public function rechazar(Orden $orden, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'motivo_rechazo' => ['required', 'string', 'max:500'],
        ]);

        $orden->rechazar(auth()->user(), $data['motivo_rechazo']);

        return back()->with('success', "Orden {$orden->codigo} rechazada.");
    }
}
