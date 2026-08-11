@extends('layouts.admin')

@section('titulo', 'Métodos de pago')

@section('contenido')

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-3">
        @forelse($metodos as $metodo)
            <div class="bg-white rounded-2xl border border-slate-100 p-4">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center text-white text-xs font-bold flex-shrink-0
                        {{ $metodo->tipo === 'yape' ? 'bg-purple-700' : ($metodo->tipo === 'plin' ? 'bg-teal-400' : 'bg-slate-700') }}">
                        @if($metodo->tipo === 'cuenta_bancaria') <i class="ti ti-building-bank"></i> @else {{ strtoupper(substr($metodo->tipo,0,1)) }} @endif
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-semibold text-slate-800">{{ ucfirst(str_replace('_',' ',$metodo->tipo)) }} · {{ $metodo->moneda }}</div>
                        <div class="text-xs text-slate-400">{{ $metodo->titular }}</div>
                    </div>
                    <span class="text-xs px-2.5 py-1 rounded-full {{ $metodo->activo ? 'bg-green-50 text-green-600' : 'bg-slate-100 text-slate-500' }}">
                        {{ $metodo->activo ? 'Activo' : 'Desactivado' }}
                    </span>
                    <form method="POST" action="{{ route('admin.metodos-pago.toggle', $metodo) }}">
                        @csrf
                        <button class="text-xs text-marca px-2">{{ $metodo->activo ? 'Desactivar' : 'Activar' }}</button>
                    </form>
                    <form method="POST" action="{{ route('admin.metodos-pago.destroy', $metodo) }}" onsubmit="return confirm('¿Eliminar este método de pago?')">
                        @csrf @method('DELETE')
                        <button class="text-red-400 text-xs px-2"><i class="ti ti-trash"></i></button>
                    </form>
                </div>

                <form method="POST" action="{{ route('admin.metodos-pago.update', $metodo) }}" enctype="multipart/form-data" class="grid grid-cols-2 gap-2">
                    @csrf @method('PUT')
                    <input type="hidden" name="tipo" value="{{ $metodo->tipo }}">
                    <input type="hidden" name="moneda" value="{{ $metodo->moneda }}">
                    <input type="text" name="titular" value="{{ $metodo->titular }}" placeholder="Titular" class="border border-slate-200 rounded-lg px-3 py-1.5 text-sm">
                    @if($metodo->tipo === 'cuenta_bancaria')
                        <input type="text" name="banco" value="{{ $metodo->banco }}" placeholder="Banco" class="border border-slate-200 rounded-lg px-3 py-1.5 text-sm">
                        <input type="text" name="numero_cuenta" value="{{ $metodo->numero_cuenta }}" placeholder="N° de cuenta" class="border border-slate-200 rounded-lg px-3 py-1.5 text-sm">
                        <input type="text" name="numero_cci" value="{{ $metodo->numero_cci }}" placeholder="CCI" class="border border-slate-200 rounded-lg px-3 py-1.5 text-sm">
                    @else
                        <input type="text" name="numero_celular" value="{{ $metodo->numero_celular }}" placeholder="Número de celular" class="border border-slate-200 rounded-lg px-3 py-1.5 text-sm">
                        <div class="flex items-center gap-2">
                            <input type="file" name="qr_imagen" accept="image/*" class="text-xs flex-1">
                            @if($metodo->qr_imagen_url)
                                <a href="{{ asset('storage/' . $metodo->qr_imagen_url) }}" target="_blank" class="text-xs text-marca whitespace-nowrap">Ver QR</a>
                            @endif
                        </div>
                    @endif
                    <button class="col-span-2 bg-slate-800 text-white text-xs font-semibold py-2 rounded-lg mt-1">Guardar cambios</button>
                </form>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-slate-100 p-10 text-center text-slate-400">
                Todavía no tienes métodos de pago configurados. El checkout no funcionará hasta que agregues al menos uno.
            </div>
        @endforelse
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 p-5 h-fit">
        <h3 class="font-semibold text-sm text-slate-900 mb-4">+ Nuevo método de pago</h3>
        <form method="POST" action="{{ route('admin.metodos-pago.store') }}" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <div>
                <label class="text-xs text-slate-500">Tipo</label>
                <select name="tipo" id="tipo-nuevo" onchange="document.getElementById('bloque-celular-nuevo').style.display = this.value === 'cuenta_bancaria' ? 'none' : 'block'; document.getElementById('bloque-banco-nuevo').style.display = this.value === 'cuenta_bancaria' ? 'block' : 'none';"
                        class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1">
                    <option value="yape">Yape</option>
                    <option value="plin">Plin</option>
                    <option value="cuenta_bancaria">Cuenta bancaria</option>
                </select>
            </div>
            <div>
                <label class="text-xs text-slate-500">Moneda</label>
                <select name="moneda" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1">
                    <option value="PEN">Soles (PEN)</option>
                    <option value="USD">Dólares (USD)</option>
                </select>
            </div>
            <div>
                <label class="text-xs text-slate-500">Titular</label>
                <input type="text" name="titular" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1">
            </div>

            <div id="bloque-celular-nuevo">
                <label class="text-xs text-slate-500">Número de celular</label>
                <input type="text" name="numero_celular" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1">
                <label class="text-xs text-slate-500 mt-2 block">Imagen del QR (opcional)</label>
                <input type="file" name="qr_imagen" accept="image/*" class="w-full text-sm mt-1">
            </div>

            <div id="bloque-banco-nuevo" style="display:none">
                <label class="text-xs text-slate-500">Banco</label>
                <input type="text" name="banco" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1">
                <label class="text-xs text-slate-500 mt-2 block">Número de cuenta</label>
                <input type="text" name="numero_cuenta" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1">
                <label class="text-xs text-slate-500 mt-2 block">CCI</label>
                <input type="text" name="numero_cci" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1">
            </div>

            <button class="w-full bg-marca text-white text-sm font-semibold py-2 rounded-lg mt-2">Crear método de pago</button>
        </form>
    </div>
</div>
@endsection
