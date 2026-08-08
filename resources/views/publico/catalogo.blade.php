@extends('layouts.publico')

@section('titulo', 'Cursos')

@section('contenido')
<section class="max-w-6xl mx-auto px-6 py-14">

    <h1 class="text-2xl font-bold text-slate-900 mb-2">Nuestros cursos</h1>
    <p class="text-slate-500 mb-8">Aprende a tu propio ritmo, con certificado al finalizar.</p>

    <form method="GET" class="flex gap-2 mb-10 max-w-md">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Busca un curso..."
               class="flex-1 border border-slate-200 rounded-full px-4 py-2.5 text-sm focus:outline-none focus:border-marca">
        <button class="bg-marca text-white text-sm font-semibold px-6 rounded-full">Buscar</button>
    </form>

    @if($cursos->isEmpty())
        <div class="text-center text-slate-400 py-20">
            <i class="ti ti-mood-empty text-4xl mb-3 block"></i>
            No encontramos cursos que coincidan con tu búsqueda.
        </div>
    @else
        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($cursos as $curso)
                <a href="{{ route('publico.curso.detalle', $curso) }}" class="rounded-2xl overflow-hidden border border-slate-100 hover:shadow-lg transition group">
                    <div class="h-32 bg-gradient-to-br from-slate-800 to-marca flex items-center justify-center"
                         @if($curso->imagen_portada) style="background-image:url({{ asset('storage/' . $curso->imagen_portada) }});background-size:cover;background-position:center" @endif>
                        <i class="ti ti-player-play-filled text-white/90 text-2xl group-hover:scale-110 transition"></i>
                    </div>
                    <div class="p-4">
                        @if($curso->categoria)
                            <div class="text-xs text-marca font-medium mb-1">{{ $curso->categoria->nombre }}</div>
                        @endif
                        <div class="font-semibold text-slate-900 text-sm mb-1">{{ $curso->titulo }}</div>
                        <div class="text-xs text-slate-400 mb-3">{{ $curso->instructor->nombre ?? 'Instructor' }}</div>
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

        <div class="mt-10">
            {{ $cursos->links() }}
        </div>
    @endif

</section>
@endsection
