@extends('layouts.estudio')

@section('titulo', $tarea->titulo)

@section('contenido')
<div class="max-w-xl mx-auto px-5 py-10">

    <h1 class="text-xl font-bold mb-1">{{ $tarea->titulo }}</h1>
    @if($tarea->fecha_limite)
        <p class="text-xs {{ $tarea->estaVencida() ? 'text-red-400' : 'text-slate-400' }} mb-4">
            <i class="ti ti-calendar"></i> Fecha límite: {{ $tarea->fecha_limite->format('d/m/Y H:i') }}
            {{ $tarea->estaVencida() ? '(vencida)' : '' }}
        </p>
    @endif

    @if($tarea->instrucciones)
        <div class="bg-[#12132a] rounded-xl p-5 text-sm text-slate-300 mb-6 leading-relaxed">{{ $tarea->instrucciones }}</div>
    @endif

    @if($entrega)
        <div class="bg-[#12132a] rounded-xl p-5 mb-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-semibold">Tu entrega</span>
                <span class="text-xs px-2.5 py-1 rounded-full
                    {{ $entrega->estado === 'calificada' ? 'bg-green-500/15 text-green-300' : 'bg-amber-500/15 text-amber-300' }}">
                    {{ $entrega->estado === 'calificada' ? 'Calificada' : 'En revisión' }}
                </span>
            </div>
            @if($entrega->comentario_alumno)
                <p class="text-sm text-slate-400 mb-2">{{ $entrega->comentario_alumno }}</p>
            @endif
            @if($entrega->archivo_url)
                <a href="{{ asset('storage/' . $entrega->archivo_url) }}" target="_blank" class="text-xs text-marca">Ver archivo entregado</a>
            @endif

            @if($entrega->estado === 'calificada')
                <div class="mt-3 pt-3 border-t border-white/10">
                    <div class="text-sm font-semibold">Nota: {{ $entrega->nota }}/{{ $tarea->puntaje_maximo }}</div>
                    @if($entrega->feedback_docente)
                        <p class="text-sm text-slate-400 mt-1">{{ $entrega->feedback_docente }}</p>
                    @endif
                </div>
            @endif
        </div>
    @endif

    @if(!$tarea->estaVencida() && (!$entrega || $entrega->estado !== 'calificada'))
        <form method="POST" action="{{ route('estudiante.tarea.entregar', [$curso, $tarea]) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="text-sm font-medium text-slate-300 block mb-1">Comentario (opcional)</label>
                <textarea name="comentario_alumno" rows="3" class="w-full bg-[#12132a] border border-white/10 rounded-lg px-3 py-2 text-sm"></textarea>
            </div>
            <div>
                <label class="text-sm font-medium text-slate-300 block mb-1">Archivo</label>
                <input type="file" name="archivo" class="text-sm w-full">
            </div>
            <button class="w-full bg-marca text-white font-semibold py-3 rounded-xl hover:opacity-90">
                {{ $entrega ? 'Volver a entregar' : 'Entregar tarea' }}
            </button>
        </form>
    @endif

</div>
@endsection
