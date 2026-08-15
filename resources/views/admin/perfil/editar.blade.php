@extends('layouts.admin')

@section('titulo', 'Mi perfil')

@section('contenido')

<div class="grid lg:grid-cols-2 gap-6 max-w-3xl">

    <div class="bg-white rounded-2xl border border-slate-100 p-5">
        <h3 class="font-semibold text-sm text-slate-900 mb-4"><i class="ti ti-user"></i> Datos personales</h3>
        <form method="POST" action="{{ route('admin.perfil.actualizar') }}" class="space-y-3">
            @csrf @method('PUT')
            <div>
                <label class="text-xs text-slate-500">Nombre</label>
                <input type="text" name="nombre" value="{{ old('nombre', auth()->user()->nombre) }}" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1">
                @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs text-slate-500">Correo</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs text-slate-500">Teléfono</label>
                <input type="text" name="telefono" value="{{ old('telefono', auth()->user()->telefono) }}" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1">
            </div>
            <div>
                <label class="text-xs text-slate-500">Rol</label>
                <input type="text" value="{{ ucfirst(auth()->user()->rol) }}" disabled class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1 bg-slate-50 text-slate-400">
            </div>
            <button class="bg-marca text-white text-sm font-semibold px-5 py-2 rounded-lg">Guardar cambios</button>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 p-5">
        <h3 class="font-semibold text-sm text-slate-900 mb-4"><i class="ti ti-lock"></i> Cambiar contraseña</h3>
        <form method="POST" action="{{ route('admin.perfil.contrasena') }}" class="space-y-3">
            @csrf @method('PUT')
            <div>
                <label class="text-xs text-slate-500">Contraseña actual</label>
                <input type="password" name="contrasena_actual" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1">
                @error('contrasena_actual') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs text-slate-500">Nueva contraseña</label>
                <input type="password" name="password" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs text-slate-500">Confirmar nueva contraseña</label>
                <input type="password" name="password_confirmation" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1">
            </div>
            <button class="bg-slate-800 text-white text-sm font-semibold px-5 py-2 rounded-lg">Cambiar contraseña</button>
        </form>
    </div>

</div>
@endsection
