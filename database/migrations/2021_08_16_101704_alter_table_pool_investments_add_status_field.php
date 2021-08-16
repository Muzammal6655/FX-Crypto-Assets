<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablePoolInvestmentsAddStatusField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pool_investments`   
        ADD COLUMN `reason` TEXT NULL AFTER `end_date`,
        ADD COLUMN `status` TINYINT(1) DEFAULT 0  NULL AFTER `reason`;");
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
