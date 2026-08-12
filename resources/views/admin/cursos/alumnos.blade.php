@extends('layouts.admin')

@section('titulo', 'Alumnos · ' . $curso->titulo)

@section('contenido')

<a href="{{ route('admin.cursos.edit', $curso) }}" class="text-xs text-slate-500 mb-4 inline-block">← Volver al curso</a>

<div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-xs text-slate-500 uppercase">
            <tr>
                <th class="text-left px-5 py-3">Alumno</th>
                <th class="text-left px-5 py-3">Correo</th>
                <th class="text-left px-5 py-3">Progreso</th>
                <th class="text-left px-5 py-3">Estado</th>
                <th class="text-left px-5 py-3">Matriculado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($matriculas as $matricula)
                <tr class="border-t border-slate-50">
                    <td class="px-5 py-3 font-medium text-slate-800">{{ $matricula->estudiante->nombre }}</td>
                    <td class="px-5 py-3 text-slate-500">{{ $matricula->estudiante->email }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2 w-40">
                            <div class="flex-1 h-2 bg-slate-100 rounded-full">
                                <div class="h-full bg-marca rounded-full" style="width: {{ $matricula->porcentaje_avance }}%"></div>
                            </div>
                            <span class="text-xs text-slate-500 w-8">{{ $matricula->porcentaje_avance }}%</span>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        <span class="text-xs px-2.5 py-1 rounded-full
                            {{ $matricula->estado === 'completada' ? 'bg-green-50 text-green-600' : ($matricula->estado === 'suspendida' ? 'bg-red-50 text-red-600' : 'bg-blue-50 text-blue-600') }}">
                            {{ ucfirst($matricula->estado) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-slate-500">{{ $matricula->matriculado_en?->format('d/m/Y') ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-slate-400 py-10">Todavía no hay alumnos matriculados en este curso.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
