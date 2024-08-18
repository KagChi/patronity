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
            $table->string("client_id");
            $table->string("client_secret");
            $table->timestamp("expires_at")
                ->nullable()
                ->default(now()->addMonth());
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('auth_secrets', function (Blueprint $table) {
            $table->dropColumn("client_id");
            $table->dropColumn("client_secret");
            $table->dropColumn("expires_at");
        });
    }
};
