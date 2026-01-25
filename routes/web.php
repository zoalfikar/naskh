<?php

use App\Events\NewDecision;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\CopyController;
use App\Http\Controllers\DiwanController;
use App\Http\Controllers\ReviewerController;
use Illuminate\Support\Facades\Route;
use App\Models\CFile;
use App\Models\JVcourt;
use App\Models\Tabs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

Route::middleware('guest')->group(function () {
Route::get('/', function () {
    return view('welcome');
});
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});



Route::get('/test',  function () {
        // $data=[
        //     'v_corte'=>43,
        //     'number'=>1234,
        //     'round_year'=>2024,
        //     'c_date'=>'2024-01-01',
        //     'c_start_year'=>2024,
        //     'user_id'=>1,
        //  ];

        //           // تخزين البيانات بدون وقت انتهاء (Persistent)
        //  Redis::set("test", json_encode($data ));
        //     return CFile::create($data) ;;df

    //     $data = ['message' => 'هذا اختبار بث فوري', 'id' => 123];
    
    // // استخدام broadcast() يرسل الحدث للريل تايم
    // broadcast(new NewDecision($data));

    // return "تم الإرسال لـ Reverb!";

    return view('copier');
    
})->name('test');

Route::get('/test1',  function () {
       

    $r= Redis::get("23::copy::52::54::2026");
    // return view('copier1');
    return $r;
    
});

Route::get('/test4', CopyController::class . '@fetchCopierData');




Route::get('/test2',  function () {
        // $data=[
        //     'v_corte'=>43,
        //     'number'=>1234,
        //     'round_year'=>2024,
        //     'c_date'=>'2024-01-01',
        //     'c_start_year'=>2024,
        //     'user_id'=>1,
        //  ];

        //           // تخزين البيانات بدون وقت انتهاء (Persistent)
        //  Redis::set("test", json_encode($data ));
        //     return CFile::create($data) ;;

    //     $data = ['message' => 'هذا اختبار بث فوري', 'id' => 123];
    
    // // استخدام broadcast() يرسل الحدث للريل تايم
    // broadcast(new NewDecision($data));

    // return "تم الإرسال لـ Reverb!";

    // return view('copier1');
    // return view('c2');

        return $allowedTabs = Tabs::whereJsonContains('courts', "46")->get();
    
});



// Route::get('/test7',  function () {

// // return $cases = DB::connection('oracle')->table('MOJ_NAKED.CFCOURT_AUDIT')->where('CFCOURTNO',1148 )->take(10)->get(); 



// // return $cases = DB::connection('oracle')->table('MOJ_NAKED.CFCOURT_AUDIT')->where('CFCOURTNO',1148 )->where('BEGINCOURTYEAR',2025)->take(10)->get(); 
//  $cases = DB::connection('oracle')->table('MOJ_NAKED.CFCOURT_AUDIT')->where('CFCOURTNO',1148 )->where('BEGINCOURTYEAR',2025)->where('COURTCODE',28 )->get(); 
//  $d = DB::connection('oracle')->table('MOJ_NAKED.DECISION_AUDIT')->where('CFCOURTSEQ',$cases[0]->cfcourtseq )->get(); 
//  $t = DB::connection('oracle')->table('MOJ_NAKED.DECISION_TABS_AUDIT')->where('CFCOURTSEQ',$cases[0]->cfcourtseq )->get(); 


// //   $cases = DB::connection('oracle')->table('MOJ_NAKED.CFCOURT_AUDIT')->where('CFCOURTNO',424 )->where('COURTCODE',44)->get(); 
// //  $d = DB::connection('oracle')->table('MOJ_NAKED.DECISION_AUDIT')->where('CFCOURTSEQ',$cases[0]->cfcourtseq )->get(); 
// //  $t = DB::connection('oracle')->table('MOJ_NAKED.DECISION_TABS_AUDIT')->where('CFCOURTSEQ',$cases[0]->cfcourtseq )->get(); 
// //  dd($cases);
// dd([$cases->toArray() ,  $d->toArray() ,$t->toArray()]);


// });

Route::get('/test6',  function () {
//         $data=[
//             'v_corte'=>43,
//             'number'=>1234,
//             'round_year'=>2024,
//             'c_date'=>'2024-01-01',
//             'c_start_year'=>2024,
//             'user_id'=>1,
//          ];
// $c = CFile::create($data);
        $judges = JVcourt::where(['vcourt' =>33 , 'active'=>1])->with('person')->get();
    
    // هذا السطر هو السحر: سيجلب الـ code الذي وضعه التريجر
    return      $judges;


});
// $cc = CFile::where('code',1)->get();

    // Route::get('/test5',  [CopyController::class, 'copyVCFetchData'])->name('get-data');
    // Route::get('/test9',  [ReviewerController::class, 'show']);


Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::middleware('diwan')->group(function () {

        // مسار افتراضي للمستخدم ديوان
        Route::get('/diwan_p',  [DiwanController::class, 'show'])->name('diwan_p');
        Route::get('/diwan/courts',  [DiwanController::class, 'getVCs'])->name('vcourts');
        Route::post('/diwan/save-cfile',  [DiwanController::class, 'saveCFile'])->name('saveCFile');
        Route::get('/diwan/active-decisions',  [DiwanController::class, 'getActiveDecisions'])->name('getActiveDecisions');
    });


        Route::middleware('copier')->group(function () {

            Route::get('/copy_p',  [CopyController::class, 'show'])->name('copy_p');
            Route::get('/get-data',  [CopyController::class, 'fetchCopierData'])->name('get-data');
            Route::get('/copy/vcourt/fetch',  [CopyController::class, 'copyVCFetchData'])->name('get-data-vcf');
            Route::post('/copy/save/draft',  [CopyController::class, 'saveDraft'])->name('save-draft');
            Route::post('/copy/save/temp-draft',  [CopyController::class, 'saveTempDraft']);
            
        });

        Route::middleware('reviewer')->group(function () {

            Route::get('/review_p', [ ReviewerController::class, 'show'])->name('review_p');
            Route::get('/get-data-review',  [ReviewerController::class, 'fetchReviewerData']);
            Route::get('/review/vcourt/fetch',  [ReviewerController::class, 'reviewVCFetchData']);
            Route::post('/review/return/draft',  [ReviewerController::class, 'returnDecision']);
            // Route::post('/copy/save/temp-draft',  [CopyController::class, 'saveTempDraft']);
            
        });


    // API routes for courts
    // Route::resource('courts', CourtController::class);
    // Route::resource('c-files', CFileController::class);
});
