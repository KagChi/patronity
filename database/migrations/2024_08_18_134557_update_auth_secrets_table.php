<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('auth_secrets', function (Blueprint $table) {
            $table->dropColumn("client_id");
            $table->dropColumn("client_secret");

            $table->string("client_access_token");
            $table->string("client_refresh_token");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('auth_secrets', function (Blueprint $table) {
            $table->dropColumn('client_access_token');
            $table->dropColumn('client_refresh_token');

            $table->string("client_id");
            $table->string("client_secret");
        });
    }
};
