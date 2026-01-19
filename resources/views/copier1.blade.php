@extends('main')
@section('content')
<div id="decision-stepper" class="h-screen bg-gray-100 flex flex-col rtl" dir="rtl">
    
    <div class="bg-white shadow-sm border-b px-6 py-3 flex justify-between items-center">
        <h2 class="text-xl font-black text-slate-800 italic">إكمال بيانات قرار محكمة النقض</h2>
        <div class="flex gap-3">
            <button @click="saveAndExit" class="bg-blue-600 text-white px-6 py-2 rounded-md font-bold hover:bg-blue-700 transition">
                إرسال للتدقيق النهائي
            </button>
        </div>
    </div>

    <div class="flex flex-1 overflow-hidden">
        
        <div class="w-1/4 bg-slate-800 border-l border-slate-700 flex flex-col">
            <div class="p-4 bg-slate-900 text-slate-400 text-xs font-bold tracking-widest uppercase">
                خطوات بناء القرار
            </div>
            <nav class="flex-1 overflow-y-auto">
                <button 
                    v-for="(tab, index) in tabs" 
                    :key="tab.id"
                    @click="selectTab(tab.id)"
                    :class="[
                        activeTab === tab.id ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-700 hover:text-slate-200',
                        'w-full flex items-center px-6 py-4 text-right transition-all duration-200 border-b border-slate-700/50'
                    ]"
                >
                    <span class="ml-4 flex-shrink-0 w-6 h-6 flex items-center justify-center rounded-full border border-current text-[10px]">
                        @{{ index + 1 }}
                    </span>
                    <span class="text-sm font-bold">@{{ tab.name }}</span>
                    <i v-if="form.content[tab.id]" class="mr-auto fas fa-check-circle text-emerald-400"></i>
                </button>
            </nav>
        </div>

        <div class="flex-1 flex flex-col bg-white overflow-hidden">
            
            <div class="bg-blue-50 p-4 border-b border-blue-100 grid grid-cols-4 gap-4">
                <div class="bg-white p-2 rounded shadow-sm border border-blue-200">
                    <label class="block text-[10px] text-blue-600 font-bold mb-1 uppercase tracking-tighter">رقم القرار</label>
                    <input type="text" v-model="form.decision_number" class="w-full text-sm font-black border-none p-0 focus:ring-0">
                </div>
                <div class="bg-white p-2 rounded shadow-sm border border-blue-200">
                    <label class="block text-[10px] text-blue-600 font-bold mb-1 uppercase tracking-tighter">أساس النقض</label>
                    <input type="text" v-model="form.base_number" class="w-full text-sm font-black border-none p-0 focus:ring-0">
                </div>
                <div class="bg-white p-2 rounded shadow-sm border border-blue-200">
                    <label class="block text-[10px] text-blue-600 font-bold mb-1 uppercase tracking-tighter">تاريخ القرار</label>
                    <input type="date" v-model="form.decision_date" class="w-full text-sm font-black border-none p-0 focus:ring-0">
                </div>
                <div class="bg-white p-2 rounded shadow-sm border border-blue-200">
                    <label class="block text-[10px] text-blue-600 font-bold mb-1 uppercase tracking-tighter">الهيئة</label>
                    <div class="text-[11px] font-bold text-slate-700 truncate">@{{ form.judges_summary }}</div>
                </div>
            </div>

            <div class="flex-1 p-8 overflow-y-auto">
                <div class="max-w-4xl mx-auto space-y-6">
                    <div class="flex items-center gap-4 border-b-2 border-slate-100 pb-4">
                        <h1 class="text-2xl font-black text-slate-800 tracking-tight">
                            تحرير: <span class="text-blue-600">@{{ currentTabName }}</span>
                        </h1>
                        <span class="text-xs bg-slate-100 px-3 py-1 rounded-full text-slate-500 font-mono italic">
                            Section: @{{ activeTab }}
                        </span>
                    </div>

                    <textarea 
                        v-model="form.content[activeTab]"
                        class="w-full min-h-[450px] p-6 text-xl leading-[2.2] text-slate-800 border-none focus:ring-0 font-serif bg-slate-50 rounded-xl shadow-inner border border-slate-200"
                        :placeholder="'اكتب نص ' + currentTabName + ' هنا...'"
                    ></textarea>
                </div>
            </div>

            <div class="p-4 bg-white border-t flex justify-between items-center shadow-lg">
                <button @click="prevTab" :disabled="isFirstTab" class="px-6 py-2 text-slate-500 font-bold disabled:opacity-30">
                    السابق
                </button>
                <div class="flex gap-2">
                    <span v-for="tab in tabs" :key="tab.id" 
                        class="w-2 h-2 rounded-full transition-all"
                        :class="activeTab === tab.id ? 'bg-blue-600 w-6' : 'bg-slate-200'">
                    </span>
                </div>
                <button @click="nextTab" class="bg-slate-800 text-white px-8 py-2 rounded-md font-bold hover:bg-black transition">
                    @{{ isLastTab ? 'حفظ وإغلاق' : 'المقطع التالي' }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection