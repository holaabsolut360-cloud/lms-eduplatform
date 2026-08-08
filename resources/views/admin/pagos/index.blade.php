@extends('layouts.admin')

@section('titulo', 'Pagos')

@section('contenido')

<div class="flex gap-2 mb-5 text-xs">
    <a href="{{ route('admin.pagos.index') }}" class="px-3 py-1.5 rounded-full {{ !request('estado') ? 'bg-marca text-white' : 'bg-white border border-slate-200 text-slate-500' }}">Todas</a>
    <a href="{{ route('admin.pagos.index', ['estado' => 'en_revision']) }}" class="px-3 py-1.5 rounded-full {{ request('estado') === 'en_revision' ? 'bg-marca text-white' : 'bg-white border border-slate-200 text-slate-500' }}">En revisión</a>
    <a href="{{ route('admin.pagos.index', ['estado' => 'aprobada']) }}" class="px-3 py-1.5 rounded-full {{ request('estado') === 'aprobada' ? 'bg-marca text-white' : 'bg-white border border-slate-200 text-slate-500' }}">Aprobadas</a>
    <a href="{{ route('admin.pagos.index', ['estado' => 'rechazada']) }}" class="px-3 py-1.5 rounded-full {{ request('estado') === 'rechazada' ? 'bg-marca text-white' : 'bg-white border border-slate-200 text-slate-500' }}">Rechazadas</a>
</div>

<div class="space-y-3">
    @forelse($ordenes as $orden)
        <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center gap-4">
            <div class="flex-1">
                <div class="text-sm font-medium text-slate-800">{{ $orden->estudiante->nombre }} — {{ $orden->curso->titulo }}</div>
                <div class="text-xs text-slate-400">{{ $orden->codigo }} · {{ $orden->metodoPago->tipo ?? '—' }} · {{ $orden->moneda }} {{ number_format($orden->monto, 2) }} · {{ $orden->created_at->format('d/m/Y H:i') }}</div>
            </div>

            @if($orden->comprobante_url)
                <a href="{{ asset('storage/' . $orden->comprobante_url) }}" target="_blank" class="text-xs text-marca">Ver comprobante</a>
            @endif

            <span class="text-xs px-2.5 py-1 rounded-full
                {{ $orden->estado === 'aprobada' ? 'bg-green-50 text-green-600' : ($orden->estado === 'rechazada' ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600') }}">
                {{ ucfirst(str_replace('_',' ',$orden->estado)) }}
            </span>

            @if($orden->estado === 'en_revision' || $orden->estado === 'pendiente')
                <form method="POST" action="{{ route('admin.pagos.aprobar', $orden) }}">
                    @csrf
                    <button class="bg-green-600 text-white text-xs font-semibold px-3 py-2 rounded-lg">Aprobar</button>
                </form>
                <button type="button" onclick="document.getElementById('rechazo-{{ $orden->id }}').showModal()" class="bg-red-50 text-red-600 text-xs font-semibold px-3 py-2 rounded-lg">Rechazar</button>

                <dialog id="rechazo-{{ $orden->id }}" class="rounded-2xl p-6 max-w-sm w-full">
                    <form method="POST" action="{{ route('admin.pagos.rechazar', $orden) }}">
                        @csrf
                        <h3 class="font-semibold text-sm mb-3">Motivo del rechazo</h3>
                        <textarea name="motivo_rechazo" required rows="3" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mb-3"></textarea>
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="document.getElementById('rechazo-{{ $orden->id }}').close()" class="text-sm text-slate-500">Cancelar</button>
                            <button class="bg-red-600 text-white text-sm font-semibold px-4 py-2 rounded-lg">Rechazar orden</button>
                        </div>
                    </form>
                </dialog>
            @endif
        </div>
    @empty
        <div class="text-center text-slate-400 py-16">No hay órdenes en este filtro.</div>
    @endforelse
</div>

<div class="mt-6">{{ $ordenes->links() }}</div>
@endsection
