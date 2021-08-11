<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableUsersAdd2faAttemptsFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `users`   
        ADD COLUMN `2fa_attempts_count` TINYINT(1) DEFAULT 0  NULL AFTER `password_attempts_date`,
        ADD COLUMN `2fa_attempts_date` DATE NULL AFTER `2fa_attempts_count`;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
