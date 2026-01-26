
<div v-if="showRadar" class="max-w-7xl mx-auto bg-white shadow-md rounded-lg overflow-hidden border border-gray-200 mt-6">
    <div class="bg-slate-800 px-6 py-4 flex justify-between items-center">
        <h3 class="text-white text-lg font-bold flex items-center gap-3">
           <div class="flex items-center gap-3">
                <div class="relative flex h-3 w-3">
                    <span class="relative inline-flex rounded-full h-3 w-3 transition-colors duration-300"
                        :class="{
                            'bg-yellow-100 shadow-[0_0_8px_rgba(250,204,21,0.4)]': pusherStatus === 'connected', /* أصفر إذا كان يعمل */
                            'bg-black': pusherStatus !== 'connected', /* أسود إذا كان لا يعمل أو جاري الاتصال */
                            'bg-red-600': pusherStatus === 'unavailable' /* أحمر فقط في حالة العطل الكامل */
                        }">
                    </span>
                </div>
            </div>
             لوحة تتبع ومراقبة القرارات 
        </h3>
        <div v-if="loadingDecisions" class="flex items-center gap-2 text-slate-300 text-sm animate-pulse">
            <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            جاري التحديث...
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-right">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-4 font-bold text-gray-700 uppercase tracking-wider">المحكمة / الأساس</th>
                    <th class="px-4 py-4 font-bold text-gray-700 text-center">رقم القرار</th>
                    <th class="px-4 py-4 font-bold text-gray-700 text-center italic">حالة النسخ</th>
                    <th class="px-4 py-4 font-bold text-gray-700 text-center italic">حالة المراجعة</th>
                    <th class="px-4 py-4 font-bold text-gray-700 text-center italic">منتهي؟</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr v-for="d in decisionsForCopy" :key="d.cfile.code" class="hover:bg-blue-50/50 transition-colors border-b border-gray-50">
                    
                    <td class="px-4 py-4">
                        <div class="font-bold text-gray-900 leading-tight">@{{ getCourt(d.cfile.v_corte).name }}</div>
                        <div class="text-xs text-blue-600 font-mono mt-1 italic">أساس: @{{ d.cfile.number }}</div>
                    </td>
                    
                    <td class="px-4 py-4 text-center font-mono text-blue-700 font-bold text-lg">
                        @{{ d.descionD.decision_number }}
                    </td>
                    
                    <td class="px-4 py-4 text-center">
                        <div v-if="d.descionD.reserved == 1" class="flex flex-col items-center gap-1">
                            <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-[10px] font-black border border-amber-300">
                                <i class="fas fa-keyboard ml-1"></i> قيد النسخ
                            </span>
                            <span class="text-[10px] text-gray-500 font-bold">بواسطة: @{{ d.descionD.reservedUName }}</span>
                        </div>
                        <div v-else-if="d.descionD.copied == 1">
                            <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-[10px] font-black border border-emerald-300 italic">
                                تم النسخ بنجاح <i class="fas fa-check"></i>
                            </span>
                        </div>
                        <span v-else class="text-gray-300 italic text-[11px]">بانتظار الناسخ...</span>
                    </td>
                    
                    <td class="px-4 py-4 text-center border-x border-gray-50">
                        <div v-if="d.descionD.reservedFRev == 1" class="flex flex-col items-center gap-1">
                            <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-[10px] font-black border border-purple-300 scale-105 shadow-sm">
                                <i class="fas fa-search ml-1"></i> محجوز للمراجعة
                            </span>
                            <span class="text-[10px] text-gray-500 font-bold italic font-serif">المراجع: @{{ d.descionD.reservedFRevUName }}</span>
                        </div>
                        <div v-else-if="d.descionD.reviewed == 1">
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-[10px] font-black border border-blue-300 italic">
                                تمت المراجعة <i class="fas fa-user-check"></i>
                            </span>
                        </div>
                        <span v-else class="text-gray-300 italic text-[11px]">لم يراجع بعد...</span>
                    </td>

                    <td class="px-4 py-4 text-center">
                        <div v-if="d.descionD.returned == 1">
                            <span class="bg-red-500 text-white px-3 py-1 rounded-full text-[10px] font-black animate-pulse">
                                مرتجع <i class="fas fa-undo"></i>
                            </span>
                        </div>
                        <div v-else-if="d.descionD.copied == 1 && d.descionD.reviewed == 1">
                            <i class="fas fa-check-double text-emerald-500 text-xl"></i>
                        </div>
                        <i v-else class="fas fa-hourglass-half text-gray-200"></i>
                    </td>
                </tr>

                <tr v-if="decisionsForCopy.length === 0">
                    <td colspan="5" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-2 text-gray-400">
                            <svg class="w-12 h-12 opacity-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="text-lg font-bold"> لا توجد قرارات قيد النسخ حالياً</span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 flex justify-between items-center">
        <span class="text-xs text-gray-500 font-medium">
            إجمالي الملفات المكتشفة: <span class="text-blue-600 font-bold">@{{ decisionsForCopy.length }}</span>
        </span>
        <div class="flex gap-4">
            <span class="flex items-center gap-1 text-[10px] text-gray-500"><span class="w-2 h-2 bg-amber-400 rounded-full"></span> نسخ</span>
            <span class="flex items-center gap-1 text-[10px] text-gray-500"><span class="w-2 h-2 bg-purple-400 rounded-full"></span> مراجعة</span>
            <span class="flex items-center gap-1 text-[10px] text-gray-500"><span class="w-2 h-2 bg-emerald-400 rounded-full"></span> منتهي</span>
        </div>
    </div>
</div>











{{-- <div class="max-w-6xl mx-auto bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
        
        <div class="bg-slate-800 px-6 py-4 flex justify-between items-center">
            <h3 class="text-white text-lg font-bold flex items-center gap-3">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </span>
                📊 تتبع القرارات (بث مباشر)
            </h3>
            <div v-if="loadingDecisions" class="flex items-center gap-2 text-slate-300 text-sm animate-pulse">
                <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                جاري التحديث...
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-right">
                <thead class="bg-gray-100 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 font-bold text-gray-700">المحكمة</th>
                        <th class="px-6 py-4 font-bold text-gray-700">رقم القرار</th>
                        <th class="px-6 py-4 font-bold text-gray-700 text-center">محجوز</th>
                        <th class="px-6 py-4 font-bold text-gray-700">الناسخ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" v-else>
                    <tr v-for="d in decisionsForCopy" :key="d.cfile.code" 
                        class="hover:bg-blue-50/50 transition-colors border-b border-gray-50">
                        
                        <td class="px-6 py-4">
                            <span class="font-medium text-gray-900">
                                @{{ getCourt(d.cfile.v_corte).name || '---' }}
                            </span>
                        </td>
                        
                        <td class="px-6 py-4 font-mono text-blue-700 font-bold">
                            @{{ d.descionD.decision_number }}
                        </td>
                        
                        <td class="px-6 py-4 text-center">
                            <span :class="d.descionD.reserved == 1 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'"
                                class="px-3 py-1 rounded-full text-xs font-bold border border-current opacity-80">
                                @{{ d.descionD.reserved == 1 ? 'نعم' : 'لا' }}
                            </span>
                        </td>
                        
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2" v-if="d.descionD.copier">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-[10px] font-bold">
                                    @{{ d.descionD.copierName ? d.descionD.copierName.substring(0,2) : '؟؟' }}
                                </div>
                                <span class="text-gray-700 font-medium">@{{ d.descionD.copierName }}</span>
                            </div>
                            <span v-else class="text-gray-400 italic text-xs">بانتظار التخصيص</span>
                        </td>
                    </tr>

                    <tr v-if="decisionsForCopy.length === 0">
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-2 text-gray-400">
                                <svg class="w-10 h-10 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="text-sm">لا توجد قرارات قيد النسخ حالياً</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
            <span class="text-xs text-gray-500 font-medium">
                إجمالي الملفات: @{{ decisionsForCopy.length }}
            </span>
        </div>
    </div>
</div> --}}