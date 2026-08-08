@extends('layouts.admin')

@section('titulo', $examen->titulo)

@section('contenido')

<a href="{{ route('admin.cursos.edit', $curso) }}" class="text-xs text-slate-500 mb-4 inline-block">← Volver al curso</a>

<div class="max-w-2xl space-y-4">
    @foreach($examen->preguntas as $i => $pregunta)
        <div class="bg-white rounded-2xl border border-slate-100 p-5">
            <div class="flex justify-between items-start mb-2">
                <span class="text-sm font-medium">{{ $i + 1 }}. {{ $pregunta->enunciado }}</span>
                <form method="POST" action="{{ route('admin.preguntas.destroy', [$examen, $pregunta]) }}" onsubmit="return confirm('¿Eliminar esta pregunta?')">
                    @csrf @method('DELETE')
                    <button class="text-red-400 text-xs"><i class="ti ti-trash"></i></button>
                </form>
            </div>
            <div class="text-xs text-slate-400 mb-2">{{ ucfirst(str_replace('_',' ',$pregunta->tipo)) }} · {{ $pregunta->puntaje }} pts</div>
            @if($pregunta->tipo !== 'respuesta_corta')
                @foreach($pregunta->opciones as $opcion)
                    <div class="text-xs {{ $opcion->es_correcta ? 'text-green-600 font-medium' : 'text-slate-500' }} pl-3">
                        {{ $opcion->es_correcta ? '✓' : '·' }} {{ $opcion->texto }}
                    </div>
                @endforeach
            @else
                <div class="text-xs text-green-600">Respuesta esperada: {{ $pregunta->respuesta_esperada }}</div>
            @endif
        </div>
    @endforeach

    <div class="bg-white rounded-2xl border border-slate-100 p-5">
        <h3 class="font-semibold text-sm mb-3">+ Agregar pregunta</h3>
        <form method="POST" action="{{ route('admin.preguntas.store', $examen) }}" class="space-y-2" x-data="{ tipo: 'opcion_multiple' }">
            @csrf
            <textarea name="enunciado" placeholder="Enunciado de la pregunta" required rows="2" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm"></textarea>
            <div class="grid grid-cols-2 gap-2">
                <select name="tipo" onchange="document.getElementById('bloque-opciones').style.display = this.value === 'respuesta_corta' ? 'none' : 'block'; document.getElementById('bloque-corta').style.display = this.value === 'respuesta_corta' ? 'block' : 'none';" class="border border-slate-200 rounded-lg px-3 py-2 text-sm">
                    <option value="opcion_multiple">Opción múltiple</option>
                    <option value="verdadero_falso">Verdadero / Falso</option>
                    <option value="respuesta_corta">Respuesta corta</option>
                </select>
                <input type="number" name="puntaje" value="1" min="1" placeholder="Puntaje" class="border border-slate-200 rounded-lg px-3 py-2 text-sm">
            </div>

            <div id="bloque-opciones" class="space-y-1">
                <div class="text-xs text-slate-500">Opciones (marca la(s) correcta(s)):</div>
                @for($i = 0; $i < 4; $i++)
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="opciones[{{ $i }}][es_correcta]" value="1">
                        <input type="text" name="opciones[{{ $i }}][texto]" placeholder="Opción {{ $i + 1 }}" class="flex-1 border border-slate-200 rounded-lg px-3 py-1.5 text-sm">
                    </div>
                @endfor
            </div>
            <div id="bloque-corta" style="display:none">
                <input type="text" name="respuesta_esperada" placeholder="Respuesta esperada (texto exacto)" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
            </div>

            <button class="bg-marca text-white text-sm font-semibold px-5 py-2 rounded-lg">Agregar pregunta</button>
        </form>
    </div>
</div>
@endsection
