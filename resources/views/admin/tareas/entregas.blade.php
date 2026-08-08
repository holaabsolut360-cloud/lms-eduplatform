@extends('layouts.admin')

@section('titulo', 'Entregas · ' . $tarea->titulo)

@section('contenido')

<a href="{{ route('admin.cursos.edit', $curso) }}" class="text-xs text-slate-500 mb-4 inline-block">← Volver al curso</a>

<div class="space-y-3 max-w-3xl">
    @forelse($entregas as $entrega)
        <div class="bg-white rounded-2xl border border-slate-100 p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="font-medium text-sm text-slate-800">{{ $entrega->matricula->estudiante->nombre }}</span>
                <span class="text-xs px-2.5 py-1 rounded-full {{ $entrega->estado === 'calificada' ? 'bg-green-50 text-green-600' : 'bg-amber-50 text-amber-600' }}">
                    {{ $entrega->estado === 'calificada' ? 'Calificada' : 'Pendiente' }}
                </span>
            </div>
            @if($entrega->comentario_alumno)
                <p class="text-sm text-slate-500 mb-2">{{ $entrega->comentario_alumno }}</p>
            @endif
            @if($entrega->archivo_url)
                <a href="{{ asset('storage/' . $entrega->archivo_url) }}" target="_blank" class="text-xs text-marca mb-3 inline-block">Ver archivo entregado</a>
            @endif

            <form method="POST" action="{{ route('admin.tareas.calificar', [$curso, $tarea, $entrega]) }}" class="flex items-end gap-2 mt-2">
                @csrf
                <div>
                    <label class="text-xs text-slate-500">Nota (/{{ $tarea->puntaje_maximo }})</label>
                    <input type="number" name="nota" value="{{ $entrega->nota }}" step="0.1" min="0" max="{{ $tarea->puntaje_maximo }}" required
                           class="w-24 border border-slate-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <input type="text" name="feedback_docente" value="{{ $entrega->feedback_docente }}" placeholder="Feedback (opcional)"
                       class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm">
                <button class="bg-marca text-white text-sm font-semibold px-4 py-2 rounded-lg">Calificar</button>
            </form>
        </div>
    @empty
        <div class="text-center text-slate-400 py-10">Todavía no hay entregas para esta tarea.</div>
    @endforelse
</div>
@endsection
