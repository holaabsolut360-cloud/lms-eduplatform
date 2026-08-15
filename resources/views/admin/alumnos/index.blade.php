@extends('layouts.admin')

@section('titulo', 'Alumnos')

@section('contenido')

<form method="GET" class="flex gap-2 mb-5 max-w-sm">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por nombre o correo..."
           class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm">
    <button class="bg-marca text-white text-sm font-semibold px-4 rounded-lg">Buscar</button>
</form>

<div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-xs text-slate-500 uppercase">
            <tr>
                <th class="text-left px-5 py-3">Alumno</th>
                <th class="text-left px-5 py-3">Correo</th>
                <th class="text-left px-5 py-3">Cursos</th>
                <th class="text-left px-5 py-3">Registrado</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($alumnos as $alumno)
                <tr class="border-t border-slate-50">
                    <td class="px-5 py-3 font-medium text-slate-800">{{ $alumno->nombre }}</td>
                    <td class="px-5 py-3 text-slate-500">{{ $alumno->email }}</td>
                    <td class="px-5 py-3 text-slate-500">{{ $alumno->matriculas_count }}</td>
                    <td class="px-5 py-3 text-slate-500">{{ $alumno->created_at->format('d/m/Y') }}</td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('admin.alumnos.show', $alumno) }}" class="text-marca text-xs font-medium">Ver ficha →</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-slate-400 py-10">No se encontraron alumnos.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">{{ $alumnos->links() }}</div>
@endsection
