<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePoolBalances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE TABLE `pool_balances` (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `pool_id` int(10) unsigned DEFAULT NULL,
          `year_month` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
          `gross_amount` double DEFAULT NULL,
          `net_amount` double DEFAULT NULL,
          `created_at` timestamp NULL DEFAULT NULL,
          `updated_at` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `pool_id` (`pool_id`),
          CONSTRAINT `pool_balances_ibfk_1` FOREIGN KEY (`pool_id`) REFERENCES `pools` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pool_balances');
    }
}
