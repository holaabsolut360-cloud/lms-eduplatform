@extends('layouts.admin')

@section('titulo', 'Nuevo curso')

@section('contenido')
<form method="POST" action="{{ route('admin.cursos.store') }}" class="max-w-2xl bg-white rounded-2xl border border-slate-100 p-6 space-y-4">
    @csrf

    <div>
        <label class="text-sm font-medium text-slate-700">Categoría</label>
        <select name="categoria_id" class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm mt-1">
            <option value="">Sin categoría</option>
            @foreach($categorias as $categoria)
                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="text-sm font-medium text-slate-700">Título del curso</label>
        <input type="text" name="titulo" value="{{ old('titulo') }}" required
               class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm mt-1">
    </div>

    <div>
        <label class="text-sm font-medium text-slate-700">Descripción corta</label>
        <input type="text" name="descripcion_corta" value="{{ old('descripcion_corta') }}" maxlength="255"
               class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm mt-1">
    </div>

    <div>
        <label class="text-sm font-medium text-slate-700">Descripción completa</label>
        <textarea name="descripcion_larga" rows="4" class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm mt-1">{{ old('descripcion_larga') }}</textarea>
    </div>

    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="text-sm font-medium text-slate-700">Precio</label>
            <input type="number" step="0.01" name="precio" value="{{ old('precio', 0) }}" required
                   class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm mt-1">
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Precio oferta (opcional)</label>
            <input type="number" step="0.01" name="precio_oferta" value="{{ old('precio_oferta') }}"
                   class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm mt-1">
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Moneda</label>
            <select name="moneda" class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm mt-1">
                <option value="PEN">Soles (PEN)</option>
                <option value="USD">Dólares (USD)</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="text-sm font-medium text-slate-700">Nivel</label>
            <select name="nivel" class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm mt-1">
                <option value="basico">Básico</option>
                <option value="intermedio">Intermedio</option>
                <option value="avanzado">Avanzado</option>
            </select>
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Nota mínima para aprobar (%)</label>
            <input type="number" name="nota_minima_aprobacion" value="{{ old('nota_minima_aprobacion', 70) }}" min="0" max="100"
                   class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm mt-1">
        </div>
    </div>

    <div class="flex items-center gap-6 pt-2">
        <label class="flex items-center gap-2 text-sm text-slate-700">
            <input type="checkbox" name="bloqueo_secuencial" value="1" checked> Bloqueo secuencial por defecto
        </label>
        <label class="flex items-center gap-2 text-sm text-slate-700">
            <input type="checkbox" name="certificado_habilitado" value="1" checked> Emitir certificado
        </label>
    </div>

    <button class="bg-marca text-white font-semibold px-6 py-2.5 rounded-lg text-sm">Crear curso</button>
</form>
@endsection
