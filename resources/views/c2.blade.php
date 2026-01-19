@extends('main')

@section('content')
<div id="app" class="max-w-7xl mx-auto p-6 space-y-6">

  <!-- بيانات القرار -->
  <div class="bg-white p-4 rounded shadow">
    <h2 class="font-bold mb-4">بيانات القرار</h2>

    <div class="grid grid-cols-4 gap-4">
      <input v-model="form.decision_number" placeholder="رقم القرار" class="input">
      <input type="date" v-model="form.decision_date" class="input">
      <select v-model="form.decision_type" class="input">
        <option value="">نوع القرار</option>
        <option>عادي</option>
        <option>مبرم</option>
      </select>
      <input v-model="form.judge" placeholder="القاضي" class="input">
    </div>
  </div>

  <!-- بيانات المحاكم -->
  <div class="grid grid-cols-3 gap-4">
    <div v-for="level in courtLevels" class="bg-gray-50 p-4 rounded shadow">
      <h3 class="font-bold mb-2">@{{ level.label }}</h3>
      <input v-model="level.base" placeholder="رقم الأساس" class="input mb-2">
      <input v-model="level.number" placeholder="رقم القرار" class="input mb-2">
      <input type="date" v-model="level.date" class="input">
    </div>
  </div>

  <!-- تابات القرار -->
  <div class="bg-white rounded shadow">
    <div class="flex border-b">
      <button
        v-for="tab in tabs"
        @click="activeTab = tab.key"
        class="px-4 py-2"
        :class="activeTab === tab.key ? 'border-b-2 border-blue-600 font-bold' : ''"
      >
        @{{ tab.title }}
      </button>
    </div>

    <textarea
      v-model="form.sections[activeTab]"
      class="w-full h-64 p-4 border"
    ></textarea>
  </div>

  <!-- حفظ -->
  <div class="text-right">
    <button
      @click="saveDecision"
      class="bg-blue-600 text-white px-6 py-2 rounded"
    >
      حفظ القرار
    </button>
  </div>
  <script>
const { createApp } = Vue

createApp({
  data() {
    return {
      activeTab: 'intro',

      tabs: [
        { key: 'intro', title: 'المقدمة' },
        { key: 'facts', title: 'الوقائع' },
        { key: 'reasons', title: 'الأسباب' },
        { key: 'verdict', title: 'المنطوق' }
      ],

      courtLevels: [
        { key: 'first', label: 'الدرجة الأولى', base: '', number: '', date: '' },
        { key: 'second', label: 'الدرجة الثانية', base: '', number: '', date: '' },
        { key: 'cassation', label: 'محكمة النقض', base: '', number: '', date: '' }
      ],

      form: {
        decision_number: '',
        decision_date: '',
        decision_type: '',
        judge: '',
        sections: {
          intro: '',
          facts: '',
          reasons: '',
          verdict: ''
        }
      }
    }
  },

  methods: {
    saveDecision() {
      axios.post('/decisions', {
        ...this.form,
        courts: this.courtLevels
      }).then(() => {
        alert('تم الحفظ بنجاح')
      })
    }
  }
}).mount('#app')
</script>

</div>
@endsection('content')