<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Route;

class PermissionAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->cat_id){
            $allowed_cats = explode("," , Auth::user()->perm->allowed_cats);
            if (!in_array($request->cat_id , $allowed_cats)){
            return redirect("/dashboard");
            }
        }
        return $next($request);
        //dd(Route::current());
    }
}
