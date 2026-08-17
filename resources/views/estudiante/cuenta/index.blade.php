@extends('layouts.publico')

@section('titulo', 'Mi cuenta')

@section('contenido')
<section class="max-w-4xl mx-auto px-6 py-12">

    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-3">
            @if(auth()->user()->avatar_url)
                <img src="{{ asset('storage/' . auth()->user()->avatar_url) }}" class="w-12 h-12 rounded-full object-cover">
            @else
                <div class="w-12 h-12 rounded-full bg-marca flex items-center justify-center text-white font-semibold">
                    {{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}
                </div>
            @endif
            <div>
                <h1 class="text-xl font-bold text-slate-900">Hola, {{ auth()->user()->nombre }}</h1>
                <p class="text-sm text-slate-500">Aquí ves tus cursos, pagos, calificaciones y certificados.</p>
            </div>
        </div>
        <a href="{{ route('estudiante.cuenta.perfil') }}" class="text-sm text-marca font-medium border border-marca/30 rounded-full px-4 py-2 flex items-center gap-1.5">
            <i class="ti ti-settings"></i> Editar perfil
        </a>
    </div>

    <div class="grid sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white border border-slate-100 rounded-2xl p-4">
            <div class="text-xl font-bold text-slate-900">{{ $matriculas->count() }}</div>
            <div class="text-xs text-slate-500">Cursos activos</div>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-4">
            <div class="text-xl font-bold text-slate-900">{{ $certificados->count() }}</div>
            <div class="text-xs text-slate-500">Certificados obtenidos</div>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-4">
            <div class="text-xl font-bold text-slate-900">{{ $ordenes->where('estado', 'en_revision')->count() + $ordenes->where('estado', 'pendiente')->count() }}</div>
            <div class="text-xs text-slate-500">Pagos en revisión</div>
        </div>
    </div>

    <div class="space-y-6">

        <div class="bg-white border border-slate-100 rounded-2xl p-5">
            <h3 class="font-semibold text-sm text-slate-900 mb-4"><i class="ti ti-books"></i> Mis cursos</h3>
            @forelse($matriculas as $matricula)
                <a href="{{ route('estudiante.curso.index', $matricula->curso) }}" class="flex items-center justify-between py-2.5 border-b border-slate-50 last:border-0 hover:opacity-80">
                    <div class="min-w-0">
                        <div class="text-sm text-slate-800 truncate">{{ $matricula->curso->titulo }}</div>
                        <div class="text-xs text-slate-400">{{ ucfirst($matricula->estado) }}</div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <div class="w-24 h-2 bg-slate-100 rounded-full">
                            <div class="h-full bg-marca rounded-full" style="width: {{ $matricula->porcentaje_avance }}%"></div>
                        </div>
                        <span class="text-xs text-slate-500 w-8">{{ $matricula->porcentaje_avance }}%</span>
                    </div>
                </a>
            @empty
                <p class="text-sm text-slate-400">Todavía no tienes cursos. <a href="{{ route('publico.catalogo') }}" class="text-marca">Explora el catálogo →</a></p>
            @endforelse
        </div>

        <div class="bg-white border border-slate-100 rounded-2xl p-5">
            <h3 class="font-semibold text-sm text-slate-900 mb-4"><i class="ti ti-certificate"></i> Mis certificados</h3>
            @forelse($certificados as $certificado)
                <div class="flex items-center justify-between py-2.5 border-b border-slate-50 last:border-0">
                    <div class="min-w-0">
                        <div class="text-sm text-slate-800 truncate">{{ $certificado->matricula->curso->titulo }}</div>
                        <div class="text-xs text-slate-400 font-mono">{{ $certificado->codigo_verificacion }}</div>
                    </div>
                    <a href="{{ route('estudiante.certificado.mostrar', $certificado->matricula->curso) }}" class="text-marca text-xs font-medium flex-shrink-0">Ver →</a>
                </div>
            @empty
                <p class="text-sm text-slate-400">Todavía no has obtenido ningún certificado. Completa un curso al 100% para desbloquearlo.</p>
            @endforelse
        </div>

        <div class="bg-white border border-slate-100 rounded-2xl p-5">
            <h3 class="font-semibold text-sm text-slate-900 mb-4"><i class="ti ti-clipboard-check"></i> Mis calificaciones</h3>
            @forelse($resultadosExamenes as $intento)
                <div class="flex items-center justify-between py-2 border-b border-slate-50 last:border-0">
                    <div class="min-w-0">
                        <div class="text-sm text-slate-800 truncate">{{ $intento->examen->titulo }}</div>
                        <div class="text-xs text-slate-400 truncate">{{ $intento->examen->curso->titulo }} · Intento {{ $intento->numero_intento }}</div>
                    </div>
                    <span class="text-xs px-2.5 py-1 rounded-full flex-shrink-0 {{ $intento->aprobado ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                        {{ $intento->nota_obtenida }}/100
                    </span>
                </div>
            @empty
            @endforelse
            @forelse($entregasCalificadas as $entrega)
                <div class="flex items-center justify-between py-2 border-b border-slate-50 last:border-0">
                    <div class="min-w-0">
                        <div class="text-sm text-slate-800 truncate">{{ $entrega->tarea->titulo }}</div>
                        <div class="text-xs text-slate-400 truncate">{{ $entrega->tarea->curso->titulo }} · Tarea</div>
                    </div>
                    <span class="text-xs px-2.5 py-1 rounded-full flex-shrink-0 bg-blue-50 text-blue-600">
                        {{ $entrega->nota }}/{{ $entrega->tarea->puntaje_maximo }}
                    </span>
                </div>
            @empty
            @endforelse
            @if($resultadosExamenes->isEmpty() && $entregasCalificadas->isEmpty())
                <p class="text-sm text-slate-400">Todavía no tienes exámenes ni tareas calificadas.</p>
            @endif
        </div>

        <div class="bg-white border border-slate-100 rounded-2xl p-5">
            <h3 class="font-semibold text-sm text-slate-900 mb-4"><i class="ti ti-receipt"></i> Mis pagos</h3>
            @forelse($ordenes as $orden)
                <div class="flex items-center justify-between py-2 border-b border-slate-50 last:border-0">
                    <div class="min-w-0">
                        <div class="text-sm text-slate-800 truncate">{{ $orden->curso->titulo }}</div>
                        <div class="text-xs text-slate-400">{{ $orden->codigo }} · {{ $orden->created_at->format('d/m/Y') }}</div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <span class="text-sm text-slate-700">{{ $orden->moneda }} {{ number_format($orden->monto, 2) }}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full
                            {{ $orden->estado === 'aprobada' ? 'bg-green-50 text-green-600' : ($orden->estado === 'rechazada' ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600') }}">
                            {{ ucfirst(str_replace('_',' ',$orden->estado)) }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-400">Todavía no has realizado ningún pago.</p>
            @endforelse
        </div>

    </div>
</section>
@endsection
