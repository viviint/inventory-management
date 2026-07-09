<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller
{
    use AuthorizesRequests;

    /**
     * Determine if the request is from Postman, AJAX, or explicitly requesting JSON.
     */
    protected function isApiOrPostman($request): bool
    {
        return $request->wantsJson() ||
               $request->ajax() ||
               str_contains($request->header('User-Agent', ''), 'Postman') ||
               $request->is('api/*');
    }
}
