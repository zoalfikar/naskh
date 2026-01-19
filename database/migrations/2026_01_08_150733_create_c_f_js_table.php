<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('c_f_js', function (Blueprint $table) {
            $table->integer('j_code');
            $table->string('j_name');
            $table->integer('cf_code');
            $table->string('j_desc');
            $table->tinyInteger('j_order');
            $table->tinyInteger('j_serperator')->default(0);
            $table->tinyInteger('j_oppsoit')->default(0);
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('c_f_js');
    }
};
