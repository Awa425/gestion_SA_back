<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use  App\Models\User ;

class UserAuthorisation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if(User::isSuperAdmin($user) || User::isAdmin($user) || User::isVigile($user)){
            if (($request->route()->getActionMethod() === 'store' || $request->route()->getActionMethod() === 'storeExcel'
            || $request->route()->getActionMethod() === 'destroy')   && ( !User::isSuperAdmin($user) && !User::isVigile($user))) {
                return abort(403, 'Unauthorized action.');
            }
            return $next($request);
        }
        return abort(403, 'Unauthorized actions.');

    }
}
