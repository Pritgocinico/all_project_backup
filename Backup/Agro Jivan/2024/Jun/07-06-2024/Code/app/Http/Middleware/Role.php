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
        $route = 'dashboard';
        if(Auth()->user()->role_id == 2){
            $route = 'employee-dashboard';
        }
        if(Auth()->user()->role_id == 3){
            $route = 'hr-dashboard';
        }
        if(Auth()->user()->role_id == 4){
            $route = 'confirm-dashboard';
        }
        if(Auth()->user()->role_id == 5){
            $route = 'driver-dashboard';
        }
        if(Auth()->user()->role_id == 6){
            $route = 'system-engineer-dashboard';
        }
        if(Auth()->user()->role_id == 7){
            $route = 'transport-department-dashboard';
        }
        if(Auth()->user()->role_id == 8){
            $route = 'warehouse-dashboard';
        }
        if(Auth()->user()->role_id == 9){
            $route = 'sales-manager-dashboard';
        }
        if(Auth()->user()->role_id == 10){
            $route = 'sales-service-dashboard';
        }
        return redirect()->route($route);
    }
}


