<div v-if="showJudjes" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50  p-4 rtl">
    
    <div class="card shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col animate__animated animate__zoomIn">
        <div class="bg-grey-600 text-white flex justify-between items-center py-3 px-4">
        <h5 class="flex items-center font-bold text-base">
            <i class="fas fa-gavel ml-2"></i>
            تشكيل الهيئة الحاكمة
        </h5>

        <div class="flex items-center">
            <button
                type="button"
                @click="includeAllJudges"
                class="text-sm font-bold bg-white text-gray-800 px-3 py-1.5 rounded-lg ml-2 hover:bg-gray-100 transition"
            >
                تضمين الكل
            </button>
        </div>
    </div>

        <div class="card-body overflow-y-auto">
            <div class="row">
                <div class="col-md-6 border-start">
                        <h6 class="text-gray-500 border-b pb-2 font-bold text-center">
                            القضاة المتاحون
                        </h6>                    
                        <div class="judges-list border p-2 rounded" style="min-height: 350px; background: #f8f9fa;">
                        <div v-for="judge in availableJudges" 
                             :key="judge.j_code" 
                             class="judge-item p-2 mb-2 bg-white border rounded shadow-sm draggable hover:bg-blue-50 transition-colors cursor-move"
                             draggable="true" 
                             @dragstart="onDragStart($event, judge)">
                            <i class="fas fa-user-tie me-2 text-secondary"></i>
                            @{{ judge.person.name }}
                            <button class="btn btn-sm btn-success py-0 px-2 float-start" @click="addJudge(judge)">+</button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                        <h6 class="text-gray-500 border-b pb-2 font-bold text-center">
                           الهيئة المختارة (بالترتيب)
                        </h6>                      <div class="selected-judges-list border p-2 rounded" 
                         style="min-height: 350px; background: #eef2ff;"
                         @dragover.prevent 
                         @drop="onDrop($event)">
                        
                        <div v-for="(judge, index) in selectedJudges" 
                             :key="judge.j_code" 
                             class="judge-item p-2 mb-2 bg-white border-end border-primary border-4 rounded shadow-sm draggable cursor-move"
                             draggable="true"
                             @dragstart="onDragStartSelected($event, index)"
                             @dragover.prevent
                             @drop="onDropReorder($event, index)">
                            <span class="badge bg-primary ms-2">@{{ index + 1 }}</span>
                            @{{ judge.person.name }}
                            <button class="btn btn-sm btn-danger py-0 px-2 float-start" @click="removeJudge(index)">×</button>
                        </div>

                       
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4 bg-slate-50 border-t border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center text-slate-500 text-sm">
                <i class="fas fa-info-circle me-2 text-blue-500"></i>
                <span>يمكنك سحب الأسماء لترتيب الأقدمية (الرئيس أولاً)</span>
            </div>
            
            <div class="flex gap-3 w-full md:w-auto">
                <button @click="showJudjes=0" 
                        class="flex-1 md:flex-none px-6 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-bold hover:bg-gray-100 transition">
                    حفظ
                </button>
            </div>
        </div>
    </div>
</div>