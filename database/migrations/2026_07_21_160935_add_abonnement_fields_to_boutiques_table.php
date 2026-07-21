<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('boutiques', function (Blueprint $table) {
            $table->string('plan')->nullable()->after('telephone');
            $table->string('abonnement_statut')->default('essai')->after('plan');
            $table->timestamp('essai_expire_le')->nullable()->after('abonnement_statut');
            $table->timestamp('abonnement_expire_le')->nullable()->after('essai_expire_le');
        });
    }

    public function down()
    {
        Schema::table('boutiques', function (Blueprint $table) {
            $table->dropColumn(['plan', 'abonnement_statut', 'essai_expire_le', 'abonnement_expire_le']);
        });
    }
};
