<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresar · {{ config('app.name', 'EduPlatform') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @php
        $apariencia = \App\Models\ConfiguracionApariencia::actual();
    @endphp
    <style>
        :root { --color-marca: {{ $apariencia->color_marca }}; }
        body { font-family: 'Inter', sans-serif; }
        .bg-marca { background-color: var(--color-marca); }
        .text-marca { color: var(--color-marca); }
        .border-marca { border-color: var(--color-marca); }
    </style>
</head>
<body class="bg-white">
<div class="min-h-screen grid lg:grid-cols-2">

    @php
        $youtubeId = null;
        if (!$apariencia->login_video_archivo && $apariencia->login_video_url && preg_match('/(?:youtu\.be\/|youtube\.com\/(?:shorts\/|watch\?v=|embed\/))([a-zA-Z0-9_-]{11})/', $apariencia->login_video_url, $m)) {
            $youtubeId = $m[1];
        }
    @endphp

    {{-- LADO IZQUIERDO: VIDEO --}}
    <div class="relative hidden lg:flex items-center justify-center p-10 xl:p-16 overflow-hidden"
         style="background: linear-gradient(135deg, #1a1c33, var(--color-marca));">

        {{-- patrón decorativo de puntos --}}
        <div class="absolute top-8 left-8 grid grid-cols-6 gap-1.5 opacity-20">
            @for($i = 0; $i < 24; $i++)
                <span class="w-1 h-1 rounded-full bg-white"></span>
            @endfor
        </div>

        <div class="relative w-full max-w-[300px] aspect-[9/16] rounded-2xl overflow-hidden shadow-2xl bg-black">
            @if($apariencia->login_video_archivo)
                <video class="w-full h-full object-cover" autoplay loop muted playsinline controls controlsList="nodownload"
                       @if($apariencia->hero_imagen_fondo) poster="{{ asset('storage/' . $apariencia->hero_imagen_fondo) }}" @endif>
                    <source src="{{ asset('storage/' . $apariencia->login_video_archivo) }}" type="video/mp4">
                </video>
            @elseif($youtubeId)
                <iframe class="w-full h-full"
                        src="https://www.youtube-nocookie.com/embed/{{ $youtubeId }}?autoplay=1&mute=1&loop=1&playlist={{ $youtubeId }}&rel=0"
                        frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
            @elseif($apariencia->login_video_url)
                <video class="w-full h-full object-cover" autoplay loop muted playsinline controls controlsList="nodownload"
                       @if($apariencia->hero_imagen_fondo) poster="{{ asset('storage/' . $apariencia->hero_imagen_fondo) }}" @endif>
                    <source src="{{ $apariencia->login_video_url }}" type="video/mp4">
                </video>
            @else
                <div class="w-full h-full flex items-center justify-center">
                    <span class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center">
                        <i class="ti ti-school text-white text-3xl"></i>
                    </span>
                </div>
            @endif
        </div>
    </div>

    {{-- LADO DERECHO: FORMULARIO --}}
    <div class="flex items-center justify-center px-6 py-16">
        <div class="w-full max-w-sm">

            <div class="flex items-center gap-2 mb-8">
                <span class="w-9 h-9 rounded-lg bg-marca flex items-center justify-center text-white">
                    <i class="ti ti-school text-lg"></i>
                </span>
                <span class="font-bold text-slate-900 text-lg">{{ config('app.name', 'EduPlatform') }}</span>
            </div>

            <h1 class="text-2xl font-bold text-slate-900 mb-1">Entrar al aula</h1>
            <div class="w-10 h-1 bg-marca rounded-full mb-4"></div>
            <p class="text-sm text-slate-500 mb-7">Usa tu correo y contraseña para acceder.</p>

            <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4">
                @csrf

                <div class="relative">
                    <i class="ti ti-user absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="correo@ejemplo.com"
                           class="w-full border border-slate-200 rounded-lg pl-10 pr-3 py-2.5 text-sm focus:outline-none focus:border-marca">
                </div>

                <div class="relative">
                    <i class="ti ti-lock absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="password" name="password" id="campo-password" required placeholder="••••••••••"
                           class="w-full border border-slate-200 rounded-lg pl-10 pr-10 py-2.5 text-sm focus:outline-none focus:border-marca">
                    <button type="button" onclick="const p = document.getElementById('campo-password'); p.type = p.type === 'password' ? 'text' : 'password';"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
                        <i class="ti ti-eye"></i>
                    </button>
                </div>

                @error('email')
                    <p class="text-red-500 text-xs">{{ $message }}</p>
                @enderror

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-slate-600">
                        <input type="checkbox" name="recordar"> Recordarme
                    </label>
                    <a href="#" class="text-marca font-medium">¿Olvidó su contraseña?</a>
                </div>

                <button class="w-full bg-marca text-white font-semibold py-3 rounded-lg hover:opacity-90">Acceder</button>
            </form>

            <p class="text-sm text-slate-500 text-center mt-6">
                ¿No tienes cuenta? <a href="{{ route('registro') }}" class="text-marca font-medium">Regístrate</a>
            </p>

            @if($apariencia->contacto_telefono || $apariencia->contacto_email)
                <div class="border-t border-slate-100 mt-8 pt-6 text-center space-y-1.5">
                    @if($apariencia->contacto_telefono)
                        <div class="text-sm text-slate-500 flex items-center justify-center gap-2">
                            <i class="ti ti-phone text-marca"></i> {{ $apariencia->contacto_telefono }}
                        </div>
                    @endif
                    @if($apariencia->contacto_email)
                        <div class="text-sm text-slate-500 flex items-center justify-center gap-2">
                            <i class="ti ti-mail text-marca"></i> {{ $apariencia->contacto_email }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

</div>

@if($apariencia->contacto_whatsapp)
    <a href="https://wa.me/{{ $apariencia->contacto_whatsapp }}" target="_blank"
       class="fixed bottom-6 right-6 w-14 h-14 rounded-full bg-green-500 text-white flex items-center justify-center shadow-lg hover:bg-green-600">
        <i class="ti ti-brand-whatsapp text-2xl"></i>
    </a>
@endif
</body>
</html>
