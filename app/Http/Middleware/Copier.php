<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Copier
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->role === 1) {
            return $next($request); // اسمح له بالمرور
        }
        return redirect('/'); // أعد التوجيه إلى الصفحة الرئيسية إذا لم يكن المستخدم من الدور المناسب
    }
}
