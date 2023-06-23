<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $user = User::find(Auth::user()->id);

        if (!  $user->roles($role)) {
            abort(401, 'This action is unauthorized.');
        }
        return $next($request);
    }
}
