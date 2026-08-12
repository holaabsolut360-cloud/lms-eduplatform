@extends('layouts.admin')

@section('titulo', $curso->titulo)

@section('contenido')

<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="font-semibold text-slate-900">{{ $curso->titulo }}</h2>
        <span class="text-xs px-2 py-0.5 rounded-full {{ $curso->estado === 'publicado' ? 'bg-green-50 text-green-600' : 'bg-amber-50 text-amber-600' }}">
            {{ ucfirst($curso->estado) }}
        </span>
    </div>
    @if($curso->estado !== 'publicado')
        <form method="POST" action="{{ route('admin.cursos.publicar', $curso) }}">
            @csrf
            <button class="bg-marca text-white text-sm font-semibold px-4 py-2 rounded-lg">Publicar curso</button>
        </form>
    @endif
</div>

<div class="grid lg:grid-cols-3 gap-6">

    {{-- CONTENIDO: MODULOS Y LECCIONES --}}
    <div class="lg:col-span-2 space-y-4">

        <div class="bg-white rounded-2xl border border-slate-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-sm text-slate-900"><i class="ti ti-stack-2"></i> Contenido del curso</h3>
            </div>

            <form method="POST" action="{{ route('admin.modulos.store', $curso) }}" class="flex gap-2 mb-5">
                @csrf
                <input type="text" name="titulo" placeholder="Título del nuevo módulo" required
                       class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm">
                <button class="bg-marca text-white text-xs font-semibold px-4 rounded-lg">+ Módulo</button>
            </form>

            @foreach($curso->modulos as $modulo)
                <div class="border border-slate-100 rounded-xl mb-3 overflow-hidden">
                    <div class="bg-slate-50 px-4 py-2.5 flex items-center justify-between text-sm font-semibold text-slate-800">
                        <span>{{ $modulo->titulo }}</span>
                        <form method="POST" action="{{ route('admin.modulos.destroy', [$curso, $modulo]) }}" onsubmit="return confirm('¿Eliminar este módulo y todo su contenido?')">
                            @csrf @method('DELETE')
                            <button class="text-red-400 text-xs"><i class="ti ti-trash"></i></button>
                        </form>
                    </div>

                    @foreach($modulo->lecciones as $leccion)
                        <div class="px-4 py-2 flex items-center gap-2 text-sm text-slate-600 border-t border-slate-50">
                            <i class="ti {{ $leccion->tipo === 'video' ? 'ti-player-play' : ($leccion->tipo === 'texto' ? 'ti-file-text' : 'ti-file') }} text-marca"></i>
                            <span class="flex-1">{{ $leccion->titulo }}</span>
                            <span class="text-xs text-slate-400">{{ $leccion->duracion_minutos }}m</span>
                            <form method="POST" action="{{ route('admin.lecciones.destroy', [$modulo, $leccion]) }}" onsubmit="return confirm('¿Eliminar esta lección?')">
                                @csrf @method('DELETE')
                                <button class="text-red-300 text-xs ml-2"><i class="ti ti-x"></i></button>
                            </form>
                        </div>
                    @endforeach

                    <details class="border-t border-slate-50">
                        <summary class="px-4 py-2 text-xs text-marca cursor-pointer select-none">+ Agregar lección</summary>
                        <form method="POST" action="{{ route('admin.lecciones.store', $modulo) }}" class="px-4 pb-4 space-y-2">
                            @csrf
                            <input type="text" name="titulo" placeholder="Título de la lección" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
                            <div class="flex gap-2">
                                <select name="tipo" class="border border-slate-200 rounded-lg px-3 py-2 text-sm">
                                    <option value="video">Video (YouTube)</option>
                                    <option value="texto">Texto</option>
                                    <option value="pdf">PDF</option>
                                    <option value="archivo">Archivo</option>
                                </select>
                                <input type="number" name="duracion_minutos" placeholder="Min." class="w-20 border border-slate-200 rounded-lg px-3 py-2 text-sm">
                                <label class="flex items-center gap-1 text-xs text-slate-500 whitespace-nowrap">
                                    <input type="checkbox" name="es_preview_gratis" value="1"> Preview gratis
                                </label>
                            </div>
                            <input type="url" name="video_youtube_url" placeholder="URL de YouTube (si es tipo video)" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
                            <textarea name="contenido_html" placeholder="Contenido de texto (si es tipo texto)" rows="2" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm"></textarea>
                            <button class="bg-slate-800 text-white text-xs font-semibold px-4 py-2 rounded-lg">Agregar lección</button>
                        </form>
                    </details>
                </div>
            @endforeach
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-5">
            <h3 class="font-semibold text-sm text-slate-900 mb-4"><i class="ti ti-clipboard-list"></i> Exámenes</h3>
            @foreach($curso->examenes as $examen)
                <div class="flex items-center justify-between border-t border-slate-50 py-2 text-sm">
                    <span>{{ $examen->titulo }}</span>
                    <a href="{{ route('admin.examenes.edit', [$curso, $examen]) }}" class="text-marca text-xs">Editar preguntas</a>
                </div>
            @endforeach
            <details class="mt-3">
                <summary class="text-xs text-marca cursor-pointer select-none">+ Crear examen</summary>
                <form method="POST" action="{{ route('admin.examenes.store', $curso) }}" class="space-y-2 mt-3">
                    @csrf
                    <input type="text" name="titulo" placeholder="Título del examen" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" name="intentos_permitidos" value="1" min="1" placeholder="Intentos" class="border border-slate-200 rounded-lg px-3 py-2 text-sm">
                        <input type="number" name="nota_minima_aprobacion" value="70" min="0" max="100" placeholder="Nota mínima" class="border border-slate-200 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <button class="bg-slate-800 text-white text-xs font-semibold px-4 py-2 rounded-lg">Crear examen</button>
                </form>
            </details>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-5">
            <h3 class="font-semibold text-sm text-slate-900 mb-4"><i class="ti ti-file-text"></i> Tareas</h3>
            @foreach($curso->tareas as $tarea)
                <div class="flex items-center justify-between border-t border-slate-50 py-2 text-sm">
                    <span>{{ $tarea->titulo }}</span>
                    <a href="{{ route('admin.tareas.entregas', [$curso, $tarea]) }}" class="text-marca text-xs">Ver entregas</a>
                </div>
            @endforeach
            <details class="mt-3">
                <summary class="text-xs text-marca cursor-pointer select-none">+ Crear tarea</summary>
                <form method="POST" action="{{ route('admin.tareas.store', $curso) }}" class="space-y-2 mt-3">
                    @csrf
                    <input type="text" name="titulo" placeholder="Título de la tarea" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
                    <textarea name="instrucciones" placeholder="Instrucciones" rows="2" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm"></textarea>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="datetime-local" name="fecha_limite" class="border border-slate-200 rounded-lg px-3 py-2 text-sm">
                        <input type="number" name="puntaje_maximo" value="100" placeholder="Puntaje máx." class="border border-slate-200 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <button class="bg-slate-800 text-white text-xs font-semibold px-4 py-2 rounded-lg">Crear tarea</button>
                </form>
            </details>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-5">
            <h3 class="font-semibold text-sm text-slate-900 mb-4"><i class="ti ti-video"></i> Clases en vivo</h3>
            @forelse($curso->clasesEnVivo as $clase)
                <div class="flex items-center justify-between border-t border-slate-50 py-2 text-sm">
                    <div>
                        <div class="text-slate-800">{{ $clase->titulo }}</div>
                        <div class="text-xs text-slate-400">{{ $clase->fecha_hora->format('d/m/Y H:i') }} · {{ ucfirst(str_replace('_',' ',$clase->plataforma)) }}
                            @if($clase->yaPaso()) <span class="text-slate-400">· Finalizada</span> @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ $clase->link_reunion }}" target="_blank" class="text-marca text-xs">Ver link</a>
                        <form method="POST" action="{{ route('admin.clases-vivo.destroy', [$curso, $clase]) }}" onsubmit="return confirm('¿Eliminar esta clase en vivo?')">
                            @csrf @method('DELETE')
                            <button class="text-red-300 text-xs"><i class="ti ti-x"></i></button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400">Todavía no has agendado ninguna clase en vivo.</p>
            @endforelse
            <details class="mt-3">
                <summary class="text-xs text-marca cursor-pointer select-none">+ Agendar clase en vivo</summary>
                <form method="POST" action="{{ route('admin.clases-vivo.store', $curso) }}" class="space-y-2 mt-3">
                    @csrf
                    <input type="text" name="titulo" placeholder="Título de la clase (ej: Sesión en vivo — Módulo 1)" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
                    <div class="grid grid-cols-2 gap-2">
                        <select name="plataforma" class="border border-slate-200 rounded-lg px-3 py-2 text-sm">
                            <option value="zoom">Zoom</option>
                            <option value="google_meet">Google Meet</option>
                            <option value="otro">Otro</option>
                        </select>
                        <input type="number" name="duracion_minutos" value="60" placeholder="Duración (min)" class="border border-slate-200 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <input type="url" name="link_reunion" placeholder="Link de la reunión (Zoom/Meet)" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
                    <input type="datetime-local" name="fecha_hora" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
                    <textarea name="notas" placeholder="Notas para el alumno (opcional)" rows="2" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm"></textarea>
                    <button class="bg-slate-800 text-white text-xs font-semibold px-4 py-2 rounded-lg">Agendar clase</button>
                </form>
            </details>
        </div>
    </div>

    <div class="space-y-4">
        <form method="POST" action="{{ route('admin.cursos.update', $curso) }}" class="bg-white rounded-2xl border border-slate-100 p-5 space-y-3">
            @csrf @method('PUT')
            <h3 class="font-semibold text-sm text-slate-900 mb-1"><i class="ti ti-settings"></i> Detalles del curso</h3>

            <div>
                <label class="text-xs text-slate-500">Título</label>
                <input type="text" name="titulo" value="{{ $curso->titulo }}" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1">
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="text-xs text-slate-500">Precio</label>
                    <input type="number" step="0.01" name="precio" value="{{ $curso->precio }}" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1">
                </div>
                <div>
                    <label class="text-xs text-slate-500">Moneda</label>
                    <select name="moneda" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1">
                        <option value="PEN" @selected($curso->moneda === 'PEN')>PEN</option>
                        <option value="USD" @selected($curso->moneda === 'USD')>USD</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="text-xs text-slate-500">Categoría</label>
                <select name="categoria_id" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1">
                    <option value="">Sin categoría</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" @selected($curso->categoria_id === $categoria->id)>{{ $categoria->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs text-slate-500">Nivel</label>
                <select name="nivel" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mt-1">
                    <option value="basico" @selected($curso->nivel === 'basico')>Básico</option>
                    <option value="intermedio" @selected($curso->nivel === 'intermedio')>Intermedio</option>
                    <option value="avanzado" @selected($curso->nivel === 'avanzado')>Avanzado</option>
                </select>
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-700">
                <input type="checkbox" name="bloqueo_secuencial" value="1" @checked($curso->bloqueo_secuencial)> Bloqueo secuencial
            </label>
            <label class="flex items-center gap-2 text-sm text-slate-700">
                <input type="checkbox" name="certificado_habilitado" value="1" @checked($curso->certificado_habilitado)> Certificado habilitado
            </label>

            <button class="w-full bg-slate-800 text-white text-sm font-semibold py-2 rounded-lg">Guardar cambios</button>
        </form>

        <div class="bg-white rounded-2xl border border-slate-100 p-5">
            <h3 class="font-semibold text-sm text-slate-900 mb-3"><i class="ti ti-chart-bar"></i> Este curso</h3>
            <div class="flex justify-between text-sm py-1.5 border-b border-slate-50">
                <span class="text-slate-500">Alumnos inscritos</span>
                <a href="{{ route('admin.cursos.alumnos', $curso) }}" class="text-marca font-medium">{{ $curso->matriculas()->count() }} · Ver progreso →</a>
            </div>
            <div class="flex justify-between text-sm py-1.5"><span class="text-slate-500">Lecciones totales</span><span>{{ $curso->totalLecciones() }}</span></div>
        </div>
    </div>

</div>
@endsection
