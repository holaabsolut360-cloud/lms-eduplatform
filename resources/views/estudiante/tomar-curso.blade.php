@extends('layouts.estudio')

@section('titulo', $leccionActual->titulo)

@php
    $todasLecciones = $curso->modulos->flatMap->lecciones->values();
    $totalLecciones = $todasLecciones->count();
    $completadas = count($completadasIds);
    $porcentaje = $totalLecciones > 0 ? (int) round(($completadas / $totalLecciones) * 100) : 0;
@endphp

@section('contenido')
<div class="flex flex-col lg:flex-row" style="min-height: calc(100vh - 3.5rem)">

    {{-- REPRODUCTOR --}}
    <div class="flex-1 flex flex-col">
        <div class="flex-1 bg-gradient-to-br from-[#1a1c33] to-[#0d0e1a] flex items-center justify-center relative">
            @if($leccionActual->tipo === 'video' && $leccionActual->youtubeId())
                <iframe class="w-full h-full absolute inset-0"
                        src="https://www.youtube-nocookie.com/embed/{{ $leccionActual->youtubeId() }}?rel=0"
                        title="{{ $leccionActual->titulo }}" frameborder="0" allowfullscreen></iframe>
            @elseif($leccionActual->tipo === 'texto')
                <div class="max-w-2xl w-full p-8 text-slate-200 leading-relaxed overflow-y-auto max-h-[70vh]">
                    {!! $leccionActual->contenido_html !!}
                </div>
            @elseif(in_array($leccionActual->tipo, ['pdf', 'archivo']) && $leccionActual->archivo_url)
                <a href="{{ asset('storage/' . $leccionActual->archivo_url) }}" target="_blank"
                   class="bg-marca text-white px-6 py-3 rounded-full font-semibold flex items-center gap-2">
                    <i class="ti ti-download"></i> Descargar material
                </a>
            @else
                <div class="text-slate-500 text-sm">Contenido no disponible.</div>
            @endif
        </div>

        <div class="bg-[#12132a] px-5 py-4 flex items-center justify-between gap-4">
            <div>
                <div class="font-semibold text-sm">{{ $leccionActual->titulo }}</div>
                <div class="text-xs text-slate-400">{{ $leccionActual->modulo->titulo }} · {{ $leccionActual->duracion_minutos }} min</div>
            </div>
            <form method="POST" action="{{ route('estudiante.curso.completar', [$curso, $leccionActual]) }}">
                @csrf
                <button class="bg-marca text-white text-sm font-semibold px-5 py-2.5 rounded-lg flex items-center gap-2 hover:opacity-90">
                    @if(in_array($leccionActual->id, $completadasIds))
                        <i class="ti ti-check"></i> Completada
                    @else
                        Marcar como completada
                    @endif
                </button>
            </form>
        </div>
    </div>

    {{-- SIDEBAR DE CONTENIDO --}}
    <div class="w-full lg:w-72 flex-shrink-0 bg-[#12132a] border-l border-white/5 flex flex-col">
        <div class="p-4">
            <div class="text-xs text-slate-400 mb-1">Tu progreso</div>
            <div class="flex items-center gap-2">
                <div class="flex-1 h-1.5 bg-white/10 rounded-full">
                    <div class="h-full bg-marca rounded-full" style="width: {{ $porcentaje }}%"></div>
                </div>
                <span class="text-xs font-semibold">{{ $porcentaje }}%</span>
            </div>

            @if($porcentaje >= 100 && $curso->certificado_habilitado)
                <a href="{{ route('estudiante.certificado.mostrar', $curso) }}"
                   class="mt-3 flex items-center gap-2 bg-marca/15 border border-marca/30 text-white text-xs font-medium px-3 py-2.5 rounded-lg hover:bg-marca/25">
                    <i class="ti ti-certificate text-marca"></i> Ver mi certificado
                </a>
            @endif
        </div>

        <div class="flex-1 overflow-y-auto px-2 pb-4">
            @foreach($curso->modulos as $modulo)
                <div class="text-xs text-slate-500 uppercase tracking-wide px-3 pt-3 pb-1">{{ $modulo->titulo }}</div>
                @foreach($modulo->lecciones as $leccion)
                    @php
                        $completada = in_array($leccion->id, $completadasIds);
                        $esActual = $leccion->id === $leccionActual->id;
                        $posicion = $todasLecciones->search(fn($l) => $l->id === $leccion->id);
                        $anterior = $posicion > 0 ? $todasLecciones->get($posicion - 1) : null;
                        $desbloqueada = !$bloqueoSecuencial || $leccion->es_preview_gratis || $posicion === 0 || ($anterior && in_array($anterior->id, $completadasIds));
                    @endphp
                    @if($desbloqueada)
                        <a href="{{ route('estudiante.curso.leccion', [$curso, $leccion]) }}"
                           class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm {{ $esActual ? 'bg-marca/15 border border-marca/30 text-white font-medium' : 'text-slate-300 hover:bg-white/5' }}">
                            <i class="ti {{ $completada ? 'ti-circle-check-filled text-green-400' : ($esActual ? 'ti-player-play-filled text-marca' : 'ti-player-play') }}"></i>
                            <span class="flex-1 truncate">{{ $leccion->titulo }}</span>
                            <span class="text-xs text-slate-500">{{ $leccion->duracion_minutos }}m</span>
                        </a>
                    @else
                        <div class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-600">
                            <i class="ti ti-lock"></i>
                            <span class="flex-1 truncate">{{ $leccion->titulo }}</span>
                        </div>
                    @endif
                @endforeach

                @foreach($curso->examenes->where('modulo_id', $modulo->id) as $examen)
                    <a href="{{ route('estudiante.examen.mostrar', [$curso, $examen]) }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-300 hover:bg-white/5">
                        <i class="ti ti-clipboard-list text-amber-400"></i>
                        <span class="flex-1 truncate">{{ $examen->titulo }}</span>
                    </a>
                @endforeach
                @foreach($curso->tareas->where('modulo_id', $modulo->id) as $tarea)
                    <a href="{{ route('estudiante.tarea.mostrar', [$curso, $tarea]) }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-300 hover:bg-white/5">
                        <i class="ti ti-file-text text-green-400"></i>
                        <span class="flex-1 truncate">{{ $tarea->titulo }}</span>
                    </a>
                @endforeach
            @endforeach
        </div>
    </div>

</div>
@endsection
