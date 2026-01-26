        <nav  class="bg-[#0f2e2e] text-white shadow-xl p-4 mb-8">
            <div class="container mx-auto flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/logo.png') }}" alt="الشعار" class="h-14 brightness-110">
                    <div>
                        <h1 class="text-xl font-bold">الجمهورية العربية السورية</h1>
                        <p class="text-xs text-yellow-500">وزارة العدل - نظام الأرشفة الإلكتروني</p>
                    </div>
                </div>
                {{-- <div class="hidden md:flex gap-6">
                    <span class="text-sm opacity-80">التاريخ: 2026/01/08</span>
                    <span class="text-sm font-bold">المستخدم: {{ Auth::user()->name }}</span>
                     
                </div> --}}

                <div class="flex items-center gap-4">
                    <div class="flex flex-row items-center gap-2 border-l-2 border-yellow-500/30 pl-4 ml-2">
                        <span class="text-sm opacity-80">مرحباً،</span>
                        <span class="font-bold text-yellow-500">{{ Auth::user()->name }}</span>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button title="تسجيل الخروج" type="submit" 
                                class="bg-red-600/20 hover:bg-red-600 text-red-100 px-4 py-2 rounded-lg border border-red-500/50 transition-all flex items-center gap-2 text-sm font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </nav>