@extends('main')


@section('head_content')
    <style>
       @media print {
        /* إخفاء كل شيء ما عدا قسم الطباعة */
        body * {
            visibility: hidden;
        }
        #print-section, #print-section * {
            visibility: visible;
        }
        #print-section {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            padding: 1.5cm;
        }
        /* منع تقطيع الورقة في منتصف الفقرة */
        p, .mb-4 {
            page-break-inside: avoid;
        }
        }

        /* إخفاء قسم الطباعة في العرض العادي داخل المتصفح */
        #print-section {
            display: 'block';
        }

    </style>
@endsection


@section('content')

<div id="decision-editor" v-cloak class="min-h-screen bg-gray-100 p-4 rtl" dir="rtl">
    <div v-if="Object.keys(errors).length > 0" class="mb-4 p-4 bg-red-50 border-r-4 border-red-500 rounded-md">
        <h4 class="text-red-800 font-bold mb-2">يوجد أخطاء في البيانات تمنع الحفظ:</h4>
        <ul class="list-disc list-inside text-sm text-red-700">
            <li v-for="(error, index) in errors" :key="index">
                @{{ error[0] }} </li>
        </ul>
    </div>
    <div class="max-w-[1600px] mx-auto bg-white shadow-2xl rounded-xl overflow-hidden border border-gray-200">
        <div class="bg-slate-800 p-3 flex items-center justify-between">
            <div class="flex gap-4">
                <button @click="saveDraft" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg font-bold transition flex items-center gap-2">
                    <i class="fas fa-save"></i> حفظ القرار (F10)
                </button>
                <button @click="printDecision" class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-print"></i> معاينة وطباعة
                </button>
            </div>
            <div class=" font-bold text-lg text-emerald-400 flex items-center gap-2">
                <h1 class="text-xl">@{{ currentVCName }}</h1>
            </div>
            <div class="text-slate-400 text-sm font-mono">
                الحالة: <span class="text-emerald-400">متصل - جاهز للنسخ</span>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-0 h-[calc(100vh-30px)]">
            
            <div class="col-span-4 border-l border-gray-200 bg-slate-50 overflow-y-auto p-4 space-y-6">
                
                <section class="space-y-3">
                    <h4 class="text-blue-900 font-black border-b border-blue-200 pb-2 text-sm uppercase">تحديد محكة النقض</h4>
                    <div>
                    <select v-model="selectedVCourt"  :disabled="selectedVCourt" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 text-sm">
                        <option v-for="(vc, index) in userVCourts" :key="index" :value="vc.vcourt">
                                @{{ vc.vcourt_name }} @{{ vc.vcourt }}
                        </option>                                
                    </select> 
                    </div>
                </section> 


                <section class="space-y-3" v-if="descionD">
                    <h4 class="text-blue-900 font-black border-b border-blue-200 pb-2 text-sm uppercase">بيانات القرار الأساسية</h4>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="col-span-1">
                            <label class="block text-[11px] font-bold text-gray-600 mb-1">نوع القرار</label>
                            <select v-model="descionD.decision_type" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 text-sm">
                                <option v-for="(type, index) in decisionTypes" :key="type.code" :value="type.code">
                                        @{{ type.description }}
                                </option>
                            </select>
                        </div>
                        <div class="col-span-1">
                            <label  class="block text-[11px] font-bold text-gray-600 mb-1">رقم القرار</label>
                            <input :disabled=true v-model="descionD.decision_number" type="number"  class="w-full border-gray-300 rounded-md text-sm font-bold text-blue-800">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-[11px] font-bold text-gray-600 mb-1">تاريخ القرار (ميلادي / هجري)</label>
                            <div class="flex gap-2">
                                <input  :disabled=true v-model="descionD.decision_date" type="date"  class="w-full border-gray-300 rounded-md text-sm">
                                <input v-model="descionD.higry_date" type="text" placeholder= " هجري  " class="w-1/2 border-gray-300 rounded-md text-sm bg-white-50">
                            </div>
                        </div>
                    </div>
                </section>

                <section class="space-y-3">
                    <h4 class="text-blue-900 font-black border-b border-blue-200 pb-2 text-sm uppercase">معلومات الدعوى - محكمة النقض</h4>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="col-span-1">
                            <label class="block text-[11px] font-bold text-gray-600 mb-1">رقم الاساس</label>
                            <input :disabled=true v-model="cfile.number" type="number"  class="w-full border-gray-300 rounded-md text-sm font-bold text-blue-800">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-[11px] font-bold text-gray-600 mb-1">اساس أول دعوى</label>
                            <input  type="number" v-model="cfile.c_begin_n"  class="w-full border-gray-300 rounded-md text-sm font-bold text-blue-800">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-[11px] font-bold text-gray-600 mb-1">تاريخ قيد الدعوى</label>
                            <div class="flex gap-2">
                                <input :disabled=true v-model="cfile.c_date"  type="date"  class="w-full border-gray-300 rounded-md text-sm">
                            </div>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-[11px] font-bold text-gray-600 mb-1"> لعام</label>
                            <div class="flex gap-2">
                                <input :disabled=true v-model="cfile.c_start_year"  type="number"  class="w-full border-gray-300 rounded-md text-sm">
                            </div>
                        </div>
                    </div>
                </section>


                <section :class="{'ring-2 ring-red-500 animate-pulse shadow-lg': errors.judges}"
                    class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                    <h4 class="text-slate-700 font-bold mb-3 text-xs flex justify-between">
                        هيئة محكمة النقض
                        <span v-if="errors.judges" class="text-red-600 text-[10px] animate-bounce">
                            <i class="fas fa-arrow-down"></i> راجع بيانات الهيئة
                        </span>
                        <button v-if="vjudges" @click="orderTd=!orderTd" class="text-blue-600 hover:underline">@{{!orderTd ? 'ترتيب' : 'حفظ' }}</button>
                        {{-- <button @click="" class="text-blue-600 hover:underline">+ إضافة قاضي</button> --}}
                    </h4>
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="bg-slate-100">
                                <th class="p-2 border border-gray-200 w-45">القاضي</th>
                                <th class="p-2 border border-gray-200 w-25">الصفة</th>
                                <th class="p-2 border border-gray-200 w-15">مخالف</th>
                                <th class="p-2 border border-gray-200 w-15">فصل</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(judge, index) in vjudges" :key="judge.j_code">
                                <td  class="p-2 border border-gray-200">@{{ judge.person.name }}</td>
                                <td class="p-2 border border-gray-200">
                                     <select v-model="judge.j_desc" class="w-full border-gray-200 rounded p-1.5 text-xs bg-white">
                                         <option value="رئيسا">رئيسا</option>
                                         <option  value="مستشارا">مستشارا</option>
                                    </select>
                                </td>
                                <td class="border border-gray-200 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <input type="checkbox" 
                                            v-model="judge.j_oppsoit" 
                                            :true-value="1" 
                                            :false-value="0"
                                            @change="judge.j_oppsoit === 1 ? judge.j_serperator = 0 : null"
                                            class="w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500">
                                        <span class="text-[10px] font-bold" :class="judge.j_oppsoit ? 'text-red-600' : 'text-gray-400'">مخالف</span>
                                    </div>
                                </td>
                                <td class="border border-gray-200 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <input type="checkbox" 
                                            v-model="judge.j_serperator" 
                                            :true-value="1" 
                                            :false-value="0"
                                            @change="handleSeparator(index)"
                                            class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                        <span class="text-[10px] font-bold" :class="judge.j_serperator ? 'text-blue-600' : 'text-gray-400'">فصل</span>
                                    </div>
                                </td>
                                <td v-if="orderTd" class="border border-gray-200 text-center">
                                    <div class="flex flex-col gap-1 items-center py-1">
                                        <button @click.prevent="moveUp(index)" v-if="index > 0" class="text-slate-400 hover:text-blue-600 transition-colors">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"/></svg>
                                        </button>
                                        <button @click.prevent="moveDown(index)" v-if="index < vjudges.length - 1" class="text-slate-400 hover:text-blue-600 transition-colors">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </section>

                <section class="space-y-4">

                    <div class="p-4 bg-blue-50 border border-blue-100 rounded-lg shadow-sm">
                        <h5 class="text-[11px] font-black text-blue-800 mb-3 flex items-center gap-2 italic underline uppercase text-right">
                            معلومات الدعوى - درجة ثانية
                        </h5>
                        
                        <div class="space-y-3">
                            <div class="grid grid-cols-3 gap-2">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 mb-1">رقم الأساس</label>
                                    <input v-model='cfile.degree2_number' type="text"  placeholder="الأساس" class="w-full border-gray-200 rounded p-1.5 text-xs focus:ring-slate-400 focus:border-slate-400 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 mb-1">رقم القرار</label>
                                    <input v-model='cfile.degree2_dec_n' type="text"  placeholder="القرار"  class="w-full border-gray-200 rounded p-1.5 text-xs focus:ring-slate-400 focus:border-slate-400 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 mb-1">تاريخ القرار</label>
                                    <input v-model='cfile.degree2_dec_d' type="date"  class="w-full border-gray-200 rounded p-1.5 text-xs shadow-sm">
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-2">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 mb-1">المحافظة</label>
                                    <select v-model='cfile.degree2_state' class="w-full border-gray-200 rounded p-1.5 text-xs bg-white shadow-sm">
                                        <option  value="">اختر...</option>
                                        <option v-for="state in states" :value="state.code">@{{ state.name }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 mb-1">المحكمة</label>
                                    <select v-model='cfile.degree2_court'  class="w-full border-gray-200 rounded p-1.5 text-xs bg-white shadow-sm">
                                        <option value="">اختر...</option>
                                        <option v-for="court in current2courts" :value="court.code">@{{ court.name }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 mb-1">الغرفة</label>
                                    <select v-model='cfile.degree2_room'   class="w-full border-gray-200 rounded p-1.5 text-xs bg-white shadow-sm">
                                        <option value="">اختر...</option>
                                        <option v-for="room in rooms" :value="room.code">@{{ room.name }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-slate-100 border border-slate-200 rounded-lg shadow-sm">
                        <h5 class="text-[11px] font-black text-slate-700 mb-3 flex items-center gap-2 italic underline uppercase">
                            معلومات الدعوى - درجة أولى
                        </h5>
                        
                        <div class="space-y-3">
                            <div class="grid grid-cols-3 gap-2">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 mb-1">رقم الأساس</label>
                                    <input v-model='cfile.degree1_number' type="text"  placeholder="الأساس" class="w-full border-gray-200 rounded p-1.5 text-xs focus:ring-slate-400 focus:border-slate-400 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 mb-1">رقم القرار</label>
                                    <input v-model='cfile.degree1_dec_n' type="text"  placeholder="القرار"  class="w-full border-gray-200 rounded p-1.5 text-xs focus:ring-slate-400 focus:border-slate-400 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 mb-1">تاريخ القرار</label>
                                    <input v-model='cfile.degree1_dec_d' type="date"  class="w-full border-gray-200 rounded p-1.5 text-xs shadow-sm">
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-2">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 mb-1">المحافظة</label>
                                    <select v-model='cfile.degree1_state' class="w-full border-gray-200 rounded p-1.5 text-xs bg-white shadow-sm">
                                        <option  value="">اختر...</option>
                                        <option v-for="state in states" :value="state.code">@{{ state.name }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 mb-1">المحكمة</label>
                                    <select v-model='cfile.degree1_court'  class="w-full border-gray-200 rounded p-1.5 text-xs bg-white shadow-sm">
                                        <option value="">اختر...</option>
                                        <option v-for="court in current1courts" :value="court.code">@{{ court.name }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 mb-1">الغرفة</label>
                                    <select v-model='cfile.degree1_room'   class="w-full border-gray-200 rounded p-1.5 text-xs bg-white shadow-sm">
                                        <option value="">اختر...</option>
                                        <option v-for="room in rooms" :value="room.code">@{{ room.name }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                </section>
            </div>

            <div v-if="loading" class="col-span-8 flex flex-col bg-white bg-opacity-75 flex items-center justify-center z-50">
                <div class="text-center">
                    <div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-16 w-16 mb-4 mx-auto"></div>
                    <h2 class="text-xl font-semibold text-gray-700">جاري تحميل البيانات...</h2>
                    <p class="text-gray-500">يرجى الانتظار قليلاً</p>
                </div>
            </div>
            <div v-else="loading" class="col-span-8 flex flex-col bg-white">
                
                <div class="flex flex-wrap gap-0 border-b border-gray-200 bg-slate-50">
                    <button v-for="tab in selectedTabs" 
                            :key="tab.code"
                            @click="activeTab = tab.code"
                            :class="activeTab === tab.code ? 'bg-white border-x border-t border-gray-200 text-blue-700 border-b-white z-10 -mb-px' : 'text-gray-500 hover:bg-gray-100'"
                            class="px-4 py-3 text-xs font-bold transition-all relative">
                        @{{ tab.description }}
                    </button>
                    <button @click="showChooseTabModal" class="ml-auto px-4 py-3 text-xs font-bold text-green-600 hover:bg-green-100 transition-all">
                        + تحديد فقرات القرار
                    </button>
                </div>

                <div class="flex-grow p-6 relative">
                    <div class="absolute top-2 right-6 text-[10px] text-blue-500 font-bold uppercase">
                        المقطع الحالي: 
                    </div>
                    <textarea v-for="tab in selectedTabs" :key="tab.code"
                        v-model="tab.value"
                        v-show="activeTab === tab.code"
                        class="w-full h-full p-8 text-xl leading-[2] text-gray-800 border-none focus:ring-0 resize-none font-serif bg-white shadow-inner"
                        placeholder="ابدأ بكتابة نص القرار هنا..."
                    > </textarea>
                </div>

                <div class="bg-slate-100 border-t border-gray-200 px-4 py-2 flex justify-between items-center text-[11px] text-gray-500">
                    <div>الكلمات: @{{ wordCount }}</div>
                    <div class="flex gap-4">
                        <span>CAPS</span>
                        <span>NUM</span>
                        <span class="text-blue-600 font-bold italic">نظام إدارة قرارات محكمة النقض v2.0</span>
                    </div>
                </div>
            </div>













            <div v-if="chooseTabModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm rtl" dir="rtl">
                <div class="bg-white w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden flex flex-col h-[900px]">
                    
                    <div class="p-6 border-b bg-slate-50 flex justify-between items-center">
                        <h3 class="text-xl font-black text-slate-800">تخصيص مقاطع القرار القضائي</h3>
                        <span class="text-xs font-bold text-slate-400">محكمة المستخدم: @{{ userCourtName }}</span>
                    </div>

                    <div class="flex-1 p-8 flex gap-6 overflow-hidden">
                        
                        <div class="flex-1 flex flex-col">
                            <label class="text-xs font-black text-slate-500 mb-3 block uppercase tracking-wider">الفقرات المتاحة </label>
                            <div class="flex-1 border-2 border-slate-100 rounded-xl overflow-y-auto p-3 space-y-2 bg-slate-50">
                                <div v-for="tab in kindTabs" :key="tab.code"
                                    @click="tempTabSelectedAvailable = tab"
                                    :class="['p-4 rounded-lg border transition-all cursor-pointer font-bold text-sm', 
                                            tempTabSelectedAvailable?.code === tab.code ? 'bg-blue-600 text-white border-blue-600 shadow-md' : 'bg-white text-slate-700 border-slate-200 hover:border-blue-300']">
                                    @{{ tab.description }}
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col justify-center gap-4 px-2">
                            <button @click="moveToActive" :disabled="!tempTabSelectedAvailable"
                                    class="w-12 h-12 rounded-full bg-white border-2 border-slate-200 text-slate-600 hover:bg-blue-600 hover:text-white hover:border-blue-600 disabled:opacity-30 transition-all flex items-center justify-center">
                                <i class="fas fa-arrow-left text-lg">+</i>  </button>
                            <button @click="removeFromActive" :disabled="!tempTabSelectedAvailable"
                                    class="w-12 h-12 rounded-full bg-white border-2 border-slate-200 text-slate-600 hover:bg-red-600 hover:text-white hover:border-red-600 disabled:opacity-30 transition-all flex items-center justify-center">
                                <i class="fas fa-arrow-right text-lg">-</i>  </button>
                        </div>

                        <div class="flex-1 flex flex-col">
                            <label class="text-xs font-black text-emerald-600 mb-3 block uppercase tracking-wider">المقاطع المختارة للعمل</label>
                            <div class="flex-1 border-2 border-emerald-100 rounded-xl overflow-y-auto p-3 space-y-2 bg-emerald-50/30">
                                <div v-for="tab in selectedTabs" :key="tab.code"
                                    @click="tempTabSelectedAvailable = tab"
                                    :class="['p-4 rounded-lg border transition-all cursor-pointer font-bold text-sm', 
                                            tempTabSelectedAvailable?.code === tab.code ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-white text-slate-700 border-emerald-200 hover:border-emerald-400']">
                                    @{{ tab.description }}
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="p-6 bg-slate-50 border-t flex justify-end gap-3">
                        <button @click="hideChooseTabModal" class="px-8 py-2.5 rounded-xl bg-slate-800 text-white font-black hover:bg-black shadow-lg transition">تم</button>
                    </div>
                </div>
            </div>
            @include('print_decision')

        </div>
    </div>
</div>


    <script>
      

            const app = Vue.createApp({
                data() {
                    return {
                        userCourtName: '',
                        selectedVCourt:null,
                        userVCourts: '',
                        orderTd:false,
                        loading: false,
                        chooseTabModal: false,
                        tabs: [],
                        kindTabs: [],
                        selectedTabs: [],
                        activeTab: null,
                        tempTabSelectedAvailable: null,
                        vjudges:[],
                        rooms:null,
                        states:null,
                        courts:[],
                        selectedStateD1:null,
                        selectedStateD2:null,
                        decisionTypes: [],
                        cfd:null,
                        cfile:{
                            kind:null,
                            subject:null,
                            number: null,
                            c_begin_n: null,
                            c_date: null,
                            c_start_year:null,
                            degree1_number:null,
                            degree1_year:null,
                            degree1_dec_n:null,
                            degree1_dec_d:null,
                            degree1_court:null,
                            degree1_state:null,
                            degree1_room:null,
                            degree2_number:null,
                            degree2_year:null,
                            degree2_dec_n:null,
                            degree2_dec_d:null,
                            degree2_court:null,
                            degree2_state:null,
                            degree2_room:null,
                        },
                        descionD: {
                            decision_type: 1,
                            decision_number: '',
                            decision_date: '',
                            higry_date:null
                        },
                        errors: {},
                    }
                },
                computed: {
                    currentVCName() {
                            if (this.selectedVCourt) {
                                const court = this.userVCourts.find(C => C.vcourt == this.selectedVCourt);
                                return court ? court.vcourt_name : null;
                                
                            } else {
                                return null;
                            }
                        },
                        current1courts() {
                            if (!this.courts.length) return [];
                            
                            return this.courts.filter(C => {
                                // نتحقق من وجود الكاتيغوري أولاً ثم نطابق كود المحافظة
                                return C.category && C.category.state == this.cfile.degree1_state;
                            });
                        },
                        current2courts() {
                            if ( !this.courts.length) return [];
                            
                            return this.courts.filter(C => {
                                return C.category && C.category.state == this.cfile.degree2_state;
                            });
                        },

                    wordCount() {
                        if (this.activeTab && this.selectedTabs.find(t => t.code === this.activeTab)) {
                            const tab = this.selectedTabs.find(t => t.code === this.activeTab);
                            return tab.value ? tab.value.trim().split(/\s+/).length : 0;
                        }
                        return 0;
                    },
                cfdReq() {
                        // التحقق من وجود البيانات الأساسية
                        if (!this.cfile || !this.descionD || !this.cfd || !this.cfd.descionD) {
                            return null;
                        }

                        return {
                            cfile: {
                                ...this.cfile ,
                                v_corte : this.cfd.cfile.v_corte,
                                id : this.cfd.cfile.id,
                                round_year:this.cfd.cfile.round_year,
                                code:this.cfd.cfile.code,
                            },
                            // توحيد المسمى مع الـ Controller (descionD)
                            descionD: {
                                ...this.descionD ,
                                hurry : this.cfd.descionD.hurry,
                                reviewed: this.cfd.descionD.reviewed,
                                copied: this.cfd.descionD.copied,
                                reserved: this.cfd.descionD.reserved,
                                reservedFRev: this.cfd.descionD.reservedFRev,
                                reservedUName: this.cfd.descionD.reservedUName,
                                reservedU: this.cfd.descionD.reservedU,
                                reservedFRevU: this.cfd.descionD.reservedFRevU,
                                reservedFRevUName: this.cfd.descionD.reservedFRevUName,
                                // ندمج القضاة والتبويبات المختارة ليتم حفظهم معاً
                                vjudges: this.vjudges,
                                tabs: this.selectedTabs
                            }
                        };
                    },
                },
                watch: {
                    // اسم الدالة هنا يجب أن يكون مطابقاً تماماً لاسم المتغير في data
                    selectedVCourt(newVal, oldVal) {
                        // لا تنفذ الجلب إذا كانت القيمة الجديدة هي نفس القديمة أو إذا كان التحميل الأولي شغالاً
                        if (newVal && newVal !== oldVal) {
                            if (!this.cfd)
                            this.vcourtChoosed(newVal);
                            this.loading = false;

                        }
                    },
                    'descionD.decision_type':{
                        handler(newVal) {
                            console.log(newVal);
                            
                                // تصفية التبويبات بناءً على المجموعة
                                this.kindTabs = this.tabs.filter((t) => (t.group == parseInt(newVal) || t.group == 0)).sort((a, b) => (a.order ) - (b.order ));
                                
                                console.log(this.tabs);
                                
                                // نصيحة: إذا كنت تريد تفريغ الاختيار السابق عند تغيير النوع
                                this.selectedTabs = []; 
                                this.activeTab = null;
            
                        },
                        immediate: true
                    }
                },
                
                methods: {
                    showChooseTabModal() {

                        this.chooseTabModal = true;
                    },
                    hideChooseTabModal() {
                        this.chooseTabModal = false;
                        this.selectedTabs = this.selectedTabs.sort((a, b) => a.order - b.order);
                        this.selectedTabs.forEach(t => {
                            if (t.value === undefined)   t.value = "";
                        });
                    },
                    moveToActive() {
                        tab = this.tempTabSelectedAvailable;
                        if (!this.selectedTabs.find(t => t.code === tab.code)) {
                            this.selectedTabs.push(tab);
                            // // تهيئة مكان النص في الفورم إذا لم يكن موجوداً
                            // if (!this.form.content[tab.code]) {
                            //     this.form.content[tab.tab_id] = '';
                            // }
                            // this.currentView = tab.tab_id;
                            // إزالتها من القائمة المتاحة (اختياري)
                            // this.availableTabs = this.availableTabs.filter(t => t.tab_id !== tab.tab_id);
                        }
                    },
                    removeFromActive() {
                        tab = this.tempTabSelectedAvailable;
                        this.selectedTabs = this.selectedTabs.filter(t => t.code !== tab.code);
                        // if (this.currentView === tab.tab_id) {
                        //     this.currentView = this.selectedTabs.length > 0 ? this.selectedTabs[0].tab_id : '';
                        // }
                    },
                    moveUp(index) {
                        if (index > 0) {
                            // تبديل القاضي مع الذي قبله في المصفوفة
                            const element = this.vjudges.splice(index, 1)[0];
                            this.vjudges.splice(index - 1, 0, element);
                            this.updateOrder();
                        }
                    },
                    moveDown(index) {
                        if (index < this.vjudges.length - 1) {
                            // تبديل القاضي مع الذي بعده
                            const element = this.vjudges.splice(index, 1)[0];
                            this.vjudges.splice(index + 1, 0, element);
                            this.updateOrder();
                        }
                    },
                    updateOrder() {
                        // تحديث قيمة j_order لتطابق الترتيب الجديد
                        this.vjudges.forEach((judge, idx) => {
                            judge.j_order = idx + 1;
                        });
                    },
                    handleSeparator(currentIndex) {
                        const currentJudge = this.vjudges[currentIndex];

                        if (currentJudge.j_serperator === 1) {
                            // 1. التأكد من إلغاء خيار "مخالف" لنفس القاضي (لأن القاضي لا يمكن أن يكون فاصلاً ومخالفاً معاً)
                            currentJudge.j_oppsoit = 0;

                            // 2. الحلقة الأهم: تصفير خيار "فصل" لجميع القضاة الآخرين
                            this.vjudges.forEach((judge, index) => {
                                if (index !== currentIndex) {
                                    judge.j_serperator = 0;
                                }
                            });
                        }
                    },
                    async vcourtChoosed(vcourt) {
                        this.loading = true;
                        try {
                            const response = await axios.get('/copy/vcourt/fetch', { params: { "vcourt": vcourt } });

                            // فحص وجود القرارات
                            if (!response.data.cfD || response.data.cfD.length <= 0) {
                                alert("عذراً، لا توجد قرارات في هذه المحكمة حالياً.");
                                
                                // بدلاً من location.reload() نقوم بتصفير الاختيار يدوياً
                                this.selectedVCourt = null; 
                                this.vjudges = [];
                                this.tabs = [];
                                this.kindTabs = [];
                                this.selectedTabs = [];
                                
                                return; // اخرج من الدالة فوراً
                            }

                            // إذا وجد قرارات يكمل الكود طبيعي
                            this.cfd = response.data.cfD;
                            this.tabs = response.data.tabs;
                            this.setCFDecRes(this.cfd);
                            this.kindTabs = this.tabs.filter((t) => t.group == this.descionD.decision_type)
                                                    .sort((a, b) => a.order - b.order);

                            if (!this.vjudges || !(this.vjudges.length > 0)) 
                            this.vjudges = response.data.judges.map((judge, index) => {
                                    return {
                                        ...judge,
                                        j_serperator: '',
                                        j_desc: 'مستشارا',
                                        j_order: index + 1, // يبدأ من 1 بدلاً من 0 ليكون منطقياً للمستخدم
                                        j_oppsoit: '' 
                                    };
                            })
                            
                            console.log("تم حجز القرار رقم: " + this.descionD.decision_number);

                        } catch (error) {
                            console.error("خطأ:", error);
                            if (error.response && error.response.status === 423) {
                                alert("النظام مشغول حالياً بمعالجة طلب آخر، يرجى المحاولة بعد ثوانٍ.");
                            } else {
                                alert("حدث خطأ أثناء جلب البيانات.");
                            }
                            this.selectedVCourt = null;
                        } finally {
                            this.loading = false;
                        }
                    },
                    // saveCFDecision() {
                    //     const data = {
                    //         cf_code: this.cfd.cfile.code,
                    //         number: this.dec.decision_number,
                    //         date: this.dec.decision_date,
                    //         user_id: this.user.id,
                    //         type: this.dec.decision_type,
                    //         selectedTabs: this.selectedTabs
                    //     };
                    //     axios.post('/decisions', data).then(() => {
                    //         alert('تم الحفظ بنجاح');
                    //     }).catch(error => {
                    //         console.error('خطأ في الحفظ:', error);
                    //         alert('حدث خطأ أثناء الحفظ');
                    //     });
                    // },
                    async saveDraft() {

                        this.errors = {}; // تفريغ الأخطاء السابقة
                        this.loading = true;
                        try {
                                   // هنا نستخدم Axios لإرسال البيانات إلى Redis أو Database
                            const response = await axios.post('/copy/save/draft', this.cfdReq);
                            console.log(response.data);
                            alert(response.data.message);

                        } catch (error) {
                        
                            if (error.response && error.response.status === 422) {
                                // هنا السر: نقوم بجلب مصفوفة الأخطاء من استجابة السيرفر
                                this.errors = error.response.data.errors;
                                alert("تعذر حفظ بيانات القرار، يرجى المحاولة مرة أخرى.");
                                console.log(error);
                            }
                            else {
                                    alert("حدث خطأ غير متوقع في السيرفر");
                                }
                        }
                        finally {
                            this.loading = false;
                        }
                    },
                    async autoSaveDraft() {
                        try {
                            // نرسل الـ cfd الذي قمنا بتطويره والذي يجمع (cfile + dec + vjudges + tabs)
                            await axios.post('/copy/save/temp-draft', this.cfdReq);

                        } catch (error) {
                            console.error("فشل الحفظ التلقائي، ربما انقطع الاتصال.");
                        }
                    },
                    printDecision() {
                        // ترتيب التبويبات حسب 'order' قبل الطباعة
                        this.selectedTabs.sort((a, b) => a.order - b.order);
                        
                        // تشغيل أمر طباعة المتصفح
                        setTimeout(() => {
                            window.print();
                        }, 500);
                    },
                    setCFDecRes(cfd){
                        this.descionD.decision_number = cfd.descionD.decision_number;
                        this.descionD.decision_date = cfd.descionD.decision_date;
                        if ( cfd.descionD.decision_type )  this.descionD.decision_type = cfd.descionD.decision_type;
                        this.cfile.number = cfd.cfile.number;
                        this.cfile.c_begin_n = cfd.cfile.c_begin_n;
                        this.cfile.c_date = cfd.cfile.c_date ? cfd.cfile.c_date.split(' ')[0] : '';
                        this.cfile.c_start_year = cfd.cfile.c_start_year;
                        this.selectedVCourt = cfd.cfile.v_corte;
                        if ( cfd.descionD.vjudges )  this.vjudges = cfd.descionD.vjudges;
                        if ( cfd.descionD.selectedTabs )  this.selectedTabs = cfd.descionD.selectedTabs;
                    },
                    async fetchRelatedData() {
                        this.loading = true; // ابدأ التحميل
                        try {
                            const res = await axios.get('/get-data');
                            this.user = res.data.user;
                            this.userVCourts = res.data.userVCourts;
                            this.userCourtName = res.data.userCourtName || 'غير محدد';

                            this.states = res.data.states ;
                            this.rooms = res.data.rooms.sort((a, b) => {
                                                            return a.code - b.code; // ترتيب تصاعدي حسب الكود
                                                        }); 
                            this.courts = res.data.courts ;
                            this.decisionTypes = res.data.decisionTypes ;

                            
                            
                            if (res.data.cfD) {
                                this.cfd = res.data.cfD
                                this.setCFDecRes(this.cfd)

                                if (!this.vjudges || !(this.vjudges.length > 0)) 
                                this.vjudges = res.data.judges.map((judge, index) => {
                                    return {
                                        ...judge,
                                        j_serperator: '',
                                        j_desc: 'مستشارا',
                                        j_order: index + 1, // يبدأ من 1 بدلاً من 0 ليكون منطقياً للمستخدم
                                        j_oppsoit: '' 
                                    };
                                })
                                console.log(res.data.tabs);
                                
                                this.tabs = res.data.tabs
                                
                            }

                            // إذا كان هناك محكمة مختارة مسبقاً، لا تغلق الـ loading هنا
                            // بل اترك vcourtChoosed تغلقه لاحقاً
                            if (!this.selectedVCourt) {
                                this.loading = false;
                            }
                        } catch (err) {
                            console.error("Error:", err);
                            alert("حدث خطأ في تحميل البيانات");
                            this.loading = false;
                        }
                    }
                },
                mounted() {
                    // إذا كان هناك بيانات سابقة قادمة من Redis، يتم تحميلها هنا
                    console.log("App Ready - Ready to complete decision data");
                    this.fetchRelatedData();


                    setInterval(() => {
                        if (this.cfd && this.selectedVCourt) {
                            this.autoSaveDraft();
                        }
                    }, 15000); 
                }
            }).mount('#decision-editor')
    </script>
@endsection