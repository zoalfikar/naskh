<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserO extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->where('id', 97)->update([
            'password' => bcrypt('1111'),
        ]);

        DB::table('users')->where('id', 182)->update([
            'password' => bcrypt('1111'),
        ]);
        DB::table('users')->where('id', 324)->update([
            'password' => bcrypt('1111'),
        ]);
        DB::table('users')->where('id', 332)->update([
            'password' => bcrypt('1111'),
        ]);
    }
}
