<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateKycDocumentsTableType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kyc_documents', function (Blueprint $table) {
            $table->boolean('doc_type')->default(1)->comment('1=passport;2=photo;3=au doc')->after('document');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kyc_documents', function (Blueprint $table) {
            //
        });
    }
}
