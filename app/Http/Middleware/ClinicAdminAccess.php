<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Session;

class ClinicAdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Session::has('user_details')){
			if(Session::get('user_details')->role == "Clinic Admin"){
				return $next($request);
			}else{
				abort(403, 'Access denied');
			}
		}else{
			return redirect()->to('login');
		}
    }
}
