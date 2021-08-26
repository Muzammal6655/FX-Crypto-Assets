<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableDepositsWithdrawsInvestmentsAddApprovedAtField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `deposits` ADD COLUMN `approved_at` TIMESTAMP NULL AFTER `status`;");
        DB::statement("ALTER TABLE `withdraws` ADD COLUMN `approved_at` TIMESTAMP NULL AFTER `status`;");
        DB::statement("ALTER TABLE `pool_investments` ADD COLUMN `approved_at` TIMESTAMP NULL AFTER `status`;");
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
