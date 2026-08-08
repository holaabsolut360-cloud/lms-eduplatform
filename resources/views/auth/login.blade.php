@extends('layouts.publico')

@section('titulo', 'Ingresar')

@section('contenido')
<section class="max-w-sm mx-auto px-6 py-20">
    <h1 class="text-xl font-bold text-slate-900 mb-1">Bienvenido de nuevo</h1>
    <p class="text-sm text-slate-500 mb-6">Ingresa para continuar tus cursos.</p>

    <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4">
        @csrf
        <div>
            <label class="text-sm font-medium text-slate-700">Correo</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm mt-1 focus:outline-none focus:border-marca">
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Contraseña</label>
            <input type="password" name="password" required
                   class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm mt-1 focus:outline-none focus:border-marca">
        </div>

        @error('email')
            <p class="text-red-500 text-xs">{{ $message }}</p>
        @enderror

        <label class="flex items-center gap-2 text-sm text-slate-600">
            <input type="checkbox" name="recordar"> Recordarme
        </label>

        <button class="w-full bg-marca text-white font-semibold py-2.5 rounded-lg hover:opacity-90">Ingresar</button>
    </form>

    <p class="text-sm text-slate-500 text-center mt-6">
        ¿No tienes cuenta? <a href="{{ route('registro') }}" class="text-marca font-medium">Regístrate</a>
    </p>
</section>
@endsection
