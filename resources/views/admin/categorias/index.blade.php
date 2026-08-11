@extends('layouts.admin')

@section('titulo', 'Categorías')

@section('contenido')

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-3">
        @forelse($categorias as $categoria)
            <div class="bg-white rounded-2xl border border-slate-100 p-4 flex items-center gap-3">
                <form method="POST" action="{{ route('admin.categorias.update', $categoria) }}" class="flex-1 flex items-center gap-3">
                    @csrf @method('PUT')
                    <div class="w-9 h-9 rounded-lg bg-marca/10 flex items-center justify-center flex-shrink-0">
                        <i class="ti {{ $categoria->icono ?: 'ti-books' }} text-marca"></i>
                    </div>
                    <input type="text" name="nombre" value="{{ $categoria->nombre }}"
                           class="flex-1 border border-transparent hover:border-slate-200 focus:border-marca rounded-lg px-2 py-1.5 text-sm font-medium text-slate-800 min-w-0">
                    <input type="text" name="icono" value="{{ $categoria->icono }}" placeholder="ti-books"
                           class="w-32 border border-transparent hover:border-slate-200 focus:border-marca rounded-lg px-2 py-1.5 text-xs text-slate-500">
                    <span class="text-xs text-slate-400 whitespace-nowrap">{{ $categoria->cursos_count }} cursos</span>
                    <button class="text-marca text-xs font-medium whitespace-nowrap">Guardar</button>
                </form>
                <form method="POST" action="{{ route('admin.categorias.destroy', $categoria) }}" onsubmit="return confirm('¿Eliminar esta categoría?')">
                    @csrf @method('DELETE')
                    <button class="text-red-400 text-xs px-2"><i class="ti ti-trash"></i></button>
                </form>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-slate-100 p-10 text-center text-slate-400">
                Todavía no tienes categorías creadas.
            </div>
        @endforelse
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 p-5 h-fit">
        <h3 class="font-semibold text-sm text-slate-900 mb-4">+ Nueva categoría</h3>
        <form method="POST" action="{{ route('admin.categorias.store') }}" class="space-y-3">
            @csrf
            <div>
                <label class="text-xs text-slate-500">Nombre</label>
                <input type="text" name="nombre" required placeholder="Ej: Marketing Digital" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1">
            </div>
            <div>
                <label class="text-xs text-slate-500">Icono (clase de Tabler Icons)</label>
                <input type="text" name="icono" placeholder="ti-speakerphone" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1">
                <p class="text-xs text-slate-400 mt-1">Ejemplos: ti-speakerphone, ti-chart-dots, ti-report-money, ti-code, ti-heart, ti-device-mobile. Búscalos en tabler.io/icons</p>
            </div>
            <button class="w-full bg-marca text-white text-sm font-semibold py-2 rounded-lg">Crear categoría</button>
        </form>
    </div>
</div>
@endsection
