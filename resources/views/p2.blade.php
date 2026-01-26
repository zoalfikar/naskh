<div v-if="cfd && descionD" id="print-section" class="hidden print:block rtl bg-white font-serif-legal px-4 " dir="rtl">
    <div class="legal-border relative min-h-[26cm]">
        
        <div class="flex justify-between items-center border-b-0 border-black pb-2 mb-0 pt-0 px-2">
            
            <div class="w-1/3 text-right text-[13px] font-bold leading-5">
                <p>الجمهورية العربية السورية</p>
                <p>وزارة العـــــــــــــــــــــــــــــدل</p>
                <p>محكمـــــــــــــة النقـــــــــــــض</p>
                <p class="mt-0.5 text-blue-900">الغرفة: @{{ currentVCName }}</p>
            </div>

            <div class="w-1/3 text-center  ">
                <img src="{{ asset('images/logo.png') }}" 
                    class="h-20 w-auto mx-auto mb-1 object-contain" 
                    alt="الشعار الرسمي">
                <h1 class="text-2xl font-black   tracking-tighter">إعــــلام حكــــم</h1>
                {{-- <div class="text-[14px] font-bold">لعام: @{{ cfile.c_start_year }} م</div> --}}
            </div>

            <div class="w-1/3 flex flex-col items-end pr-4 pt-12 ">
                <div class="text-[11px] mt-0.5 ml-0 font-bold  ">رقم الصحيفه: @{{ pageNumber || 1 }}</div>
                {{-- <div class="p-0 border-[1.5px] border-black bg-white">
                    <img :src="'https://api.qrserver.com/v1/create-qr-code/?size=140x50&data=' + cfile.code" 
                        class="w-40 h-12 object-fill" 
                        alt="الباركود" />
                </div> --}}
                {{-- <div class="text-[9px] font-mono font-bold tracking-widest w-36 text-center">CODE: @{{ cfile.code }}</div> --}}
            </div>
        </div>

        <div class="grid grid-cols-3 border-2 border-black mb-4 text-center divide-x-2 divide-x-reverse divide-black font-black text-lg bg-gray-50/30">
            <div class="py-1 px-1">رقم الأساس: @{{ cfile.number }}</div>
            <div class="py-1 px-1">رقم القرار: @{{ descionD.decision_number }}</div>
            <div class="py-1 px-1">لعام: @{{ cfile.c_start_year }} م</div>
        </div>

        <div class="text-center mb-5">
            <div class="text-1xl font-bold mb-1 font-serif">بسم الله الرحمن الرحيم</div>
            <div class="text-0.5xl font-bold  border-b-0 border-black inline-block pb-1 px-10">باسم الشعب العربي السوري</div>
        </div>

        <div class="mb-10">
            <h3 class="font-bold text-b mb-3 underline">إن محكمة النقض - @{{currentVCName}} -الهيئة الحاكمة والمؤلفة من السادة القضاة:</h3>
            <div class="grid grid-cols-3 mt-2 mr-4 text-md">
                <div v-for="(judge, index) in vjudges" :key="judge.j_code" class="flex items-center">
                    <span class="font-bold">@{{ judge.j_desc }}:</span>
                    <span class="mx-2 italic">@{{ judge.person.name }}</span>
                </div>
            </div>
        </div>

        <div class="space-y-6"> 
            <div v-for="tab in selectedTabs" :key="tab.code" class="tab-content-block">
                <div class="flex items-center mb-1"> <div class="w-3 h-3 bg-black ml-2"></div>
                    <h4 class="font-black text-xl leading-none">@{{ tab.description }}:</h4>
                </div>
                
                <pre class="text-[1.25rem] pr-9 text-justify leading-normal font-medium font-serif-legal whitespace-pre-wrap break-words m-0 p-0" 
                    style="font-family: 'Amiri', serif !important;">@{{ tab.value }}</pre>
            </div>
        </div>




        <div class="mt-6 text-center">
            <p class="text-[16px] font-bold">
                {{-- قراراً صدر وتلي علناً في يوم ............. الموافق لـ @{{ new Date().toLocaleDateString('ar-SA') }} م --}}
                قراراً صدر  في  @{{descionD.higry_date}} ه الموافق لـ @{{descionD.decision_date}} م

            </p>
        </div>




        <div class="mt-10 border-t border-black pt-4 px-10">
            <div class="flex justify-between items-center text-[13px] font-bold">
                
                <div class="text-right">
                    <p>الناسخ: .............................</p>
                </div>

                <div class="text-left">
                    <p>المدقق: .................</p>
                </div>

            </div>
        </div>

{{-- <div class="mt-8 mr-10">
    <div class="border-2 border-black p-2 w-48 text-center text-[12px] font-black rotate-[-5deg]">
        نسخة طبق الأصل <br>
        أعطيت لصاحب العلاقة
    </div>
</div> --}}







        <div class="absolute bottom-0 left-0 w-full  grid grid-cols-5 text-center gap-2 border-t border-black pt-4">
            <div v-for="judge in vjudges" :key="judge.j_code" class="text-[10px]">
                @{{ judge.j_desc }}<br>
                <span class="font-bold">@{{ judge.person.name }}</span>
            </div>
        </div>

    </div>
</div>