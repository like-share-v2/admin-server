<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateAdminGoogleAuthTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admin_google_auth', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->unique()->comment('用户ID');
            $table->string('secret', 50)->comment('secret');
            $table->unsignedTinyInteger('is_enable')->comment('是否启用');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_google_auth');
    }
}
