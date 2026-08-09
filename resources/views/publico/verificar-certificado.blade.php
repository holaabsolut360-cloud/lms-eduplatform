@extends('layouts.publico')

@section('titulo', 'Verificar certificado')

@section('contenido')
<section class="max-w-md mx-auto px-6 py-16">

    <h1 class="text-xl font-bold text-slate-900 mb-1 text-center">Verificar certificado</h1>
    <p class="text-sm text-slate-500 mb-6 text-center">Ingresa el código que aparece en el certificado para confirmar su autenticidad.</p>

    <form method="GET" action="{{ route('publico.certificado.verificar') }}" class="flex gap-2 mb-8">
        <input type="text" name="codigo" value="{{ $codigo }}" placeholder="Ej: CERT-A1B2C3D4" required
               class="flex-1 border border-slate-200 rounded-full px-4 py-2.5 text-sm font-mono focus:outline-none focus:border-marca">
        <button class="bg-marca text-white text-sm font-semibold px-6 rounded-full">Verificar</button>
    </form>

    @if($codigo)
        @if($certificado)
            <div class="border-2 border-green-200 bg-green-50 rounded-2xl p-6 text-center">
                <i class="ti ti-circle-check text-green-500 text-3xl"></i>
                <div class="font-semibold text-green-700 mt-2 mb-4">Certificado válido</div>
                <div class="text-sm text-slate-600 mb-1"><strong>{{ $certificado->matricula->estudiante->nombre }}</strong></div>
                <div class="text-sm text-slate-600 mb-3">completó el curso <strong>{{ $certificado->matricula->curso->titulo }}</strong></div>
                <div class="text-xs text-slate-400">Emitido el {{ $certificado->emitido_en->format('d/m/Y') }}</div>
            </div>
        @else
            <div class="border-2 border-red-200 bg-red-50 rounded-2xl p-6 text-center">
                <i class="ti ti-circle-x text-red-500 text-3xl"></i>
                <div class="font-semibold text-red-700 mt-2">Código no encontrado</div>
                <div class="text-sm text-slate-500 mt-1">Verifica que el código esté escrito correctamente.</div>
            </div>
        @endif
    @endif

</section>
@endsection
