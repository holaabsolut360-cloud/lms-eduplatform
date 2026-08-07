<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\MetodoPago;
use App\Models\Orden;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function mostrar(Curso $curso, Request $request): View
    {
        $moneda = $request->get('moneda', $curso->moneda);
        $metodosPago = MetodoPago::activos()->paraMoneda($moneda)->get();

        return view('publico.checkout', compact('curso', 'metodosPago', 'moneda'));
    }

    public function confirmar(Curso $curso, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'metodo_pago_id' => ['required', 'exists:metodos_pago,id'],
            'moneda' => ['required', 'in:PEN,USD'],
            'comprobante' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $rutaComprobante = $request->file('comprobante')->store('comprobantes', 'public');

        $monto = $data['moneda'] === 'USD' && $curso->precio_oferta
            ? $curso->precio_oferta
            : ($curso->precio_oferta ?? $curso->precio);

        $orden = Orden::create([
            'curso_id' => $curso->id,
            'estudiante_id' => auth()->id(),
            'metodo_pago_id' => $data['metodo_pago_id'],
            'monto' => $monto,
            'moneda' => $data['moneda'],
            'estado' => 'en_revision',
            'comprobante_url' => $rutaComprobante,
        ]);

        return redirect()
            ->route('publico.checkout.gracias', $orden)
            ->with('success', 'Tu comprobante fue recibido. Te avisaremos apenas se active tu acceso.');
    }

    public function gracias(Orden $orden): View
    {
        abort_unless($orden->estudiante_id === auth()->id(), 403);

        return view('publico.checkout-gracias', compact('orden'));
    }
}
