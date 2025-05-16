<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    // Aquí puedes agregar rutas que quieras excluir de CSRF si es necesario
    protected $except = [
        //
    ];
}
