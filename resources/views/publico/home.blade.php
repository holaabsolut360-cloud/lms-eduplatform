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

    {{-- FRANJA DE KPIs --}}
    <section class="bg-slate-900 py-8">
        <div class="max-w-6xl mx-auto px-6 grid grid-cols-3 gap-6 text-center">
            <div>
                <div class="text-2xl sm:text-3xl font-bold text-white">{{ $totalCursosPublicados }}+</div>
                <div class="text-xs sm:text-sm text-slate-400 mt-1">Cursos disponibles</div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-bold text-white">{{ $apariencia->cifra_estudiantes }}</div>
                <div class="text-xs sm:text-sm text-slate-400 mt-1">Estudiantes</div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-bold text-white flex items-center justify-center gap-1">
                    {{ $apariencia->cifra_rating }} <i class="ti ti-star-filled text-amber-400 text-lg"></i>
                </div>
                <div class="text-xs sm:text-sm text-slate-400 mt-1">Calificación promedio</div>
            </div>
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
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-slate-900">Nuestros mejores cursos</h2>
            <a href="{{ route('publico.catalogo') }}" class="text-marca text-sm font-semibold hidden sm:block">Ver todos →</a>
        </div>
        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($destacados as $curso)
                <a href="{{ route('publico.curso.detalle', $curso) }}" class="rounded-2xl overflow-hidden border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition group bg-white">
                    <div class="h-36 bg-gradient-to-br from-slate-800 to-marca flex items-center justify-center relative"
                         @if($curso->imagen_portada) style="background-image:url({{ asset('storage/' . $curso->imagen_portada) }});background-size:cover;background-position:center" @endif>
                        <span class="absolute top-3 left-3 bg-black/50 text-white text-[10px] font-semibold px-2.5 py-1 rounded-full">{{ ucfirst($curso->nivel) }}</span>
                        <i class="ti ti-player-play-filled text-white/90 text-2xl group-hover:scale-110 transition"></i>
                    </div>
                    <div class="p-4">
                        @if($curso->categoria)
                            <div class="text-[11px] text-marca font-semibold uppercase tracking-wide mb-1">{{ $curso->categoria->nombre }}</div>
                        @endif
                        <div class="font-semibold text-slate-900 text-sm mb-1 leading-snug">{{ $curso->titulo }}</div>
                        <div class="text-xs text-slate-400 mb-3 flex items-center gap-1"><i class="ti ti-books"></i> {{ $curso->totalLecciones() }} lecciones</div>
                        <div class="flex items-center justify-between pt-3 border-t border-slate-50">
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

    {{-- BENEFICIOS --}}
    <section class="bg-slate-50 py-20">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-12">
                <div class="text-marca text-xs font-semibold tracking-wide mb-2">◆ POR QUÉ ELEGIRNOS</div>
                <h2 class="text-2xl font-bold text-slate-900">Beneficios de estudiar con nosotros</h2>
            </div>
            <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl p-6 border border-slate-100">
                    <div class="w-11 h-11 rounded-xl bg-marca/10 flex items-center justify-center mb-4">
                        <i class="ti ti-certificate text-marca text-xl"></i>
                    </div>
                    <div class="font-semibold text-slate-900 mb-1">Certifícate</div>
                    <div class="text-sm text-slate-500">Recibe tu certificado digital verificable al completar cada curso.</div>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-slate-100">
                    <div class="w-11 h-11 rounded-xl bg-marca/10 flex items-center justify-center mb-4">
                        <i class="ti ti-clock text-marca text-xl"></i>
                    </div>
                    <div class="font-semibold text-slate-900 mb-1">A tu propio ritmo</div>
                    <div class="text-sm text-slate-500">Accede cuando quieras, desde cualquier dispositivo, sin horarios fijos.</div>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-slate-100">
                    <div class="w-11 h-11 rounded-xl bg-marca/10 flex items-center justify-center mb-4">
                        <i class="ti ti-infinity text-marca text-xl"></i>
                    </div>
                    <div class="font-semibold text-slate-900 mb-1">Acceso de por vida</div>
                    <div class="text-sm text-slate-500">Una vez matriculado, el contenido queda disponible para siempre.</div>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-slate-100">
                    <div class="w-11 h-11 rounded-xl bg-marca/10 flex items-center justify-center mb-4">
                        <i class="ti ti-clipboard-check text-marca text-xl"></i>
                    </div>
                    <div class="font-semibold text-slate-900 mb-1">Exámenes prácticos</div>
                    <div class="text-sm text-slate-500">Evalúa tu aprendizaje con quizzes y ejercicios reales del sector.</div>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-slate-100">
                    <div class="w-11 h-11 rounded-xl bg-marca/10 flex items-center justify-center mb-4">
                        <i class="ti ti-device-mobile text-marca text-xl"></i>
                    </div>
                    <div class="font-semibold text-slate-900 mb-1">100% desde el celular</div>
                    <div class="text-sm text-slate-500">Toda la plataforma está optimizada para estudiar desde tu móvil.</div>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-slate-100">
                    <div class="w-11 h-11 rounded-xl bg-marca/10 flex items-center justify-center mb-4">
                        <i class="ti ti-brand-whatsapp text-marca text-xl"></i>
                    </div>
                    <div class="font-semibold text-slate-900 mb-1">Soporte directo</div>
                    <div class="text-sm text-slate-500">Resuelve tus dudas rápido, por WhatsApp o correo.</div>
                </div>
            </div>
        </div>
    </section>

    {{-- NOSOTROS --}}
    @if($apariencia->nosotros_texto)
    <section class="py-16">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-marca text-xs font-semibold mb-2">◆ ACERCA DE NOSOTROS</div>
            <h2 class="text-2xl font-bold text-slate-900 mb-4">Creamos experiencias únicas</h2>
            <p class="text-slate-600 max-w-2xl">{{ $apariencia->nosotros_texto }}</p>
        </div>
    </section>
    @endif

    {{-- BANNER VERIFICAR CERTIFICADO --}}
    <section class="max-w-6xl mx-auto px-6 pb-20">
        <div class="rounded-2xl p-8 sm:p-10 flex flex-col sm:flex-row items-center justify-between gap-6" style="background: linear-gradient(120deg, #1a1c33, var(--color-marca));">
            <div class="text-center sm:text-left">
                <div class="text-white font-bold text-lg mb-1">¿Tienes un certificado nuestro?</div>
                <div class="text-white/70 text-sm">Verifica su autenticidad en segundos con el código de verificación.</div>
            </div>
            <a href="{{ route('publico.certificado.verificar') }}" class="bg-white text-slate-900 font-semibold px-6 py-3 rounded-full hover:opacity-90 whitespace-nowrap">
                Verificar certificado →
            </a>
        </div>
    </section>

@endsection
