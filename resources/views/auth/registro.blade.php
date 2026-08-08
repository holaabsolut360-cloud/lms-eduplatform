@extends('layouts.publico')

@section('titulo', 'Crear cuenta')

@section('contenido')
<section class="max-w-sm mx-auto px-6 py-20">
    <h1 class="text-xl font-bold text-slate-900 mb-1">Crea tu cuenta</h1>
    <p class="text-sm text-slate-500 mb-6">Regístrate para empezar a aprender.</p>

    <form method="POST" action="{{ route('registro.attempt') }}" class="space-y-4">
        @csrf
        <div>
            <label class="text-sm font-medium text-slate-700">Nombre completo</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}" required autofocus
                   class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm mt-1 focus:outline-none focus:border-marca">
            @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Correo</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm mt-1 focus:outline-none focus:border-marca">
            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Celular (opcional)</label>
            <input type="text" name="telefono" value="{{ old('telefono') }}"
                   class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm mt-1 focus:outline-none focus:border-marca">
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Contraseña</label>
            <input type="password" name="password" required
                   class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm mt-1 focus:outline-none focus:border-marca">
            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Confirmar contraseña</label>
            <input type="password" name="password_confirmation" required
                   class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm mt-1 focus:outline-none focus:border-marca">
        </div>

        <button class="w-full bg-marca text-white font-semibold py-2.5 rounded-lg hover:opacity-90">Crear cuenta</button>
    </form>

    <p class="text-sm text-slate-500 text-center mt-6">
        ¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-marca font-medium">Ingresa aquí</a>
    </p>
</section>
@endsection
