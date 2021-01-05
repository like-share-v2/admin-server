<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class UpdateDefrayTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('defray', function (Blueprint $table) {
            $table->string('email', 100)->nullable()->default(null)->comment('邮箱');
            $table->string('phone', 50)->nullable()->default(null)->comment('邮箱');
            $table->string('upi', 50)->nullable()->default(null)->comment('邮箱');
            $table->string('ifsc', 50)->nullable()->default(null)->comment('IFSC');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('defray', function (Blueprint $table) {
            //
        });
    }
}
