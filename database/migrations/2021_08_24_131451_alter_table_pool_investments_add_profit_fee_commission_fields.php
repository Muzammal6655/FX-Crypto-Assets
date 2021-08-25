<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablePoolInvestmentsAddProfitFeeCommissionFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pool_investments`   
          ADD COLUMN `profit` DOUBLE NULL AFTER `profit_percentage`,
          ADD COLUMN `management_fee` DOUBLE NULL AFTER `management_fee_percentage`,
          ADD COLUMN `commission` DOUBLE NULL AFTER `management_fee`;");
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
