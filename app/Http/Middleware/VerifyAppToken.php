<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyAppToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //verify request has valid API header 'X-App-Token' and it matches env
        if ($request->hasHeader('X-App-Token')) {

            $securityToken = $request->header('X-App-Token');
            
            if ($securityToken == config('app.app_token')) {
                return $next($request);
            }

            return $this->unauthorizedResponse($request);
        }
    
        return $this->unauthorizedResponse($request);
    }

    private function unauthorizedResponse(Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json(['result' => false, 'message' => 'Unauthorized Request'], 401);
        }
        
        return response('Unauthorized Request', 401);
    }
}
