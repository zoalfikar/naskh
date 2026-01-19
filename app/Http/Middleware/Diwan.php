<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Diwan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->role === 0) {
            return $next($request); // اسمح له بالمرور
        }
        return redirect('/'); // أعد التوجيه إلى الصفحة الرئيسية إذا لم يكن المستخدم من الدور المناسب
    }
}
