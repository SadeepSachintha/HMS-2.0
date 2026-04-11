<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! $request->user()) {
            return redirect('login');
        }

        $userRole = $request->user()->role->slug ?? 'guest';

        if (! in_array($userRole, $roles)) {
            abort(403, 'Unauthorized Access: Your clearance level does not permit this action.');
        }

        return $next($request);
    }
}
