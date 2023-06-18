<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Spatie\Permission\Traits\hasRole;
class UserPostsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('trashed')) {
            $user = Auth::user();
            $postId = $request->route('post');

            if ($user->hasRole('admin') || $user->posts()->where('id', $postId)->exists()) {
                return $next($request);
            }
        }

        abort(401, 'This action is unauthorized.');
    }
}
