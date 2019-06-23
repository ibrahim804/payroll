<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\CustomsErrorsTrait;

class Cors
{
    use CustomsErrorsTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request)->header('Access-Control-Allow-Origin', '*');

        // return $next($request)
        //     ->header('Access-Control-Allow-Origin', '*')
        //     ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH')
        //     ->header('Access-Control-Allow-Credentials', 'true')
        //     ->header('Access-Control-Max-Age', '10000')
        //     ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, Accept, X-Requested-With');

        // ORIGINAL WAY OF CORS POLICY

        // header("Access-Control-Allow-Origin: *");
        //
        // $headers = [
        //     'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, PUT, DELETE',
        //     'Access-Control-Allow-Headers' => 'Content-Type, X-Auth-Token, Origin, Authorization',
        // ];
        //
        // if ($request->getMethod() == "OPTIONS"){
        //     //The client-side application can set only headers allowed in Access-Control-Allow-Headers
        //     return response()->json('OK', 200, $headers);
        // }
        //
        // $response = $next($request);
        //
        // foreach ($headers as $key => $value) {
        //     $response->header($key, $value);
        // }
        //
        // return $response;
    }
}









//
