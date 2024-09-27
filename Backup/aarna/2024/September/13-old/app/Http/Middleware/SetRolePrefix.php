<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class SetRolePrefix
{
    public function handle($request, Closure $next)
    {
        if ($user = $request->user()) {
            // If user is authenticated, set the role prefix
            $role = $user->role; // Assuming 'role' is a property of your user model

            // Set the role prefix based on the user's role
            $request->attributes->set('rolePrefix', $role);
        } else {
            // If user is not authenticated, set a default role prefix or handle accordingly
            $request->attributes->set('rolePrefix', 'guest');
        }

        return $next($request);
    }
}
