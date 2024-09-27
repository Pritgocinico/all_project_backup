<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
//     public function handle(Request $request, Closure $next, $role)
//     {
//         // where $admin= 0 & $guest = 1
//         if (! $request->user()->hasRole($role)) {
//             return redirect()->route('login');
//         }
//         return $next($request);
// }
    public function handle($request, Closure $next, ...$roles)
    {
        foreach($roles as $role){
            if ($request->user()->hasRole($role)){
                return $next($request);
            }
        }
        abort(404);
    }
}


