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
        Schema::create('dec_tabs', function (Blueprint $table) {
            $table->integer('tab_code');
            $table->string('tab_desc');
            $table->integer('tab_order');
            $table->text('tab_value');
            $table->float('tab_font_s');
            $table->bigInteger('cf_code');
            $table->bigInteger('descision_n');
            $table->dateTime('descision_d')->nullable();
            $table->tinyInteger('not_printed')->default(0);
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dec_tabs');
    }
};
