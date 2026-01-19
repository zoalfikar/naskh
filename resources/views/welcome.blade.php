<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>نسخ قرارات محكمة النقض</title>
        <link rel="icon" type="image/png" href="{{ asset('images/OIP.jfif') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">

        <!-- Tailwind CSS -->
        <script src="{{ asset('tailwind.js') }}"></script>
    </head>
    <body class="bg-gray-100 text-gray-800 flex flex-col items-center justify-center min-h-screen relative">
        <div class="absolute top-4 right-4 text-right">
            <img src="{{ asset('images/logo.png') }}" alt="شعار" class="w-20 mb-2">
            <h1 class="text-lg font-bold">الجمهورية العربية السورية</h1>
            <h2 class="text-md font-medium">وزارة العدل</h2>
        </div>

        <h1 style="font-family: 'Tajawal', sans-serif;" class="text-4xl font-extrabold text-grey-700 mb-6">محكمة النقض</h1>

        <form id="login-form" action="/login" method="POST" class="bg-white p-6 rounded-lg shadow-md w-full max-w-sm">
            @csrf
            <div class="mb-4">
                <input type="text" id="username" name="name" placeholder="اسم المستخدم" required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div class="mb-4">
                <input type="password" id="password" name="password" placeholder="كلمة المرور" required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                تسجيل الدخول
            </button>
        </form>
        @error('text')
            <h1 class="text-red-500 text-center" style="padding: 10px; text-size: 18px;">{{ $message }}</h1>
        @enderror
    </body>
</html>
