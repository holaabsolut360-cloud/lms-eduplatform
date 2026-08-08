@extends('layouts.admin')

@section('titulo', 'Usuarios')

@section('contenido')

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-xs text-slate-500 uppercase">
                <tr>
                    <th class="text-left px-5 py-3">Nombre</th>
                    <th class="text-left px-5 py-3">Correo</th>
                    <th class="text-left px-5 py-3">Rol</th>
                    <th class="text-left px-5 py-3">Estado</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $usuario)
                    <tr class="border-t border-slate-50">
                        <td class="px-5 py-3 font-medium text-slate-800">{{ $usuario->nombre }}</td>
                        <td class="px-5 py-3 text-slate-500">{{ $usuario->email }}</td>
                        <td class="px-5 py-3 text-slate-500">{{ ucfirst($usuario->rol) }}</td>
                        <td class="px-5 py-3">
                            <span class="text-xs px-2.5 py-1 rounded-full {{ $usuario->activo ? 'bg-green-50 text-green-600' : 'bg-slate-100 text-slate-500' }}">
                                {{ $usuario->activo ? 'Activo' : 'Desactivado' }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right">
                            @if($usuario->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.usuarios.desactivar', $usuario) }}">
                                    @csrf
                                    <button class="text-xs text-marca">{{ $usuario->activo ? 'Desactivar' : 'Reactivar' }}</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-slate-400 py-10">No hay instructores/administradores registrados aún.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 p-5 h-fit">
        <h3 class="font-semibold text-sm text-slate-900 mb-4">+ Nuevo usuario interno</h3>
        <form method="POST" action="{{ route('admin.usuarios.store') }}" class="space-y-3">
            @csrf
            <div>
                <label class="text-xs text-slate-500">Nombre</label>
                <input type="text" name="nombre" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1">
            </div>
            <div>
                <label class="text-xs text-slate-500">Correo</label>
                <input type="email" name="email" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1">
            </div>
            <div>
                <label class="text-xs text-slate-500">Contraseña</label>
                <input type="password" name="password" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1">
            </div>
            <div>
                <label class="text-xs text-slate-500">Rol</label>
                <select name="rol" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1">
                    <option value="instructor">Instructor</option>
                    <option value="administrador">Administrador</option>
                </select>
            </div>
            <button class="w-full bg-marca text-white text-sm font-semibold py-2 rounded-lg">Crear usuario</button>
        </form>
    </div>
</div>
@endsection
