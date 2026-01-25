<div id="print-section" class="hidden print:block rtl" dir="rtl" v-if="descionD">
    <div class="border-2 border-black p-2 mb-4">
        <div class="grid grid-cols-3 text-center border-b border-black pb-2 mb-2">
            <div class="text-sm">الصحيفة: @{{ pageNumber || 1 }}</div>
            <div class="font-bold text-lg">محكمة النقض<br>إعلام حكم</div>
            <div class="text-sm">لعام: @{{ cfile.c_start_year }}</div>
        </div>
        <div class="grid grid-cols-3 text-center font-bold">
            <div>رقم الأساس: @{{ cfile.number }}</div>
            <div>رقم القرار: @{{ descionD && descionD.decision_number ? descionD.decision_number : '' }}</div>
            <div>تاريخ القرار: @{{ descionD && descionD.decision_date ? descionD.decision_date : '' }}</div>
        </div>
    </div>

    <div class="text-center my-6">
        <div class="text-xl font-serif">بسم الله الرحمن الرحيم</div>
        <div class="text-lg font-serif mt-2">باسم الشعب العربي في سورية</div>
    </div>

    <div class="mb-6">
        <span class="font-bold underline">الهيئة الحاكمة:</span>
        <div class="grid grid-cols-2 mt-2 mr-4">
            <div v-for="judge in vjudges" :key="judge.j_code" class="text-md">
                @{{ judge.j_desc }}: @{{ judge.person.name }}
            </div>
        </div>
    </div>

    <div v-for="tab in selectedTabs" :key="tab.code" class="mb-4 text-justify leading-loose">
        <h3 class="font-bold text-md mb-1" v-if="tab.value">@{{ tab.description }}:</h3>
        <p class="text-lg font-serif whitespace-pre-line mr-4">@{{ tab.value }}</p>
    </div>

    <div class="mt-20 grid grid-cols-5 text-center gap-2 border-t border-black pt-4">
        <div v-for="judge in vjudges" :key="judge.j_code" class="text-[10px]">
            @{{ judge.j_desc }}<br>
            <span class="font-bold">@{{ judge.person.name }}</span>
        </div>
    </div>
</div>