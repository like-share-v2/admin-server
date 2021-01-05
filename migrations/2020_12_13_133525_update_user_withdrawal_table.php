<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class UpdateUserWithdrawalTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_withdrawal', function (Blueprint $table) {
            $table->string('bank_code', 50)->default(null)->nullable()->comment('银行Code');
        });
        Schema::table('defray', function (Blueprint $table) {
            $table->unsignedInteger('withdrawal_id')->default(0)->comment('用户提现ID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
}
