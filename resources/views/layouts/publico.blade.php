<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'EduPlatform') · {{ config('app.name', 'EduPlatform') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @php($apariencia = \App\Models\ConfiguracionApariencia::actual())
    <style>
        :root { --color-marca: {{ $apariencia->color_marca }}; }
        body { font-family: 'Inter', sans-serif; }
        .bg-marca { background-color: var(--color-marca); }
        .text-marca { color: var(--color-marca); }
        .border-marca { border-color: var(--color-marca); }
    </style>
    @stack('estilos')
</head>
<body class="bg-white text-slate-800">

    <header class="border-b border-slate-100 sticky top-0 bg-white/90 backdrop-blur z-40">
        <div class="max-w-6xl mx-auto px-6 py-3 flex items-center justify-between">
            <a href="{{ route('publico.home') }}" class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-marca flex items-center justify-center text-white">
                    <i class="ti ti-school"></i>
                </span>
                <span class="font-bold text-slate-900">{{ config('app.name', 'EduPlatform') }}</span>
            </a>

            <nav class="hidden md:flex items-center gap-6 text-sm text-slate-600">
                <a href="{{ route('publico.home') }}" class="hover:text-slate-900">Inicio</a>
                <a href="{{ route('publico.catalogo') }}" class="hover:text-slate-900">Cursos</a>
            </nav>

            <div class="flex items-center gap-3">
                @auth
                    @if(auth()->user()->esInstructor())
                        <a href="{{ route('admin.cursos.index') }}" class="text-sm text-slate-600 hover:text-slate-900">Panel admin</a>
                    @endif
                    <span class="text-sm text-slate-600 hidden sm:inline">{{ auth()->user()->nombre }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-sm font-medium px-4 py-2 rounded-full border border-slate-200 hover:bg-slate-50">Salir</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium px-4 py-2 rounded-full border border-slate-200 hover:bg-slate-50">Ingresar</a>
                    <a href="{{ route('registro') }}" class="text-sm font-semibold text-white px-4 py-2 rounded-full bg-marca">Crear cuenta</a>
                @endauth
            </div>
        </div>
    </header>

    @if (session('success'))
        <div class="max-w-6xl mx-auto px-6 mt-4">
            <div class="bg-green-50 text-green-700 border border-green-200 rounded-xl px-4 py-3 text-sm">
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if (session('error'))
        <div class="max-w-6xl mx-auto px-6 mt-4">
            <div class="bg-red-50 text-red-700 border border-red-200 rounded-xl px-4 py-3 text-sm">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <main>
        @yield('contenido')
    </main>

    <footer class="border-t border-slate-100 mt-20 bg-slate-900">
        <div class="max-w-6xl mx-auto px-6 py-14 grid sm:grid-cols-3 gap-10">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <span class="w-8 h-8 rounded-lg bg-marca flex items-center justify-center text-white">
                        <i class="ti ti-school"></i>
                    </span>
                    <span class="font-bold text-white">{{ config('app.name', 'EduPlatform') }}</span>
                </div>
                <p class="text-sm text-slate-400 max-w-xs">Aprende a tu propio ritmo, con certificado al finalizar cada curso.</p>
            </div>
            <div>
                <div class="text-white font-semibold text-sm mb-3">Enlaces</div>
                <div class="flex flex-col gap-2 text-sm text-slate-400">
                    <a href="{{ route('publico.home') }}" class="hover:text-white">Inicio</a>
                    <a href="{{ route('publico.catalogo') }}" class="hover:text-white">Cursos</a>
                    <a href="{{ route('publico.certificado.verificar') }}" class="hover:text-white">Verificar certificado</a>
                </div>
            </div>
            <div>
                <div class="text-white font-semibold text-sm mb-3">Contacto</div>
                <div class="flex flex-col gap-2 text-sm text-slate-400">
                    @if($apariencia->contacto_telefono)
                        <span class="flex items-center gap-2"><i class="ti ti-phone"></i> {{ $apariencia->contacto_telefono }}</span>
                    @endif
                    @if($apariencia->contacto_email)
                        <span class="flex items-center gap-2"><i class="ti ti-mail"></i> {{ $apariencia->contacto_email }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="border-t border-white/10">
            <div class="max-w-6xl mx-auto px-6 py-5 text-xs text-slate-500 flex flex-col sm:flex-row justify-between gap-2">
                <span>© {{ date('Y') }} {{ config('app.name', 'EduPlatform') }}. Todos los derechos reservados.</span>
                <span>Hecho con Laravel</span>
            </div>
        </div>
    </footer>

    @if($apariencia->contacto_whatsapp)
        <a href="https://wa.me/{{ $apariencia->contacto_whatsapp }}" target="_blank"
           class="fixed bottom-6 right-6 w-14 h-14 rounded-full bg-green-500 text-white flex items-center justify-center shadow-lg hover:bg-green-600 z-40">
            <i class="ti ti-brand-whatsapp text-2xl"></i>
        </a>
    @endif

    @stack('scripts')
</body>
</html>
