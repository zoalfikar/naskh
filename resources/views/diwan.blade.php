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
                                    @{{ court.catigory.name }}  - @{{ court.name }} @{{ court.code }}
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

                        
                        <div class="space-y-2" v-if="form.urgencyType === 'urgent'">
                            <label class="block text-sm font-semibold text-gray-700">رقم كتاب الاستعجال</label>
                            <input  type="text" class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#1a4d4d] placeholder-gray-400">
                        </div>
                        <div class="space-y-2" v-if="form.urgencyType === 'urgent'">
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
                            <input v-model="form.c_date" onfocus="(this.type='date')"  onblur="(this.type='text')" placeholder="سنة/شهر/يوم" class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#1a4d4d]">
                            <span v-if="errors.c_date" class="error-text text-red-600 pr-2">
                                @{{ errors.c_date[0] }}
                            </span>
                        </div>

                    </div>

                    <div class="mt-12 flex flex-col md:flex-row gap-4 border-t pt-8">
                        <button @click="saveCFile" class="flex-1 bg-[#1a4d4d] text-white py-4 rounded-xl font-bold hover:bg-[#0f2e2e] shadow-lg hover:shadow-2xl transition-all flex justify-center items-center gap-2">
                            حفظ البيانات في الأرشيف
                        </button>
                        <button type="button" @click="toggleRadar" 
                                :class="showRadar ? 'bg-slate-900' : 'bg-slate-800'"
                                class="flex-1 text-white py-4 rounded-xl font-bold shadow-lg transition-all flex justify-center items-center gap-3 relative overflow-hidden">
                            
                            {{-- <span v-if="showRadar" class="absolute inset-0 bg-white/20 animate-pulse"></span> --}}
                            
                            <span>@{{ showRadar ? ' إخفاء' : 'عرض القرارات الحاليه' }}</span>
                        </button>
                        <button type="reset" class="px-8 py-4 bg-gray-100 text-gray-600 rounded-xl font-bold hover:bg-gray-200 transition-all">
                            تفريغ الحقول
                        </button>
                    </div>
                </form>
            </div>
            


            @include("diwan.live")


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
                        errors: {},
                        showRadar: false, // الرادار مخفي عند فتح الصفحة
                        pusherStatus: 'connecting',
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
                    },
                    toggleRadar() {
                        this.showRadar = !this.showRadar;
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
                                        enabledTransports: ['ws'],
                                        cluster: 'mt1' // قيمة وهمية مطلوبة للمكتبة فقط
                                    });


                    // 2. مراقبة حالة الاتصال (هذا الجزء الجديد)
                    pusher.connection.bind('state_change', (states) => {
                        // states.current ستكون (connected, connecting, disconnected, unavailable)
                        this.pusherStatus = states.current;
                        console.log('حالة الاتصال الحالية:', states.current);
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
    </div>
@endsection