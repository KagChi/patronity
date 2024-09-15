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
        Schema::table('members', function (Blueprint $table) {
            $table->string('tier')
                ->nullable()
                ->change();
            $table->string('cancel_date')
                ->nullable()
                ->change();
            $table->string('discord')
                ->nullable()
                ->change();
            $table->string('last_charge_date')
                ->nullable()
                ->change();
            $table->dropColumn('access_expiration_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->date('access_expiration_date');
            $table->string('tier')
                ->change();
        });
    }
};
