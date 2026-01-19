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
        Schema::create('tabs', function (Blueprint $table) {
            $table->integer('code')->unique();
            $table->string('description');
            $table->integer('order');
            $table->json('courts')->nullable();
            $table->tinyInteger('not_printed')->default(0);
            $table->tinyInteger('group');
            $table->timestamps();
        });
        DB::unprepared(
            "CREATE TRIGGER tab_code_seq BEFORE INSERT ON tabs FOR EACH ROW BEGIN 
                IF NEW.code IS NULL THEN
                            SET NEW.code = (SELECT COALESCE(MAX(code), 0) + 1 FROM tabs);
                END IF;
             END;"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabs');
        DB::unprepared("DROP TRIGGER IF EXISTS tab_code_seq;");
    }
};
