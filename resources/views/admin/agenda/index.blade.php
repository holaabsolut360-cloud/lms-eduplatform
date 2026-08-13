@extends('layouts.admin')

@section('titulo', 'Agenda de clases en vivo')

@section('contenido')

<p class="text-sm text-slate-500 mb-6">
    @if($esAdmin)
        Todas las clases en vivo agendadas por todos los instructores de la plataforma.
    @else
        Tus clases en vivo agendadas, en todos tus cursos.
    @endif
</p>

<div class="grid lg:grid-cols-2 gap-6">

    <div>
        <h3 class="font-semibold text-sm text-slate-900 mb-3 flex items-center gap-2"><i class="ti ti-calendar-event text-marca"></i> Próximas</h3>
        <div class="space-y-4">
            @forelse($proximas as $fecha => $clasesDelDia)
                <div>
                    <div class="text-xs font-semibold text-slate-500 uppercase mb-2">{{ \Carbon\Carbon::parse($fecha)->locale('es')->translatedFormat('l d \d\e F') }}</div>
                    <div class="space-y-2">
                        @foreach($clasesDelDia as $clase)
                            <div class="bg-white rounded-2xl border border-slate-100 p-4 flex items-center gap-3">
                                <div class="w-11 h-11 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0 text-red-500 font-bold text-xs text-center leading-tight">
                                    {{ $clase->fecha_hora->format('H:i') }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-slate-800 truncate">{{ $clase->titulo }}</div>
                                    <div class="text-xs text-slate-400 truncate">
                                        {{ $clase->curso->titulo }}
                                        @if($esAdmin) · {{ $clase->curso->instructor->nombre }} @endif
                                        · {{ ucfirst(str_replace('_',' ',$clase->plataforma)) }}
                                    </div>
                                </div>
                                @if($clase->estaEnVivoAhora())
                                    <span class="bg-red-500 text-white text-[10px] font-semibold px-2 py-1 rounded-full flex-shrink-0">EN VIVO</span>
                                @endif
                                <a href="{{ $clase->link_reunion }}" target="_blank" class="text-marca text-xs font-medium flex-shrink-0">Unirse</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-slate-100 p-8 text-center text-slate-400 text-sm">
                    No hay clases en vivo agendadas próximamente.
                </div>
            @endforelse
        </div>
    </div>

    <div>
        <h3 class="font-semibold text-sm text-slate-900 mb-3 flex items-center gap-2"><i class="ti ti-history text-slate-400"></i> Realizadas recientemente</h3>
        <div class="space-y-4">
            @forelse($pasadas as $fecha => $clasesDelDia)
                <div>
                    <div class="text-xs font-semibold text-slate-400 uppercase mb-2">{{ \Carbon\Carbon::parse($fecha)->locale('es')->translatedFormat('l d \d\e F') }}</div>
                    <div class="space-y-2">
                        @foreach($clasesDelDia as $clase)
                            <div class="bg-white rounded-2xl border border-slate-100 p-4 flex items-center gap-3 opacity-70">
                                <div class="w-11 h-11 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0 text-slate-500 font-bold text-xs">
                                    {{ $clase->fecha_hora->format('H:i') }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-slate-700 truncate">{{ $clase->titulo }}</div>
                                    <div class="text-xs text-slate-400 truncate">
                                        {{ $clase->curso->titulo }}
                                        @if($esAdmin) · {{ $clase->curso->instructor->nombre }} @endif
                                    </div>
                                </div>
                                <span class="text-xs text-slate-400 flex-shrink-0">Finalizada</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-slate-100 p-8 text-center text-slate-400 text-sm">
                    Todavía no se ha realizado ninguna clase.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
