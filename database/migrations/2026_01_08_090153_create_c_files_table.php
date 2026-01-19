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
        Schema::create('c_files', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('code')->nullable()->unique();
            $table->integer('v_corte');
            $table->integer('number');
            $table->string('subject')->nullable();
            $table->tinyInteger('kind')->default(1);
            $table->dateTime('c_date');
            $table->integer('c_begin_n')->nullable();
            $table->integer('c_start_year')->nullable();
            $table->integer('round_year')->nullable();
            $table->integer('degree1_court')->nullable();
            $table->integer('degree1_state')->nullable();
            $table->integer('degree1_room')->nullable();
            $table->integer('degree1_number')->nullable();
            $table->integer('degree1_year')->nullable();
            $table->integer('degree1_dec_n')->nullable();
            $table->dateTime('degree1_dec_d')->nullable();
            $table->integer('degree2_court')->nullable();
            $table->integer('degree2_state')->nullable();
            $table->integer('degree2_room')->nullable();
            $table->integer('degree2_number')->nullable();
            $table->integer('degree2_year')->nullable();
            $table->integer('degree2_dec_n')->nullable();
            $table->dateTime('degree2_dec_d')->nullable();
            $table->integer('user_id');
            $table->timestamps();
        });

        DB::unprepared(
            "CREATE TRIGGER c_files_code_seq BEFORE INSERT ON c_files FOR EACH ROW
                BEGIN
                     IF NEW.code IS NULL THEN
                        SET NEW.code = (SELECT COALESCE(MAX(code), 0) + 1 FROM c_files);
                    END IF;
                END;"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('c_files');
        DB::unprepared("DROP TRIGGER IF EXISTS c_files_code_seq");
    }
};
