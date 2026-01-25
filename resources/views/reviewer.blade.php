@extends('main')


{{-- @section('title', 'ديوان محكمة النقض') --}}


@section('content')


    <div id="app" v-cloak class="container mx-auto px-4 pb-12">
        @{{ currentVCName }}
        <div v-if="!selectedVCourt" class="flex items-center justify-center min-h-screen bg-slate-100 no-print">
            <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md border-t-4 border-blue-600">
                <h2 class="text-2xl font-bold text-slate-800 mb-6 text-center">مدقق القرارات القضائية</h2>
                <label class="block text-sm font-medium text-gray-700 mb-2">يرجى اختيار المحكمة للبدء بالتدقيق:</label>
                <div v-if="loading" class="text-sm text-gray-500 animate-pulse">
                    جاري تحميل قائمة المحاكم...
                </div>
                <select v-else v-model="selectedVCourt"   :disabled="selectedVCourt"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    <option :value="null" disabled>اختر المحكمة...</option>
                    <option v-for="court in userVCourts" :key="court.vcourt" :value="court.vcourt">
                        @{{ court.vcourt_name }} @{{ court.vcourt }}
                    </option>
                </select>
                <p class="mt-4 text-xs text-gray-500 text-center italic">سيتم جلب المسودات المحجوزة لهذه المحكمة فقط.</p>
            </div>
        </div>

        {{-- <div v-else>
            <div class="fixed top-0 left-0 right-0 bg-slate-800 text-white p-3 z-50 shadow-md no-print flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <button @click="selectedVCourt = null" class="text-gray-400 hover:text-white">
                        <i class="fas fa-arrow-right"></i> تغيير المحكمة
                    </button>
                    <div class="h-8 w-px bg-slate-600"></div>
                    <span class="font-bold">@{{ currentVCName }}</span>
                </div>
                
                <select v-model="activeKey" @change="loadSpecificDraft" class="bg-slate-700 text-white text-sm rounded px-3 py-1 border-none focus:ring-1 focus:ring-blue-400">
                    <option :value="null">-- اختر مسودة للتدقيق (@{{ courtDrafts.length }}) --</option>
                    <option v-for="d in courtDrafts" :value="d.key">قرار رقم: @{{ d.decision_number }}</option>
                </select>

                <div class="flex gap-2">
                    <button @click="rejectBackToCopier" class="bg-red-500 px-4 py-1 rounded text-sm hover:bg-red-600">إعادة للناسخ</button>
                    <button @click="finalizeAndSave" class="bg-green-600 px-4 py-1 rounded text-sm font-bold hover:bg-green-700">اعتماد نهائي</button>
                </div>
            </div>

            <div id="print-layout" class="pt-20">
            </div>
        </div> --}}

        <div v-else v-if="!loading">

            <div  class="fixed top-0 left-0 right-0 bg-slate-800 text-white p-4 z-50 shadow-2xl no-print">
                <div class="container mx-auto flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <h2 class="text-xl font-bold border-l-2 pl-4 ml-2">لوحة التدقيق والاعتماد</h2>
                        <div class="text-xs">
                            <span class="block">ملف رقم: @{{ cfile.number }}</span>
                            <span class="block text-blue-300">قرار رقم: @{{ descionD.decision_number }} / @{{ cfile.c_start_year }}</span>
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <input v-model="descionD.reviewer_note" type="text" 
                            placeholder="اكتب ملاحظة للناسخ في حال الرفض..." 
                            class="bg-slate-700 border-none rounded px-4 py-2 text-sm w-80 focus:ring-2 focus:ring-yellow-500">
                        
                        <button @click="rejectBackToCopier" class="bg-red-600 hover:bg-red-700 px-6 py-2 rounded font-bold transition">
                            <i class="fas fa-undo ml-1"></i> إعادة للناسخ
                        </button>
                        
                        <button @click="finalizeAndSave" class="bg-green-600 hover:bg-green-700 px-6 py-2 rounded font-bold shadow-lg transition">
                            <i class="fas fa-check-double ml-1"></i> اعتماد وحفظ نهائي
                        </button>
                    </div>
                </div>
            </div>

            <div id="decision-paper" class="pt-24 pb-12 bg-gray-200 min-h-screen flex justify-center">
                <div class="bg-white shadow-2xl p-12 w-[210mm] min-h-[297mm] relative overflow-hidden transition-all duration-300"
                    :class="{'opacity-50 pointer-events-none': loading}">
                    
                    <div class="flex justify-between items-start mb-8 border-b-2 border-black pb-4">
                        <div class="text-center">
                            <h1 class="text-xl font-bold">الجمهورية العربية السورية</h1>
                            <h2 class="text-lg">مجلس القضاء الأعلى</h2>
                            <h3 class="text-md font-semibold">محكمة النقض</h3>
                        </div>
                        <div class="text-center">
                            <img src="{{ asset('images/logo.png') }}" class="w-20 h-20 mx-auto">
                        </div>
                        <div class="text-right text-sm">
                            <p>الرقم: @{{ descionD.decision_number }}</p>
                            <p>التاريخ: @{{ descionD.decision_date }}</p>
                            <p>الغرفة: @{{ currentVCName }}</p>
                        </div>
                    </div>

                    <div class="content-area">
                        <div v-for="tab in tabs" :key="tab.code" class="mb-6">
                            <h4 class="font-bold text-lg mb-2 underline">@{{ tab. description }}:</h4>
                            <textarea v-model="tab.value" 
                                    class="w-full border-none focus:ring-1 focus:ring-blue-200 p-2 text-justify leading-relaxed text-md resize-none"
                                    rows="4"></textarea>
                        </div>
                    </div>

                    <div class="mt-12 pt-8 border-t border-dashed border-gray-300">
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div v-for="judge in vjudges" :key="judge.id" class="flex flex-col">
                                <span class="font-bold">@{{ judge.j_desc }}</span>
                                <span>القاضي @{{ judge.name }}</span>
                                <span v-if="judge.j_oppsoit == 1" class="text-red-600 font-bold text-xs">(مخالف)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


        <script>


            const app = Vue.createApp({
                data() {
                    return {
                        loading:false,
                        userCourtName: '',
                        selectedVCourt:null,
                        userVCourts:'' ,
                        vjudges:[],
                        tabs:[],
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
                            decision_type: 0,
                            decision_number: '',
                            decision_date: '',
                            higry_date:null
                        }
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
                    
                },
                methods: {
                    async rejectBackToCopier() {
                        this.loading = true;
                        try {
                            const response = await axios.post('/review/return/draft', {  { "cfile": this.cfile , "descionD" : this.descionD } });
                            alert(message);

                            alert("عذراً، لا توجد قرارات في هذه المحكمة حالياً.");
                            
                            // بدلاً من location.reload() نقوم بتصفير الاختيار يدوياً
                            this.selectedVCourt = null; 
                            this.vjudges = [];
                            this.tabs = [];
                        } 
                        catch (error) {
                            console.error("خطأ:", error);
                            alert("حدث خطأ أثناء  المعالحه.");
                        } finally {
                            this.loading = false;
                        }
                    },
                    async vcourtChoosed(vcourt) {
                        if (!vcourt)  return
                        this.loading = true;
                        try {
                            const response = await axios.get('/review/vcourt/fetch', { params: { "vcourt": vcourt } });

                            // فحص وجود القرارات
                            if (!response.data.cfD || response.data.cfD.length <= 0) {
                                alert("عذراً، لا توجد قرارات في هذه المحكمة حالياً.");
                                
                                // بدلاً من location.reload() نقوم بتصفير الاختيار يدوياً
                                this.selectedVCourt = null; 
                                this.vjudges = [];
                                this.tabs = [];
                                return; // اخرج من الدالة فوراً
                            }

                            // إذا وجد قرارات يكمل الكود طبيعي
                            this.cfd = response.data.cfD;
                            this.cfile = response.data.cfD.cfile;
                            this.descionD = response.data.cfD.descionD;
                            this.tabs = response.data.cfD.descionD.tabs;
                            if (!this.vjudges || !(this.vjudges.length > 0)) 
                            this.vjudges = response.data.cfD.descionD.vjudges;
                            
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
                    async fetchRelatedData() {
                        this.loading = true; // ابدأ التحميل
                        try {
                            const res = await axios.get('/get-data-review');
                            this.user = res.data.user;
                            this.userVCourts = res.data.userVCourts;
                            this.userCourtName = res.data.userCourtName || 'غير محدد';

                           
                            
                            // من اجل التحديث من غير قصد
                            if (res.data.cfD) {
                                this.cfd = res.data.cfD;
                                this.cfile = res.data.cfD.cfile;
                                this.descionD = res.data.cfD.descionD;
                                this.tabs = res.data.cfD.descionD.tabs;
                                if (!this.vjudges || !(this.vjudges.length > 0)) 
                                this.vjudges = res.data.cfD.descionD.vjudges;
                                
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
                    console.log("App Ready - Ready to review decision data");
                    this.fetchRelatedData();
                }
            });

            app.mount('#app');


                        
        </script>
@endsection