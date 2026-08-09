@extends('layouts.publico')

@section('titulo', 'Mi certificado · ' . $curso->titulo)

@section('contenido')
<section class="max-w-2xl mx-auto px-6 py-16 text-center">

    <div class="w-16 h-16 rounded-full bg-marca/10 flex items-center justify-center mx-auto mb-5">
        <i class="ti ti-certificate text-marca text-2xl"></i>
    </div>

    <h1 class="text-2xl font-bold text-slate-900 mb-1">¡Felicidades, {{ $estudiante->nombre }}!</h1>
    <p class="text-slate-500 mb-8">Completaste el curso <strong>{{ $curso->titulo }}</strong></p>

    <div class="border-2 border-marca/20 rounded-2xl p-10 mb-8 bg-gradient-to-br from-marca/5 to-transparent">
        <div class="text-xs text-slate-400 mb-2 tracking-wide uppercase">Certificado de finalización</div>
        <div class="text-xl font-bold text-slate-900 mb-1">{{ $curso->titulo }}</div>
        <div class="text-sm text-slate-500 mb-6">otorgado a {{ $estudiante->nombre }}</div>
        <div class="text-xs text-slate-400">
            Código de verificación: <span class="font-mono text-slate-600">{{ $certificado->codigo_verificacion }}</span>
        </div>
        <div class="text-xs text-slate-400">
            Emitido el {{ $certificado->emitido_en->format('d/m/Y') }}
        </div>
    </div>

    <div class="flex items-center justify-center gap-3">
        <a href="{{ route('estudiante.certificado.descargar', $curso) }}" class="bg-marca text-white font-semibold px-6 py-3 rounded-full hover:opacity-90 flex items-center gap-2">
            <i class="ti ti-download"></i> Descargar PDF
        </a>
        <a href="{{ route('publico.certificado.verificar', $certificado->codigo_verificacion) }}" class="text-sm text-slate-500 border border-slate-200 px-6 py-3 rounded-full">
            Ver página de verificación
        </a>
    </div>

</section>
@endsection
