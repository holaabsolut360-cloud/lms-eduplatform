@extends('layouts.admin')

@section('titulo', 'Dashboard')

@section('contenido')

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-slate-100 p-5">
        <div class="w-9 h-9 rounded-lg bg-marca/10 flex items-center justify-center mb-3">
            <i class="ti ti-cash text-marca"></i>
        </div>
        <div class="text-xl font-bold text-slate-900">S/ {{ number_format($ingresosMes, 2) }}</div>
        <div class="flex items-center justify-between mt-1">
            <div class="text-xs text-slate-500">Ingresos este mes</div>
            @if($crecimientoIngresos !== null)
                <span class="text-xs font-semibold flex items-center gap-0.5 {{ $crecimientoIngresos >= 0 ? 'text-green-600' : 'text-red-500' }}">
                    <i class="ti {{ $crecimientoIngresos >= 0 ? 'ti-arrow-up-right' : 'ti-arrow-down-right' }}"></i> {{ abs($crecimientoIngresos) }}%
                </span>
            @endif
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 p-5">
        <div class="w-9 h-9 rounded-lg bg-green-50 flex items-center justify-center mb-3">
            <i class="ti ti-user-plus text-green-600"></i>
        </div>
        <div class="text-xl font-bold text-slate-900">{{ $alumnosNuevosMes }}</div>
        <div class="flex items-center justify-between mt-1">
            <div class="text-xs text-slate-500">Alumnos nuevos este mes</div>
            @if($crecimientoAlumnos !== null)
                <span class="text-xs font-semibold flex items-center gap-0.5 {{ $crecimientoAlumnos >= 0 ? 'text-green-600' : 'text-red-500' }}">
                    <i class="ti {{ $crecimientoAlumnos >= 0 ? 'ti-arrow-up-right' : 'ti-arrow-down-right' }}"></i> {{ abs($crecimientoAlumnos) }}%
                </span>
            @endif
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 p-5">
        <div class="w-9 h-9 rounded-lg bg-amber-50 flex items-center justify-center mb-3">
            <i class="ti ti-clock text-amber-600"></i>
        </div>
        <div class="text-xl font-bold text-slate-900">{{ $ordenesPendientes }}</div>
        <div class="text-xs text-slate-500 mt-1">Pagos por revisar</div>
        @if($ordenesPendientes > 0)
            <a href="{{ route('admin.pagos.index') }}" class="text-xs text-marca font-medium mt-2 inline-block">Revisar ahora →</a>
        @endif
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 p-5">
        <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center mb-3">
            <i class="ti ti-users text-blue-600"></i>
        </div>
        <div class="text-xl font-bold text-slate-900">{{ $totalAlumnos }}</div>
        <div class="text-xs text-slate-500 mt-1">Alumnos totales · {{ $totalCursos }} cursos</div>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    @if($proximasClases->isNotEmpty())
    <div class="bg-white rounded-2xl border border-slate-100 p-5 lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-sm text-slate-900"><i class="ti ti-calendar-event text-red-500"></i> Próximas clases en vivo</h3>
            <a href="{{ route('admin.agenda.index') }}" class="text-xs text-marca font-medium">Ver agenda completa</a>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3">
            @foreach($proximasClases as $clase)
                <div class="border border-slate-100 rounded-xl p-3">
                    <div class="text-xs font-semibold text-red-500 mb-1">{{ $clase->fecha_hora->format('d/m H:i') }}</div>
                    <div class="text-sm text-slate-800 truncate">{{ $clase->titulo }}</div>
                    <div class="text-xs text-slate-400 truncate">{{ $clase->curso->titulo }}</div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-100 p-5">
        <h3 class="font-semibold text-sm text-slate-900 mb-4"><i class="ti ti-trophy"></i> Cursos más vendidos</h3>
        @forelse($cursosTop as $curso)
            <div class="flex items-center justify-between py-2 border-b border-slate-50 last:border-0">
                <a href="{{ route('admin.cursos.edit', $curso) }}" class="text-sm text-slate-700 hover:text-marca">{{ $curso->titulo }}</a>
                <span class="text-xs text-slate-400">{{ $curso->matriculas_count }} alumnos</span>
            </div>
        @empty
            <p class="text-sm text-slate-400 py-4">Todavía no hay matrículas registradas.</p>
        @endforelse
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-sm text-slate-900"><i class="ti ti-receipt"></i> Últimas órdenes</h3>
            <a href="{{ route('admin.pagos.index') }}" class="text-xs text-marca font-medium">Ver todas</a>
        </div>
        @forelse($ultimasOrdenes as $orden)
            <div class="flex items-center justify-between py-2 border-b border-slate-50 last:border-0">
                <div class="min-w-0">
                    <div class="text-sm text-slate-700 truncate">{{ $orden->estudiante->nombre }} — {{ $orden->curso->titulo }}</div>
                    <div class="text-xs text-slate-400">{{ $orden->created_at->diffForHumans() }}</div>
                </div>
                <span class="text-xs px-2 py-0.5 rounded-full flex-shrink-0
                    {{ $orden->estado === 'aprobada' ? 'bg-green-50 text-green-600' : ($orden->estado === 'rechazada' ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600') }}">
                    {{ ucfirst(str_replace('_',' ',$orden->estado)) }}
                </span>
            </div>
        @empty
            <p class="text-sm text-slate-400 py-4">Todavía no hay órdenes registradas.</p>
        @endforelse
    </div>
</div>

@endsection
