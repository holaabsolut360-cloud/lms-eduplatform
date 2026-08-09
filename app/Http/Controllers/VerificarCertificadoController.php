<?php

namespace App\Http\Controllers;

use App\Models\Certificado;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerificarCertificadoController extends Controller
{
    public function mostrar(Request $request, ?string $codigo = null): View
    {
        $codigo = $codigo ?? $request->query('codigo');

        $certificado = $codigo
            ? Certificado::with('matricula.curso', 'matricula.estudiante')
                ->where('codigo_verificacion', $codigo)
                ->first()
            : null;

        return view('publico.verificar-certificado', [
            'codigo' => $codigo,
            'certificado' => $certificado,
        ]);
    }
}
