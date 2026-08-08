@extends('layouts.publico')

@section('titulo', 'Inicio')

@section('contenido')

    {{-- HERO --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900/90 to-slate-900/60"
             style="background-image: {{ $apariencia->hero_imagen_fondo ? 'linear-gradient(120deg, rgba(15,16,30,.88), rgba(15,16,30,.5)), url(' . asset('storage/' . $apariencia->hero_imagen_fondo) . ')' : 'linear-gradient(135deg, #3a2f6b, var(--color-marca))' }}; background-size: cover; background-position: center;">
        </div>

        <div class="relative max-w-6xl mx-auto px-6 py-24">
            <span class="inline-flex items-center gap-2 bg-marca/90 text-white text-xs font-semibold px-4 py-1.5 rounded-full mb-5">
                <i class="ti ti-certificate"></i> Capacítate
            </span>
            <h1 class="text-3xl sm:text-5xl font-bold text-white max-w-xl leading-tight">
                {{ $apariencia->hero_titulo }}
            </h1>
            @if($apariencia->hero_subtitulo)
                <p class="text-slate-200 max-w-lg mt-4">{{ $apariencia->hero_subtitulo }}</p>
            @endif
            <div class="flex items-center gap-4 mt-8">
                <a href="{{ route('publico.catalogo') }}" class="bg-marca text-white font-semibold px-6 py-3 rounded-full hover:opacity-90">
                    {{ $apariencia->hero_texto_boton }} →
                </a>
                <div class="flex items-center gap-1 text-white text-sm">
                    <i class="ti ti-star-filled text-amber-400"></i> {{ $apariencia->cifra_rating }} rating
                </div>
            </div>
        </div>

        {{-- tarjetas flotantes --}}
        <div class="hidden lg:block absolute top-16 right-16 bg-white rounded-2xl shadow-2xl p-4 w-40">
            <div class="w-9 h-9 rounded-lg bg-marca/10 flex items-center justify-center mb-2">
                <i class="ti ti-users text-marca"></i>
            </div>
            <div class="text-xl font-bold text-slate-900">{{ $apariencia->cifra_estudiantes }}</div>
            <div class="text-xs text-slate-500">Estudiantes</div>
        </div>
        <div class="hidden lg:block absolute top-48 right-56 bg-marca rounded-2xl shadow-2xl p-4 w-28">
            <div class="text-xl font-bold text-white">{{ $apariencia->cifra_empresas }}</div>
            <div class="text-xs text-white/80">Empresas</div>
        </div>
    </section>

    {{-- CATEGORÍAS --}}
    @if($categorias->isNotEmpty())
    <section class="max-w-6xl mx-auto px-6 py-20 text-center">
        <div class="text-marca text-xs font-semibold tracking-wide mb-2">◆ CATEGORÍAS TOP</div>
        <h2 class="text-2xl font-bold text-slate-900 mb-10">Las mejores recomendaciones</h2>

        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-4 text-left">
            @foreach($categorias as $categoria)
                <a href="{{ route('publico.catalogo', ['categoria' => $categoria->id]) }}"
                   class="flex items-center gap-3 border border-slate-100 rounded-xl px-4 py-3 hover:shadow-md hover:border-marca/30 transition">
                    <div class="w-10 h-10 rounded-lg bg-marca/10 flex items-center justify-center flex-shrink-0">
                        <i class="ti {{ $categoria->icono ?: 'ti-books' }} text-marca text-lg"></i>
                    </div>
                    <div>
                        <div class="font-semibold text-slate-900 text-sm">{{ $categoria->nombre }}</div>
                        <div class="text-xs text-slate-400">{{ $categoria->cursos_count }} cursos</div>
                    </div>
                </a>
            @endforeach
        </div>

        <a href="{{ route('publico.catalogo') }}" class="inline-flex items-center gap-2 bg-marca text-white text-sm font-semibold px-6 py-3 rounded-full mt-10 hover:opacity-90">
            Ver todas las categorías →
        </a>
    </section>
    @endif

    {{-- CURSOS DESTACADOS --}}
    @if($destacados->isNotEmpty())
    <section class="max-w-6xl mx-auto px-6 py-10">
        <h2 class="text-2xl font-bold text-slate-900 mb-8">Cursos destacados</h2>
        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($destacados as $curso)
                <a href="{{ route('publico.curso.detalle', $curso) }}" class="rounded-2xl overflow-hidden border border-slate-100 hover:shadow-lg transition group">
                    <div class="h-32 bg-gradient-to-br from-slate-800 to-marca flex items-center justify-center"
                         @if($curso->imagen_portada) style="background-image:url({{ asset('storage/' . $curso->imagen_portada) }});background-size:cover;background-position:center" @endif>
                        <i class="ti ti-player-play-filled text-white/90 text-2xl group-hover:scale-110 transition"></i>
                    </div>
                    <div class="p-4">
                        <div class="font-semibold text-slate-900 text-sm mb-1">{{ $curso->titulo }}</div>
                        <div class="text-xs text-slate-400 mb-3">{{ $curso->totalLecciones() }} lecciones · {{ ucfirst($curso->nivel) }}</div>
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-slate-900">{{ $curso->moneda === 'USD' ? '$' : 'S/' }} {{ number_format($curso->precio_oferta ?? $curso->precio, 2) }}</span>
                            @if($curso->precio_oferta)
                                <span class="text-xs text-slate-400 line-through">{{ $curso->moneda === 'USD' ? '$' : 'S/' }} {{ number_format($curso->precio, 2) }}</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- NOSOTROS --}}
    @if($apariencia->nosotros_texto)
    <section class="bg-slate-50 py-16 mt-10">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-marca text-xs font-semibold mb-2">◆ ACERCA DE NOSOTROS</div>
            <h2 class="text-2xl font-bold text-slate-900 mb-4">Creamos experiencias únicas</h2>
            <p class="text-slate-600 max-w-2xl">{{ $apariencia->nosotros_texto }}</p>
        </div>
    </section>
    @endif

@endsection
