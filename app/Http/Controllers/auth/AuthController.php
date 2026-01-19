<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request) {

        // التحقق من المدخلات
        $credentials = $request->validate([
            'name' => ['required'],
            'password' => ['required'],
        ]);
        $credentials['active'] = 1; // التأكد من أن المستخدم نشط
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // تأمين الجلسة

            // التوجيه الذكي بناءً على الدور
            return $this->redirectUserBasedOnRole(Auth::user());
        }

        // في حال فشل الدخول
        return back()->withErrors([
            'text' => 'الاسم أو كلمة المرور غير صحيحة.',
        ]);
    }
    protected function redirectUserBasedOnRole($user) {
        return match($user->role) {
            0 => redirect()->intended('/diwan_p'),
            1  => redirect()->intended('/copy_p'),
            default  => redirect()->intended('/dashboard'),
        };
    }


    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}