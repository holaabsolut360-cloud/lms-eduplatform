@extends('layouts.publico')

@section('titulo', 'Editar perfil')

@section('contenido')
<section class="max-w-md mx-auto px-6 py-12">

    <a href="{{ route('estudiante.cuenta.index') }}" class="text-xs text-slate-500 mb-4 inline-block">← Volver a mi cuenta</a>

    <div class="bg-white border border-slate-100 rounded-2xl p-5 mb-6">
        <h3 class="font-semibold text-sm text-slate-900 mb-4"><i class="ti ti-user"></i> Datos personales</h3>
        <form method="POST" action="{{ route('estudiante.cuenta.actualizar') }}" class="space-y-3">
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
                <label class="text-xs text-slate-500">Celular</label>
                <input type="text" name="telefono" value="{{ old('telefono', auth()->user()->telefono) }}" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1">
            </div>
            <button class="w-full bg-marca text-white text-sm font-semibold py-2.5 rounded-lg">Guardar cambios</button>
        </form>
    </div>

    <div class="bg-white border border-slate-100 rounded-2xl p-5">
        <h3 class="font-semibold text-sm text-slate-900 mb-4"><i class="ti ti-lock"></i> Cambiar contraseña</h3>
        <form method="POST" action="{{ route('estudiante.cuenta.contrasena') }}" class="space-y-3">
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
            <button class="w-full bg-slate-800 text-white text-sm font-semibold py-2.5 rounded-lg">Cambiar contraseña</button>
        </form>
    </div>

</section>
@endsection
