@extends('layouts.estudio')

@section('titulo', $examen->titulo)

@section('contenido')
<div class="max-w-2xl mx-auto px-5 py-10">

    <h1 class="text-xl font-bold mb-1">{{ $examen->titulo }}</h1>
    @if($examen->instrucciones)
        <p class="text-slate-400 text-sm mb-4">{{ $examen->instrucciones }}</p>
    @endif

    <div class="flex gap-4 text-xs text-slate-400 mb-8">
        <span><i class="ti ti-repeat"></i> {{ $intentosRestantes }} intento(s) restante(s)</span>
        <span><i class="ti ti-target-arrow"></i> Nota mínima: {{ $examen->nota_minima_aprobacion }}/100</span>
        @if($examen->tiempo_limite_min)
            <span><i class="ti ti-clock"></i> {{ $examen->tiempo_limite_min }} min</span>
        @endif
    </div>

    @if($ultimoIntento)
        <div class="rounded-xl border {{ $ultimoIntento->aprobado ? 'border-green-500/30 bg-green-500/5' : 'border-red-500/30 bg-red-500/5' }} p-4 mb-8 flex items-center justify-between">
            <div>
                <div class="text-sm font-semibold {{ $ultimoIntento->aprobado ? 'text-green-300' : 'text-red-300' }}">
                    Último intento: {{ $ultimoIntento->nota_obtenida }}/100 — {{ $ultimoIntento->aprobado ? 'Aprobado' : 'No aprobado' }}
                </div>
            </div>
            <a href="{{ route('estudiante.examen.resultado', [$curso, $examen, $ultimoIntento]) }}" class="text-xs text-marca">Ver detalle</a>
        </div>
    @endif

    @if($intentosRestantes > 0)
        <form method="POST" action="{{ route('estudiante.examen.enviar', [$curso, $examen]) }}" class="space-y-6">
            @csrf
            @foreach($examen->preguntas as $i => $pregunta)
                <div class="bg-[#12132a] rounded-xl p-5">
                    <div class="text-sm font-medium mb-3">{{ $i + 1 }}. {{ $pregunta->enunciado }} <span class="text-xs text-slate-500">({{ $pregunta->puntaje }} pts)</span></div>
                    <input type="hidden" name="respuestas[{{ $i }}][pregunta_id]" value="{{ $pregunta->id }}">

                    @if($pregunta->tipo === 'respuesta_corta')
                        <input type="text" name="respuestas[{{ $i }}][respuesta]" required
                               class="w-full bg-[#0d0e1a] border border-white/10 rounded-lg px-3 py-2 text-sm">
                    @else
                        <div class="space-y-2">
                            @foreach($pregunta->opciones as $opcion)
                                <label class="flex items-center gap-2 text-sm text-slate-300 bg-[#0d0e1a] rounded-lg px-3 py-2 cursor-pointer has-[:checked]:border has-[:checked]:border-marca/40">
                                    <input type="radio" name="respuestas[{{ $i }}][respuesta]" value="{{ $opcion->id }}" required>
                                    {{ $opcion->texto }}
                                </label>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach

            <button class="w-full bg-marca text-white font-semibold py-3 rounded-xl hover:opacity-90">Enviar examen</button>
        </form>
    @else
        <div class="text-center text-slate-400 text-sm py-10">Ya utilizaste todos tus intentos disponibles para este examen.</div>
    @endif

</div>
@endsection
