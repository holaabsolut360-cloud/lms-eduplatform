<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $cursos = Curso::where('estado', 'publicado')->select('slug', 'updated_at')->get();

        $urls = collect([
            ['loc' => route('publico.home'), 'lastmod' => now()->toAtomString(), 'priority' => '1.0'],
            ['loc' => route('publico.catalogo'), 'lastmod' => now()->toAtomString(), 'priority' => '0.8'],
        ])->concat(
            $cursos->map(fn (Curso $curso) => [
                'loc' => route('publico.curso.detalle', $curso->slug),
                'lastmod' => $curso->updated_at->toAtomString(),
                'priority' => '0.9',
            ])
        );

        $xml = view('sitemap', compact('urls'))->render();

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
