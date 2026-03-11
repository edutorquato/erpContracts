<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class AdminLevel
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user || $user->level !== User::LEVEL_ADMIN) {
            abort(403);
        }

        return $next($request);
    }
}
