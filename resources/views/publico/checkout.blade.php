@extends('layouts.publico')

@section('titulo', 'Comprar ' . $curso->titulo)

@section('contenido')
<section class="max-w-md mx-auto px-6 py-14">

    <h1 class="text-xl font-bold text-slate-900 mb-1">Completa tu compra</h1>
    <p class="text-sm text-slate-500 mb-6">{{ $curso->titulo }}</p>

    <div class="flex items-center justify-between bg-slate-50 rounded-xl px-4 py-3 mb-6">
        <span class="text-sm text-slate-600">Total a pagar</span>
        <div class="flex items-center gap-2">
            <span class="font-bold text-slate-900">{{ $moneda === 'USD' ? '$' : 'S/' }} {{ number_format($moneda === 'USD' && $curso->precio_oferta ? $curso->precio_oferta : ($curso->precio_oferta ?? $curso->precio), 2) }}</span>
            <a href="{{ route('publico.checkout', [$curso, 'moneda' => $moneda === 'PEN' ? 'USD' : 'PEN']) }}" class="text-xs text-marca border border-marca/30 rounded px-2 py-0.5">
                Cambiar a {{ $moneda === 'PEN' ? 'USD' : 'PEN' }}
            </a>
        </div>
    </div>

    @if($metodosPago->isEmpty())
        <div class="text-sm text-slate-400 border border-slate-100 rounded-xl p-6 text-center">
            No hay métodos de pago configurados en esta moneda todavía. Contáctanos por WhatsApp para coordinar tu inscripción.
        </div>
    @else
        <form method="POST" action="{{ route('publico.checkout.confirmar', $curso) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" name="moneda" value="{{ $moneda }}">

            <div class="font-semibold text-sm text-slate-800 mb-1">Elige un método de pago</div>

            <div class="space-y-2">
                @foreach($metodosPago as $i => $metodo)
                    <label class="flex items-center gap-3 border rounded-xl px-4 py-3 cursor-pointer has-[:checked]:border-marca has-[:checked]:bg-marca/5">
                        <input type="radio" name="metodo_pago_id" value="{{ $metodo->id }}" {{ $i === 0 ? 'checked' : '' }} required class="accent-[var(--color-marca)]">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold
                            {{ $metodo->tipo === 'yape' ? 'bg-purple-700' : ($metodo->tipo === 'plin' ? 'bg-teal-400' : 'bg-slate-700') }}">
                            @if($metodo->tipo === 'cuenta_bancaria') <i class="ti ti-building-bank"></i> @else {{ strtoupper(substr($metodo->tipo,0,1)) }} @endif
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-medium text-slate-800">{{ ucfirst(str_replace('_',' ',$metodo->tipo)) }}</div>
                            <div class="text-xs text-slate-400">
                                {{ $metodo->tipo === 'cuenta_bancaria' ? ($metodo->banco . ' · ' . $metodo->numero_cuenta) : $metodo->numero_celular }}
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>

            <div class="border border-dashed border-slate-200 rounded-xl p-4">
                <label class="text-sm font-medium text-slate-700 flex items-center gap-2 mb-2">
                    <i class="ti ti-upload text-marca"></i> Subir comprobante de pago (imagen o PDF)
                </label>
                <input type="file" name="comprobante" accept=".jpg,.jpeg,.png,.pdf" required class="text-sm w-full">
                @error('comprobante') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button class="w-full bg-marca text-white font-semibold py-3 rounded-xl hover:opacity-90">
                Confirmar compra
            </button>
            <p class="text-xs text-slate-400 text-center">Tu acceso se activará una vez validado el pago (usualmente en minutos).</p>
        </form>
    @endif

</section>
@endsection
