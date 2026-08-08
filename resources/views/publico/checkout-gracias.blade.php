@extends('layouts.publico')

@section('titulo', 'Gracias por tu compra')

@section('contenido')
<section class="max-w-md mx-auto px-6 py-24 text-center">
    <div class="w-16 h-16 rounded-full bg-marca/10 flex items-center justify-center mx-auto mb-5">
        <i class="ti ti-clock text-marca text-2xl"></i>
    </div>
    <h1 class="text-xl font-bold text-slate-900 mb-2">¡Ya recibimos tu comprobante!</h1>
    <p class="text-slate-500 mb-1">Orden <span class="font-mono text-slate-700">{{ $orden->codigo }}</span></p>
    <p class="text-slate-500 mb-8">Estamos validando tu pago. En cuanto se apruebe, el curso <strong>{{ $orden->curso->titulo }}</strong> aparecerá automáticamente en tu cuenta.</p>
    <a href="{{ route('publico.home') }}" class="inline-block bg-marca text-white font-semibold px-6 py-3 rounded-full">Volver al inicio</a>
</section>
@endsection
