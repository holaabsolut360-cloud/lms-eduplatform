<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'Estudiando') · {{ config('app.name', 'EduPlatform') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @php($apariencia = \App\Models\ConfiguracionApariencia::actual())
    <style>
        :root { --color-marca: {{ $apariencia->color_marca }}; }
        body { font-family: 'Inter', sans-serif; }
        .bg-marca { background-color: var(--color-marca); }
        .text-marca { color: var(--color-marca); }
    </style>
</head>
<body class="bg-[#0d0e1a] text-white min-h-screen">

    <header class="h-14 flex items-center px-5 gap-3 bg-[#12132a] border-b border-white/5">
        <a href="{{ route('publico.home') }}" class="text-slate-400 hover:text-white">
            <i class="ti ti-arrow-left text-lg"></i>
        </a>
        <span class="text-sm font-medium truncate">{{ $curso->titulo ?? '' }}</span>
        <div class="ml-auto flex items-center gap-2">
            <a href="{{ route('estudiante.curso.index', $curso) }}" class="text-xs text-slate-400 hover:text-white">Contenido</a>
        </div>
    </header>

    @if (session('success'))
        <div class="max-w-3xl mx-auto mt-4 px-5">
            <div class="bg-green-500/10 text-green-300 border border-green-500/20 rounded-xl px-4 py-3 text-sm">{{ session('success') }}</div>
        </div>
    @endif

    @yield('contenido')

</body>
</html>
