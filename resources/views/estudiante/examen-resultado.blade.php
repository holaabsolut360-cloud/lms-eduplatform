@extends('layouts.estudio')

@section('titulo', 'Resultado · ' . $examen->titulo)

@section('contenido')
<div class="max-w-md mx-auto px-5 py-20 text-center">

    <div class="w-16 h-16 rounded-full mx-auto mb-5 flex items-center justify-center {{ $intento->aprobado ? 'bg-green-500/15' : 'bg-red-500/15' }}">
        <i class="ti {{ $intento->aprobado ? 'ti-check text-green-400' : 'ti-x text-red-400' }} text-2xl"></i>
    </div>

    <h1 class="text-2xl font-bold mb-1">{{ $intento->nota_obtenida }}/100</h1>
    <p class="text-slate-400 mb-8">{{ $intento->aprobado ? '¡Aprobaste el examen!' : 'No alcanzaste la nota mínima de ' . $examen->nota_minima_aprobacion }}</p>

    <div class="flex gap-3 justify-center">
        <a href="{{ route('estudiante.curso.index', $curso) }}" class="text-sm border border-white/10 px-5 py-2.5 rounded-full text-slate-300">Volver al curso</a>
        @if(!$intento->aprobado && $examen->intentosRestantes($intento->matricula_id) > 0)
            <a href="{{ route('estudiante.examen.mostrar', [$curso, $examen]) }}" class="text-sm bg-marca text-white px-5 py-2.5 rounded-full font-semibold">Reintentar</a>
        @endif
    </div>

</div>
@endsection
