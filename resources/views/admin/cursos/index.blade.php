@extends('layouts.admin')

@section('titulo', 'Mis cursos')

@section('contenido')
<div class="flex items-center justify-between mb-5">
    <p class="text-sm text-slate-500">Gestiona el contenido, precios y estado de tus cursos.</p>
    <a href="{{ route('admin.cursos.create') }}" class="bg-marca text-white text-sm font-semibold px-4 py-2 rounded-lg flex items-center gap-1">
        <i class="ti ti-plus"></i> Nuevo curso
    </a>
</div>

<div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-xs text-slate-500 uppercase">
            <tr>
                <th class="text-left px-5 py-3">Curso</th>
                <th class="text-left px-5 py-3">Categoría</th>
                <th class="text-left px-5 py-3">Precio</th>
                <th class="text-left px-5 py-3">Alumnos</th>
                <th class="text-left px-5 py-3">Estado</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($cursos as $curso)
                <tr class="border-t border-slate-50">
                    <td class="px-5 py-3 font-medium text-slate-800">{{ $curso->titulo }}</td>
                    <td class="px-5 py-3 text-slate-500">{{ $curso->categoria->nombre ?? '—' }}</td>
                    <td class="px-5 py-3 text-slate-500">{{ $curso->moneda === 'USD' ? '$' : 'S/' }} {{ number_format($curso->precio, 2) }}</td>
                    <td class="px-5 py-3 text-slate-500">{{ $curso->matriculas_count }}</td>
                    <td class="px-5 py-3">
                        <span class="text-xs px-2.5 py-1 rounded-full
                            {{ $curso->estado === 'publicado' ? 'bg-green-50 text-green-600' : ($curso->estado === 'archivado' ? 'bg-slate-100 text-slate-500' : 'bg-amber-50 text-amber-600') }}">
                            {{ ucfirst($curso->estado) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('admin.cursos.edit', $curso) }}" class="text-marca text-xs font-medium">Editar</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-slate-400 py-10">Todavía no tienes cursos creados.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">{{ $cursos->links() }}</div>
@endsection
