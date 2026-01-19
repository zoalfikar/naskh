<?php

namespace Database\Seeders;

use Illuminate\Container\Attributes\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB as FacadesDB;

class Old_database_migration extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $rooms = FacadesDB::connection('oracle')->table('MOJ_NAKED.G_ROOMS')->get();
        //      foreach ($rooms as $log) {
        //     FacadesDB::connection('mysql')->table('rooms')->insert([
        //         'code' => $log->room_id,
        //         'name' => $log->room_desc,
        //     ]);
        // };

        // $states = FacadesDB::connection('oracle')->table('MOJ_NAKED.G_STATECODES')->get();
        //      foreach ($states as $log) {
        //     FacadesDB::connection('mysql')->table('states')->insert([
        //         'code' => $log->s_code,
        //         'name' => $log->description,
        //     ]);
        // };

        // $coutcats = FacadesDB::connection('oracle')->table('MOJ_NAKED.G_COURT_CAT')->get();
        //      foreach ($coutcats as $log) {
        //     FacadesDB::connection('mysql')->table('court__cats')->insert([
        //         'code' => $log->catcode,
        //         'state' => $log->statecode,
        //         'description' => $log->description,
        //         'appeal' => $log->istinaf,
        //     ]);
        // };

        // $courts = FacadesDB::connection('oracle')->table('MOJ_NAKED.G_COURTCODE')->get();
        //      foreach ($courts as $log) {
        //     FacadesDB::connection('mysql')->table('courts')->insert([
        //         'code' => $log->courtcode, 
        //         'name' => $log->courtname, 
        //         'cat' => $log->catcode,
        //         'user_id' => $log->userid,
        //         'active' => $log->isused,
        //     ]);
        // };

        // $cases = FacadesDB::connection('oracle')->table('MOJ_NAKED.CFCOURT_AUDIT')->take(10)->get();
        //      foreach ($cases as $log) {
        //     FacadesDB::connection('mysql')->table('c_files')->insert([
        //         'code'=>$log->cfcourtseq,
        //         'v_corte'=>$log->courtcode,
        //         'number'=>$log->cfcourtno,
        //         'subject'=>$log->subject,
        //         'kind'=>$log->groupid,
        //         'c_date'=>$log->cfcourtdate,
        //         'c_begin_n'=>$log->begincourtno,
        //         'c_start_year'=>$log->begincourtyear,
        //         'round_year'=>$log->roundyear,
        //         'degree1_court'=>$log->degree1_courtcode,
        //         'degree1_state'=>$log->degree1_courtstate,
        //         'degree1_room'=>$log->degree1_courtroomcode,
        //         'degree1_number'=>$log->degree1_courtno,
        //         'degree1_year'=>$log->degree1_courtyear,
        //         'degree1_dec_n'=>$log->degree1_decno,
        //         'degree1_dec_d'=>$log->degree1_decdate == 0 ? null : $log->degree1_decdate,
        //         'degree2_court'=>$log->degree2_courtcode,
        //         'degree2_state'=>$log->degree2_courtstate,
        //         'degree2_room'=>$log->degree2_courtroomcode,
        //         'degree2_number'=>$log->degree2_courtno,
        //         'degree2_year'=>$log->degree2_courtyear,
        //         'degree2_dec_n'=>$log->degree2_decno,
        //         'degree2_dec_d'=>$log->degree2_decdate == 0 ? null : $log->degree2_decdate,
        //         'user_id'=>$log->userid,
        //     ]);

            
        // };


        // $cases = FacadesDB::connection('oracle')->table('MOJ_NAKED.DECISION_AUDIT')->take(10)->get();
        //      foreach ($cases as $log) {
        //     FacadesDB::connection('mysql')->table('decisions')->insert([
        //         'cf_code'=>$log->cfcourtseq,
        //         'number'=>$log->decision_no,
        //         'date'=>$log->decision_date ,
        //         'note'=>$log->decision_note,
        //         'user_id'=>$log->userid,
        //         'croup'=>$log->groupid,
        //         'hurry_date'=>$log->hurrydate,
        //         'kind'=>$log->dec_kind,
        //         'hurry'=>$log->is_hurry  ? $log->is_hurry  : null,
        //         'type'=>$log->dec_type,
        //         'hurry_text'=>$log->hurryno,
        //         'opposit_judge'=>$log->opposit_judge,
        //     ]);
        // };
        
        // $dtypes = FacadesDB::connection('oracle')->table('MOJ_NAKED.DECTYPECODE')->get();
        //      foreach ($dtypes as $log) {
        //     FacadesDB::connection('mysql')->table('decision_types')->insert([
        //         'code'=>$log->type_code,
        //         'description'=>$log->type_desc,
        //     ]);
        // };

        // function convertStringToArray($inputText) {
        //     // 1. إزالة الفواصل والمسافات الزائدة من الأطراف
        //     $cleanedText = trim($inputText, " \t\n\r\0\x0B,");

        //     // 2. التحقق ما إذا كان النص فارغاً بعد التنظيف لتجنب مصفوفة تحتوي على عنصر فارغ
        //     if (empty($cleanedText)) {
        //         return [];
        //     }

        //     // 3. تحويل النص إلى مصفوفة
        //     return json_encode(explode(',', $cleanedText));
        // } 
        // $tabs = FacadesDB::connection('oracle')->table('MOJ_NAKED.TABSCODE')->get();
        //      foreach ($tabs as $log) {
        //     FacadesDB::connection('mysql')->table('tabs')->insert([
        //         'code'=>$log->tab_code,
        //         'description'=>$log->tab_desc,
        //         'order'=> $log->tab_order,
        //         'courts'=> convertStringToArray($log->courtcode) ,
        //         'not_printed'=> $log->no_print,
        //         'group'=> $log->tab_group,
        //     ]);
        // };


        // // $tabs = FacadesDB::connection('oracle')->table('MOJ_NAKED.DECISION_TABS_AUDIT')->take(10)->get();
        // //      foreach ($tabs as $log) {
        // //     FacadesDB::connection('mysql')->table('dec_tabs')->insert([
        // //         'tab_code'=>$log->tab_code,
        // //         'tab_desc'=>$log->tab_desc,
        // //         'tab_order'=> $log->tab_order,
        // //         'tab_value'=> $log->tab_value,
        // //         'tab_font_s'=> $log->tab_font_size,
        // //         'cf_code'=> $log->cfcourtseq,
        // //         'descision_n'=> $log->decision_no,
        // //         'descision_d'=> $log->decision_date ? $log->decision_date : null,
        // //         'not_printed'=> $log->no_print,
        // //         'user_id'=> $log->userid,
        // //     ]);
        // // };

        $people = FacadesDB::connection('oracle')->table('MOJ_NAKED.V_PERSONS')->get();
        foreach ($people as $log) {
             FacadesDB::connection('mysql')->table('people')->insert([
                    'code'=>$log->p_code,
                    'national_no'=>0,
                    'name'=>$log->p_name,
                    'role'=>$log->p_type,
                    'notes'=>$log->p_note,
            ]);
        };

        // $vcourtcats = FacadesDB::connection('oracle')->table('MOJ_NAKED.VETOCOURTCAT')->get();
        // foreach ($vcourtcats as $log) {
        //      FacadesDB::connection('mysql')->table('v_court_cats')->insert([
        //             'code'=>$log->catcode,
        //             'name'=>$log->catname,
        //             'user_id'=>$log->userid,
        //             'created_at'=>$log->system_date,
        //     ]);
        // };

        // $vcourts = FacadesDB::connection('oracle')->table('MOJ_NAKED.VETOCOURTCODE')->get();
        // foreach ($vcourts as $log) {
        //      FacadesDB::connection('mysql')->table('v_courts')->insert([
        //             'code'=>$log->courtcode,
        //             'name'=>$log->courtname,
        //             'cat'=>$log->catcode,
        //             'user_id'=>$log->userid,
        //             'correction'=>$log->correction_level,
        //     ]);
        // };

        // $users = FacadesDB::connection('oracle')->table('MOJ_NAKED.USERS')->get();
        // foreach ($users as $log) {
        //     if($log->courtname != null)
        //      FacadesDB::connection('mysql')->table('users')->insert([
        //             'id'=>$log->userid,
        //             'name'=>$log->username,
        //             'role'=>$log->groupid,
        //             'password'=>$log->password,
        //             'state'=>$log->statecode,
        //             'vcourt'=>$log->user_courtcode,
        //             'vcourt_name'=>$log->courtname,
        //             'active'=>$log->isactive,
        //     ]);
        // };
        $jcourt = FacadesDB::connection('oracle')->table('MOJ_NAKED.JUDGECOURT')->get();
        foreach ($jcourt as $log) {
             FacadesDB::connection('mysql')->table('j_vcourts')->insert([
                    'j_code'=>$log->judgecode,
                    'vcourt'=>$log->courtcode,
                    'active'=>$log->isactive,
            ]);
        };
        // $cjs = FacadesDB::connection('oracle')->table('MOJ_NAKED.CFJUDGES')->take(10)->get();
        // foreach ($cjs as $log) {
        //      FacadesDB::connection('mysql')->table('c_f_js')->insert([
        //             'j_code'=>$log->j_code,
        //             'j_name'=>$log->cf_j_name,
        //             'cf_code'=>$log->cfcourtseq,
        //             'j_desc'=>$log->j_desc,
        //             'j_order'=>$log->j_order,
        //             'j_serperator'=>$log->j_separater,
        //             'j_oppsoit'=>$log->j_opposit,
        //             'user_id'=>$log->userid,
        //     ]);
        // };
    }
}
