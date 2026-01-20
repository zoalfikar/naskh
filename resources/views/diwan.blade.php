@extends('main')


@section('title', 'ديوان محكمة النقض')


@section('content')


    <div id="app" v-cloak class="container mx-auto px-4 pb-12">
            <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-[#1a4d4d] to-[#0f2e2e] p-6 text-white text-center">
                    <h2 class="text-2xl font-bold mb-2">بطاقة بيانات قضائية</h2>
                    <p class="text-sm opacity-70">يرجى إدخال بيانات القرار بدقة لضمان صحة الأرشفة</p>
                </div>

                <form class="p-8">
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        
                        <div class="md:col-span-3 border-b pb-2 mb-2">
                            <h3 class="text-[#1a4d4d] font-bold flex items-center gap-2">
                                <span class="w-2 h-6 bg-yellow-500 rounded-full"></span>
                                بيانات المحكمة والنوع
                            </h3>
                        </div>

                        
                        <div class="space-y-2">
                    
                            <label class="block text-sm font-semibold text-gray-700">المحكمة</label>
                            <div v-if="loading" class="text-sm text-gray-500 animate-pulse">
                                جاري تحميل قائمة المحاكم...
                            </div>
                            <select v-else  v-model="form.v_corte" class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#1a4d4d] bg-gray-50 pl-4 appearance-none cursor-pointer">
                                <option value="">-- اختر المحكمة من القائمة --</option>
                                <option v-for="court in courts" :key="court.code" :value="court.code">
                                    @{{ court.catigory.name }}  - @{{ court.name }}
                                </option>
                            </select>
                            <span v-if="errors.v_corte" class="error-text text-red-600 pr-2">
                                @{{ errors.v_corte[0] }}
                            </span>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">طبيعة القرار</label>
                            <div class="flex gap-4 mt-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input checked type="radio"  v-model="form.urgencyType" class="w-4 h-4 text-[#1a4d4d]" value="normal"> <span class="text-sm">عادي</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio"  v-model="form.urgencyType" class="w-4 h-4 text-[#1a4d4d]"  value="urgent"> <span class="text-sm">مستعجل</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio"  v-model="form.urgencyType" class="w-4 h-4 text-[#1a4d4d]" value="other"> <span class="text-sm">متفرق</span>
                                </label>
                            </div>
                        </div>

                        
                        <div class="space-y-2" v-if="urgencyType === 'urgent'">
                            <label class="block text-sm font-semibold text-gray-700">رقم كتاب الاستعجال</label>
                            <input  type="text" class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#1a4d4d] placeholder-gray-400">
                        </div>
                        <div class="space-y-2" v-if="urgencyType === 'urgent'">
                            <label class="block text-sm font-semibold text-gray-700">تاريخ كتاب الاستعجال</label>
                            <input  type="date" class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#1a4d4d]">
                        </div>
                        

                        <div class="md:col-span-3 border-b pb-2 mt-4 mb-2">
                            <h3 class="text-[#1a4d4d] font-bold flex items-center gap-2">
                                <span class="w-2 h-6 bg-yellow-500 rounded-full"></span>
                                تفاصيل القرار المكتتب
                            </h3>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">رقم القرار</label>
                            <input v-model="form.decision_number" type="number" placeholder="" class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#1a4d4d] bg-gray-50">
                            <span v-if="errors.decision_number" class="error-text text-red-600 pr-2">
                                @{{ errors.decision_number[0] }}
                            </span>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">لعام</label>
                            <input v-model="form.c_start_year" type="number"  class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#1a4d4d] bg-gray-50">
                            <span v-if="errors.c_start_year" class="error-text text-red-600 pr-2">
                                @{{ errors.c_start_year[0] }}
                            </span>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">تاريخ القرار</label>
                            <input v-model="form.decision_date" onfocus="(this.type='date')"  onblur="(this.type='text')" placeholder="سنة/شهر/يوم"  class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#1a4d4d]">
                            <span v-if="errors.decision_date" class="error-text text-red-600 pr-2">
                                @{{ errors.decision_date[0] }}
                            </span>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">الرقم الأساسي للدعوى</label>
                            <input v-model="form.number"  type="number" placeholder="" class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#1a4d4d]">
                            <span v-if="errors.number" class="error-text text-red-600 pr-2">
                                @{{ errors.number[0] }}
                            </span>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">مدور لعام </label>
                            <input v-model="form.round_year" type="number"   class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#1a4d4d] bg-gray-50">
                            <span v-if="errors.round_year" class="error-text text-red-600 pr-2">
                                @{{ errors.round_year[0] }}
                            </span>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">تاريخ قيد الدعوى</label>
                            <input v-model="form.c_date" type="date" class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#1a4d4d]">
                            <span v-if="errors.c_date" class="error-text text-red-600 pr-2">
                                @{{ errors.c_date[0] }}
                            </span>
                        </div>

                    </div>

                    <div class="mt-12 flex flex-col md:flex-row gap-4 border-t pt-8">
                        <button @click="saveCFile" class="flex-1 bg-[#1a4d4d] text-white py-4 rounded-xl font-bold hover:bg-[#0f2e2e] shadow-lg hover:shadow-2xl transition-all flex justify-center items-center gap-2">
                            حفظ البيانات في الأرشيف
                        </button>
                        <button type="reset" class="px-8 py-4 bg-gray-100 text-gray-600 rounded-xl font-bold hover:bg-gray-200 transition-all">
                            تفريغ الحقول
                        </button>
                    </div>
                </form>
            </div>
            




        <div class="max-w-6xl mx-auto bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
                
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
        </div>


        <script>


            const app = Vue.createApp({
                data() {
                    return {
                        courts: [],
                        loading: true,
                        loadingDecisions: true,
                        // decisions: [],
                        decisionsForCopy: [],
                        form:{
                            v_corte: '',
                            decision_number: '',
                            c_start_year: '2026',
                            decision_date: '',
                            number: '',
                            round_year: '2026',
                            c_date: '',
                            urgencyType:'normal',
                            hurry_text:null,
                            hurry_date:null
                        },
                        errors: {}
                    }
                },
                methods: {
                
                    async fetchVCourts() {
                        try {
                            const response = await axios.get('/diwan/courts');
                            this.courts =  response.data;
                            this.courts.sort((a, b) => (a.catigory.name + a.name).localeCompare(b.catigory.name + b.name));
                        } catch (error) {
                                console.error("حدث خطأ أثناء جلب البيانات:", error);
                                alert("تعذر تحميل قائمة المحاكم، يرجى تحديث الصفحة.");
                        } finally {
                            this.loading = false;
                        }
                    }
                    ,
                    async saveCFile(e) {
                        e.preventDefault();
                        this.errors = {}; // تفريغ الأخطاء السابقة
                        try {
                            const response = await axios.post('/diwan/save-cfile', this.form);
                            alert("تم حفظ بيانات القرار بنجاح!");
                        } catch (error) {
                        
                          if (error.response && error.response.status === 422) {
                            // هنا السر: نقوم بجلب مصفوفة الأخطاء من استجابة السيرفر
                            this.errors = error.response.data.errors;
                          }
                            alert("تعذر حفظ بيانات القرار، يرجى المحاولة مرة أخرى.");
                            console.log(error);
                        }
                    },
                    async initBringActiveDecisions() {
                        this.loadingDecisions = true;
                        try {
                            const response = await axios.get('/diwan/active-decisions');
                            console.log(response.data);

                            this.decisionsForCopy =  response.data.decisionsForCopy;
                            this.decisionsForCopy =this.decisionsForCopy.sort( (a, b) => { return a.descionD.decision_number - b.descionD.decision_number;  });
                            this.loadingDecisions = false;
                        } catch (error) {
                        
                            alert("تعذر جلب القرارات النشطة.");
                            console.log(error);

                        }finally {
                            this.loadingDecisions = false;
                        }
                    },
                    
                    async bringActiveDecisions() {
                        try {
                            const response = await axios.get('/diwan/active-decisions');
                            
                            this.decisionsForCopy =  response.data.decisionsForCopy;
                            this.decisionsForCopy =this.decisionsForCopy.sort( (a, b) => { return a.descionD.decision_number - b.descionD.decision_number; });
                            this.loadingDecisions = false;
                        } catch (error) {
                        
                            alert("تعذر جلب القرارات النشطة.");
                            console.log(error);

                        }
                    },
                    getCourt(courtCode) {
                        // ابحث في مصفوفة المحاكم عن المحكمة التي تملك هذا الكود
                        const court = this.courts.find(c => c.code === courtCode);
                        return court ? court : 'غير معرف';
                    }
                    
                },
                mounted() {
                    this.fetchVCourts();
                    this.initBringActiveDecisions();

                    // 2. إنشاء الاتصال مباشرة عبر مكتبة Pusher
                    var pusher = new Pusher('{{ env("REVERB_APP_KEY") }}', {
                                        wsHost: window.location.hostname,
                                        wsPort: 8080,
                                        forceTLS: false,
                                        enabledTransports: ['ws', 'wss'],
                                        cluster: 'mt1' // قيمة وهمية مطلوبة للمكتبة فقط
                                    });

                    var channel = pusher.subscribe('desicions');
                    
                    channel.bind('NewDecisionEvent', (data) => {
                        console.log('وصلت البيانات أخيراً:', data);
                        // alert('وصل قرار جديد: ' + JSON.stringify(data));
                        this.bringActiveDecisions();
                    });
                }
            });

            app.mount('#app');


            // const app = Vue.createApp({
            //     data() {
            //         return {
            //             showLiveTable: true,
            //             courts: [],
            //             loading: true,
            //             loadingDecisions: true,
            //             decisionsForCopy: [],
            //             form: {
            //                 v_corte: '',
            //                 decision_number: '',
            //                 c_start_year: '2026',
            //                 decision_date: '',
            //                 number: '',
            //                 round_year: '2026',
            //                 c_date: '',
            //             },
            //             errors: {}
            //         }
            //     },
            //     computed: {
            //         // فرز المحاكم تلقائياً
            //         sortedCourts() {
            //             return [...this.courts].sort((a, b) => 
            //                 (a.catigory.name + a.name).localeCompare(b.catigory.name + b.name)
            //             );
            //         },
            //         // فرز القرارات تلقائياً حسب الرقم
            //         sortedDecisions() {
            //             return [...this.decisionsForCopy].sort((a, b) => 
            //                 a.descionD.decision_number - b.descionD.decision_number
            //             );
            //         }
            //     },
            //     methods: {
            //         async fetchVCourts() {
            //             try {
            //                 const response = await axios.get('/diwan/courts');
            //                 this.courts = response.data;
            //             } catch (error) {
            //                 console.error("خطأ في جلب المحاكم:", error);
            //             } finally {
            //                 this.loading = false;
            //             }
            //         },

            //         async saveCFile(e) {
            //             e.preventDefault();
            //             this.errors = {}; 
            //             try {
            //                 const response = await axios.post('/diwan/save-cfile', this.form);
            //                 alert("✅ تم حفظ بيانات القرار بنجاح!");
            //                 // إعادة تصفية النموذج بعد الحفظ الناجح (اختياري)
            //                 this.resetForm();
            //             } catch (error) {
            //                 if (error.response && error.response.status === 422) {
            //                     this.errors = error.response.data.errors;
            //                 } else {
            //                     alert("❌ تعذر حفظ بيانات القرار.");
            //                 }
            //             }
            //         },

            //         async fetchActiveDecisions(showLoader = true) {
            //             if (showLoader) this.loadingDecisions = true;
            //             try {
            //                 const response = await axios.get('/diwan/active-decisions');
            //                 this.decisionsForCopy = response.data.decisionsForCopy;
            //             } catch (error) {
            //                 console.error("خطأ في جلب القرارات:", error);
            //             } finally {
            //                 this.loadingDecisions = false;
            //             }
            //         },

            //         getCourt(courtCode) {
            //             const court = this.courts.find(c => c.code === courtCode);
            //             return court ? court : { name: 'غير معرف', catigory: { name: '' } };
            //         },

            //         resetForm() {
            //             this.form.decision_number = '';
            //             this.form.number = '';
            //             this.form.decision_date = '';
            //             this.errors = {};
            //         }
            //     },
            //     mounted() {
            //         this.fetchVCourts();
            //         this.fetchActiveDecisions();

            //         // إعداد Real-time Connection
            //         const pusher = new Pusher('{{ env("REVERB_APP_KEY") }}', {
            //             wsHost: window.location.hostname,
            //             wsPort: 8080,
            //             forceTLS: false,
            //             enabledTransports: ['ws', 'wss'],
            //             cluster: 'mt1'
            //         });

            //         const channel = pusher.subscribe('desicions');
            //         channel.bind('NewDecisionEvent', (data) => {
            //             console.log('تنبيه: قرار جديد وصل', data);
            //             // تحديث القائمة بدون إظهار مؤشر التحميل المزعج
            //             this.fetchActiveDecisions(false); 
                        
            //             // إشعار بسيط بدلاً من alert المزعج (يمكنك استخدام Toast)
            //             console.log('New decision added');
            //         });
            //     }
            // });
            // app.mount('#app');
                        
        </script>
@endsection