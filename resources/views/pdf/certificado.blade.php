<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0; }
        body {
            margin: 0;
            font-family: 'DejaVu Sans', sans-serif;
            color: #1f2937;
        }
        .marco {
            border: 14px solid #6c5ce7;
            padding: 40px 60px;
            height: 100%;
            box-sizing: border-box;
            text-align: center;
        }
        .marco-interno {
            border: 1px solid #c9c2ea;
            padding: 50px 40px;
        }
        .institucion {
            font-size: 13px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #6c5ce7;
            font-weight: bold;
            margin-bottom: 30px;
        }
        .titulo {
            font-size: 15px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 10px;
        }
        .otorgado-a {
            font-size: 12px;
            color: #9ca3af;
            margin-bottom: 6px;
        }
        .nombre-alumno {
            font-size: 36px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 20px;
            border-bottom: 1px solid #e5e7eb;
            display: inline-block;
            padding-bottom: 12px;
        }
        .texto-curso {
            font-size: 14px;
            color: #4b5563;
            margin-bottom: 4px;
        }
        .nombre-curso {
            font-size: 22px;
            font-weight: bold;
            color: #6c5ce7;
            margin-bottom: 40px;
        }
        .pie {
            font-size: 10px;
            color: #9ca3af;
            margin-top: 30px;
        }
        .codigo {
            font-family: monospace;
            font-size: 11px;
            color: #6b7280;
            margin-top: 4px;
        }
    </style>
</head>
<body>
    <div class="marco">
        <div class="marco-interno">
            <div class="institucion">{{ config('app.name', 'EduPlatform') }}</div>

            <div class="titulo">Certificado de Finalización</div>

            <p class="otorgado-a">Otorgado a</p>
            <div class="nombre-alumno">{{ $estudiante->nombre }}</div>

            <p class="texto-curso">por haber completado satisfactoriamente el curso</p>
            <div class="nombre-curso">{{ $curso->titulo }}</div>

            <p class="texto-curso">Emitido el {{ $certificado->emitido_en->locale('es')->translatedFormat('d \d\e F \d\e Y') }}</p>

            <div class="pie">
                Este certificado puede verificarse en {{ url('/verificar-certificado') }}
                <div class="codigo">Código: {{ $certificado->codigo_verificacion }}</div>
            </div>
        </div>
    </div>
</body>
</html>
