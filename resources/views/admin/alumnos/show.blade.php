@extends('layouts.admin')

@section('titulo', $alumno->nombre)

@section('contenido')

<a href="{{ route('admin.alumnos.index') }}" class="text-xs text-slate-500 mb-4 inline-block">← Volver a alumnos</a>

<div class="flex items-center gap-3 mb-6">
    <div class="w-12 h-12 rounded-full bg-marca flex items-center justify-center text-white font-semibold">
        {{ strtoupper(substr($alumno->nombre, 0, 1)) }}
    </div>
    <div>
        <div class="font-semibold text-slate-900">{{ $alumno->nombre }}</div>
        <div class="text-xs text-slate-500">{{ $alumno->email }} @if($alumno->telefono) · {{ $alumno->telefono }} @endif · Registrado el {{ $alumno->created_at->format('d/m/Y') }}</div>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">

        <div class="bg-white rounded-2xl border border-slate-100 p-5">
            <h3 class="font-semibold text-sm text-slate-900 mb-4"><i class="ti ti-books"></i> Cursos matriculados</h3>
            @forelse($matriculas as $matricula)
                <div class="flex items-center justify-between py-2 border-b border-slate-50 last:border-0">
                    <div class="min-w-0">
                        <div class="text-sm text-slate-800 truncate">{{ $matricula->curso->titulo }}</div>
                        <div class="text-xs text-slate-400">Matriculado {{ $matricula->matriculado_en?->format('d/m/Y') ?? '—' }}</div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <div class="w-24 h-2 bg-slate-100 rounded-full">
                            <div class="h-full bg-marca rounded-full" style="width: {{ $matricula->porcentaje_avance }}%"></div>
                        </div>
                        <span class="text-xs text-slate-500 w-8">{{ $matricula->porcentaje_avance }}%</span>
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-400">Sin cursos matriculados todavía.</p>
            @endforelse
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-5">
            <h3 class="font-semibold text-sm text-slate-900 mb-4"><i class="ti ti-certificate"></i> Certificados obtenidos</h3>
            @forelse($certificados as $certificado)
                <div class="flex items-center justify-between py-2 border-b border-slate-50 last:border-0">
                    <span class="text-sm text-slate-800">{{ $certificado->matricula->curso->titulo }}</span>
                    <span class="text-xs text-slate-400 font-mono">{{ $certificado->codigo_verificacion }}</span>
                </div>
            @empty
                <p class="text-sm text-slate-400">Todavía no ha obtenido ningún certificado.</p>
            @endforelse
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-5">
            <h3 class="font-semibold text-sm text-slate-900 mb-4"><i class="ti ti-receipt"></i> Historial de pagos</h3>
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
                <p class="text-sm text-slate-400">Sin pagos registrados.</p>
            @endforelse
        </div>

    </div>

    <div>
        <div class="bg-white rounded-2xl border border-slate-100 p-5">
            <h3 class="font-semibold text-sm text-slate-900 mb-4"><i class="ti ti-note"></i> Notas internas</h3>
            <p class="text-xs text-slate-400 mb-3">Solo visibles para el equipo administrativo. El alumno nunca las ve.</p>

            <form method="POST" action="{{ route('admin.alumnos.notas.store', $alumno) }}" class="mb-4">
                @csrf
                <textarea name="contenido" required rows="3" placeholder="Ej: Pagó en efectivo, pidió extensión de plazo..."
                          class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm"></textarea>
                <button class="w-full bg-slate-800 text-white text-xs font-semibold py-2 rounded-lg mt-2">Agregar nota</button>
            </form>

            <div class="space-y-3">
                @forelse($notas as $nota)
                    <div class="border-t border-slate-50 pt-3">
                        <p class="text-sm text-slate-700">{{ $nota->contenido }}</p>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-xs text-slate-400">{{ $nota->autor->nombre }} · {{ $nota->created_at->diffForHumans() }}</span>
                            <form method="POST" action="{{ route('admin.alumnos.notas.destroy', [$alumno, $nota]) }}" onsubmit="return confirm('¿Eliminar esta nota?')">
                                @csrf @method('DELETE')
                                <button class="text-red-300 text-xs"><i class="ti ti-trash"></i></button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-400">Sin notas todavía.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
