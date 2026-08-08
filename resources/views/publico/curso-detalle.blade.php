@extends('layouts.publico')

@section('titulo', $curso->titulo)

@section('contenido')
<section class="max-w-6xl mx-auto px-6 py-14 grid lg:grid-cols-3 gap-10">

    <div class="lg:col-span-2">
        @if($curso->categoria)
            <div class="text-xs text-marca font-medium mb-2">{{ $curso->categoria->nombre }}</div>
        @endif
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-3">{{ $curso->titulo }}</h1>
        <p class="text-slate-500 mb-4">{{ $curso->descripcion_corta }}</p>

        <div class="flex flex-wrap gap-5 text-sm text-slate-500 mb-6">
            <span><i class="ti ti-users"></i> {{ $curso->matriculas()->count() }} alumnos</span>
            <span><i class="ti ti-books"></i> {{ $curso->totalLecciones() }} lecciones</span>
            <span><i class="ti ti-award"></i> Nivel {{ ucfirst($curso->nivel) }}</span>
            @if($curso->certificado_habilitado)
                <span><i class="ti ti-certificate"></i> Con certificado</span>
            @endif
        </div>

        <div class="rounded-2xl overflow-hidden h-56 bg-gradient-to-br from-slate-900 to-marca flex items-center justify-center mb-10"
             @if($curso->imagen_portada) style="background-image:url({{ asset('storage/' . $curso->imagen_portada) }});background-size:cover;background-position:center" @endif>
            <div class="w-14 h-14 rounded-full bg-white/20 flex items-center justify-center">
                <i class="ti ti-player-play-filled text-white text-2xl"></i>
            </div>
        </div>

        @if($curso->descripcion_larga)
            <h2 class="font-bold text-slate-900 mb-3">Sobre este curso</h2>
            <div class="text-slate-600 mb-10 leading-relaxed">{!! nl2br(e($curso->descripcion_larga)) !!}</div>
        @endif

        <h2 class="font-bold text-slate-900 mb-3">Contenido del curso</h2>
        <div class="space-y-3">
            @foreach($curso->modulos as $modulo)
                <div class="border border-slate-100 rounded-xl overflow-hidden">
                    <div class="bg-slate-50 px-4 py-2.5 flex justify-between items-center text-sm font-semibold text-slate-800">
                        <span>{{ $modulo->titulo }}</span>
                        <span class="text-xs font-normal text-slate-400">{{ $modulo->lecciones->count() }} lecciones</span>
                    </div>
                    @foreach($modulo->lecciones as $leccion)
                        <div class="px-4 py-2.5 flex items-center gap-2 text-sm text-slate-600 border-t border-slate-50">
                            <i class="ti {{ $leccion->es_preview_gratis ? 'ti-player-play text-marca' : 'ti-lock text-slate-300' }}"></i>
                            <span class="flex-1">{{ $leccion->titulo }}</span>
                            @if($leccion->es_preview_gratis)
                                <span class="text-xs text-marca font-medium">Vista previa</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

    <div>
        <div class="sticky top-24 border border-slate-100 rounded-2xl p-6 shadow-sm">
            <div class="flex items-baseline gap-2 mb-1">
                <span class="text-2xl font-bold text-slate-900">
                    {{ $curso->moneda === 'USD' ? '$' : 'S/' }} {{ number_format($curso->precio_oferta ?? $curso->precio, 2) }}
                </span>
                @if($curso->precio_oferta)
                    <span class="text-sm text-slate-400 line-through">{{ $curso->moneda === 'USD' ? '$' : 'S/' }} {{ number_format($curso->precio, 2) }}</span>
                @endif
            </div>

            @if($yaMatriculado)
                <a href="{{ route('estudiante.curso.index', $curso) }}" class="block text-center bg-marca text-white font-semibold py-3 rounded-xl mt-4 hover:opacity-90">
                    Continuar aprendiendo
                </a>
            @else
                <a href="{{ route('publico.checkout', $curso) }}" class="block text-center bg-marca text-white font-semibold py-3 rounded-xl mt-4 hover:opacity-90">
                    Comprar curso
                </a>
            @endif

            <div class="mt-6 pt-5 border-t border-slate-100 space-y-3 text-sm text-slate-600">
                <div class="flex items-center gap-2"><i class="ti ti-infinity text-marca"></i> Acceso de por vida</div>
                <div class="flex items-center gap-2"><i class="ti ti-device-mobile text-marca"></i> Aprende desde el celular</div>
                @if($curso->certificado_habilitado)
                    <div class="flex items-center gap-2"><i class="ti ti-certificate text-marca"></i> Certificado al finalizar</div>
                @endif
            </div>
        </div>
    </div>

</section>
@endsection
