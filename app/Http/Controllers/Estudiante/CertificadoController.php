<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use App\Models\Certificado;
use App\Models\Curso;
use App\Models\Matricula;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CertificadoController extends Controller
{
    public function mostrar(Curso $curso): View
    {
        $certificado = $this->certificadoDelUsuario($curso);

        return view('estudiante.certificado', [
            'curso' => $curso,
            'certificado' => $certificado,
            'estudiante' => auth()->user(),
        ]);
    }

    public function descargar(Curso $curso): Response
    {
        $certificado = $this->certificadoDelUsuario($curso);

        $pdf = Pdf::loadView('pdf.certificado', [
            'curso' => $curso,
            'certificado' => $certificado,
            'estudiante' => auth()->user(),
        ])->setPaper('a4', 'landscape');

        $nombreArchivo = 'certificado-' . str($curso->titulo)->slug() . '.pdf';

        return $pdf->download($nombreArchivo);
    }

    private function certificadoDelUsuario(Curso $curso): Certificado
    {
        $matricula = Matricula::where('curso_id', $curso->id)
            ->where('estudiante_id', auth()->id())
            ->firstOrFail();

        return Certificado::where('matricula_id', $matricula->id)->firstOrFail();
    }
}
