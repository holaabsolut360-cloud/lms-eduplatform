<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'Panel') · Admin</title>
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
        .border-marca { border-color: var(--color-marca); }
    </style>
</head>
<body class="bg-[#f5f6fa] text-slate-800">
<div class="flex min-h-screen">

    <aside class="w-56 flex-shrink-0 bg-[#181b2e] text-white flex flex-col">
        <div class="h-14 flex items-center gap-2 px-4 bg-black/20">
            <span class="w-6 h-6 rounded-md bg-marca flex items-center justify-center text-xs"><i class="ti ti-school"></i></span>
            <span class="font-semibold text-sm">{{ config('app.name', 'EduPlatform') }}</span>
        </div>

        <nav class="py-3 flex-1 overflow-y-auto text-sm">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-5 py-2 {{ request()->routeIs('admin.dashboard') ? 'bg-marca/15 border-l-2 border-marca text-white' : 'text-slate-300 hover:bg-white/5' }}">
                <i class="ti ti-layout-dashboard"></i> Dashboard
            </a>
            <a href="{{ route('admin.agenda.index') }}" class="flex items-center gap-2 px-5 py-2 {{ request()->routeIs('admin.agenda.*') ? 'bg-marca/15 border-l-2 border-marca text-white' : 'text-slate-300 hover:bg-white/5' }}">
                <i class="ti ti-calendar-event"></i> Agenda
            </a>

            <div class="text-[10px] tracking-wide text-slate-500 uppercase px-5 pt-3 pb-1">Enseñanza</div>
            <a href="{{ route('admin.cursos.index') }}" class="flex items-center gap-2 px-5 py-2 {{ request()->routeIs('admin.cursos.*') ? 'bg-marca/15 border-l-2 border-marca text-white' : 'text-slate-300 hover:bg-white/5' }}">
                <i class="ti ti-books"></i> Mis cursos
            </a>
            <a href="{{ route('admin.categorias.index') }}" class="flex items-center gap-2 px-5 py-2 {{ request()->routeIs('admin.categorias.*') ? 'bg-marca/15 border-l-2 border-marca text-white' : 'text-slate-300 hover:bg-white/5' }}">
                <i class="ti ti-category"></i> Categorías
            </a>

            <div class="text-[10px] tracking-wide text-slate-500 uppercase px-5 pt-3 pb-1">Mi web pública</div>
            <a href="{{ route('admin.apariencia.editar') }}" class="flex items-center gap-2 px-5 py-2 {{ request()->routeIs('admin.apariencia.*') ? 'bg-marca/15 border-l-2 border-marca text-white' : 'text-slate-300 hover:bg-white/5' }}">
                <i class="ti ti-palette"></i> Apariencia
            </a>
            <a href="{{ route('admin.pagos.index') }}" class="flex items-center gap-2 px-5 py-2 {{ request()->routeIs('admin.pagos.*') ? 'bg-marca/15 border-l-2 border-marca text-white' : 'text-slate-300 hover:bg-white/5' }}">
                <i class="ti ti-cash"></i> Pagos
            </a>

            @if(auth()->user()->esAdministrador())
                <div class="text-[10px] tracking-wide text-slate-500 uppercase px-5 pt-3 pb-1">Plataforma</div>
                <a href="{{ route('admin.alumnos.index') }}" class="flex items-center gap-2 px-5 py-2 {{ request()->routeIs('admin.alumnos.*') ? 'bg-marca/15 border-l-2 border-marca text-white' : 'text-slate-300 hover:bg-white/5' }}">
                    <i class="ti ti-user-search"></i> Alumnos
                </a>
                <a href="{{ route('admin.metodos-pago.index') }}" class="flex items-center gap-2 px-5 py-2 {{ request()->routeIs('admin.metodos-pago.*') ? 'bg-marca/15 border-l-2 border-marca text-white' : 'text-slate-300 hover:bg-white/5' }}">
                    <i class="ti ti-credit-card"></i> Métodos de pago
                </a>
                <a href="{{ route('admin.usuarios.index') }}" class="flex items-center gap-2 px-5 py-2 {{ request()->routeIs('admin.usuarios.*') ? 'bg-marca/15 border-l-2 border-marca text-white' : 'text-slate-300 hover:bg-white/5' }}">
                    <i class="ti ti-users"></i> Usuarios
                </a>
            @endif
        </nav>

        <div class="p-4 border-t border-white/5">
            <div class="text-xs text-slate-400 mb-2">{{ auth()->user()->nombre }} · {{ ucfirst(auth()->user()->rol) }}</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-xs text-slate-400 hover:text-white flex items-center gap-1"><i class="ti ti-logout"></i> Cerrar sesión</button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0">
        <header class="h-14 bg-white border-b border-slate-100 flex items-center px-6">
            <h1 class="text-sm font-semibold text-slate-900">@yield('titulo', 'Panel')</h1>
        </header>

        <main class="p-6 flex-1 overflow-y-auto">
            @if (session('success'))
                <div class="bg-green-50 text-green-700 border border-green-200 rounded-xl px-4 py-3 text-sm mb-5">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="bg-red-50 text-red-700 border border-red-200 rounded-xl px-4 py-3 text-sm mb-5">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            @yield('contenido')
        </main>
    </div>

</div>
</body>
</html>
