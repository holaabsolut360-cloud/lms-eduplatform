@extends('layouts.admin')

@section('titulo', 'Apariencia de mi web')

@section('contenido')

<form method="POST" action="{{ route('admin.apariencia.actualizar') }}" enctype="multipart/form-data" class="max-w-2xl space-y-5">
    @csrf @method('PUT')

    <div class="bg-white rounded-2xl border border-slate-100 p-5">
        <h3 class="font-semibold text-sm text-slate-900 mb-4"><i class="ti ti-photo"></i> Sección Hero (portada)</h3>
        <div class="grid grid-cols-2 gap-4 mb-3">
            <div>
                <label class="text-xs text-slate-500">Título principal</label>
                <input type="text" name="hero_titulo" value="{{ $apariencia->hero_titulo }}" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1">
            </div>
            <div>
                <label class="text-xs text-slate-500">Texto del botón</label>
                <input type="text" name="hero_texto_boton" value="{{ $apariencia->hero_texto_boton }}" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1">
            </div>
        </div>
        <div class="mb-3">
            <label class="text-xs text-slate-500">Subtítulo</label>
            <input type="text" name="hero_subtitulo" value="{{ $apariencia->hero_subtitulo }}" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1">
        </div>
        <div>
            <label class="text-xs text-slate-500">Imagen de fondo</label>
            <input type="file" name="hero_imagen_fondo" accept="image/*" class="text-sm mt-1">
            @if($apariencia->hero_imagen_fondo)
                <div class="text-xs text-slate-400 mt-1">Imagen actual configurada.</div>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 p-5">
        <h3 class="font-semibold text-sm text-slate-900 mb-4"><i class="ti ti-color-swatch"></i> Color de marca</h3>
        <input type="color" name="color_marca" value="{{ $apariencia->color_marca }}" class="w-16 h-10 border border-slate-200 rounded-lg">
        <p class="text-xs text-slate-400 mt-2">Se aplica a botones, badges y acentos de toda la web.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 p-5">
        <h3 class="font-semibold text-sm text-slate-900 mb-4"><i class="ti ti-stack-2"></i> Cursos destacados en el home</h3>
        <div class="space-y-2">
            @foreach($cursosPublicados as $curso)
                <label class="flex items-center gap-2 text-sm border border-slate-200 rounded-lg px-3 py-2">
                    <input type="checkbox" name="cursos_destacados_ids[]" value="{{ $curso->id }}"
                           @checked(in_array($curso->id, $apariencia->cursos_destacados_ids ?? []))>
                    {{ $curso->titulo }}
                </label>
            @endforeach
            @if($cursosPublicados->isEmpty())
                <p class="text-xs text-slate-400">Todavía no tienes cursos publicados para destacar.</p>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 p-5">
        <h3 class="font-semibold text-sm text-slate-900 mb-4"><i class="ti ti-info-circle"></i> Sección "Nosotros" y cifras</h3>
        <div class="grid grid-cols-3 gap-3 mb-3">
            <div>
                <label class="text-xs text-slate-500">Estudiantes</label>
                <input type="text" name="cifra_estudiantes" value="{{ $apariencia->cifra_estudiantes }}" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1">
            </div>
            <div>
                <label class="text-xs text-slate-500">Empresas</label>
                <input type="text" name="cifra_empresas" value="{{ $apariencia->cifra_empresas }}" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1">
            </div>
            <div>
                <label class="text-xs text-slate-500">Rating</label>
                <input type="text" name="cifra_rating" value="{{ $apariencia->cifra_rating }}" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1">
            </div>
        </div>
        <textarea name="nosotros_texto" rows="3" placeholder="Texto de la sección Nosotros" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">{{ $apariencia->nosotros_texto }}</textarea>
    </div>

    <button class="bg-marca text-white font-semibold px-6 py-2.5 rounded-lg text-sm">Guardar cambios</button>
</form>
@endsection
