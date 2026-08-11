<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MetodoPago;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MetodoPagoController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()->esAdministrador(), 403, 'Solo un administrador puede gestionar los métodos de pago.');

        $metodos = MetodoPago::orderBy('moneda')->orderBy('orden')->get();

        return view('admin.metodos-pago.index', compact('metodos'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->esAdministrador(), 403);

        $data = $this->validarDatos($request);
        $data['orden'] = MetodoPago::max('orden') + 1;

        if ($request->hasFile('qr_imagen')) {
            $data['qr_imagen_url'] = $request->file('qr_imagen')->store('metodos-pago', 'public');
        }

        MetodoPago::create($data);

        return back()->with('success', 'Método de pago creado.');
    }

    public function update(MetodoPago $metodo, Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->esAdministrador(), 403);

        $data = $this->validarDatos($request);

        if ($request->hasFile('qr_imagen')) {
            $data['qr_imagen_url'] = $request->file('qr_imagen')->store('metodos-pago', 'public');
        }

        $metodo->update($data);

        return back()->with('success', 'Método de pago actualizado.');
    }

    public function toggle(MetodoPago $metodo): RedirectResponse
    {
        abort_unless(auth()->user()->esAdministrador(), 403);

        $metodo->update(['activo' => ! $metodo->activo]);

        return back()->with('success', $metodo->activo ? 'Método activado.' : 'Método desactivado.');
    }

    public function destroy(MetodoPago $metodo): RedirectResponse
    {
        abort_unless(auth()->user()->esAdministrador(), 403);

        $metodo->delete();

        return back()->with('success', 'Método de pago eliminado.');
    }

    private function validarDatos(Request $request): array
    {
        return $request->validate([
            'tipo' => ['required', 'in:yape,plin,cuenta_bancaria'],
            'moneda' => ['required', 'in:PEN,USD'],
            'titular' => ['required', 'string', 'max:255'],
            'numero_celular' => ['nullable', 'string', 'max:20'],
            'banco' => ['nullable', 'string', 'max:100'],
            'numero_cuenta' => ['nullable', 'string', 'max:50'],
            'numero_cci' => ['nullable', 'string', 'max:50'],
            'qr_imagen' => ['nullable', 'image', 'max:2048'],
        ]);
    }
}
