<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('decisions', function (Blueprint $table) {
            $table->integer('cf_code');
            $table->integer('number');
            $table->dateTime('date');
            $table->string('note')->nullable();
            $table->integer('user_id');
            $table->tinyInteger('croup');
            $table->dateTime('hurry_date')->nullable();
            $table->tinyInteger('kind');
            $table->tinyInteger('hurry')->default(0)->nullable();
            $table->tinyInteger('type');
            $table->string('hurry_text')->nullable();
            $table->integer('opposit_judge')->default(0);
            $table->string('higry_date')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decisions');
    }
};
