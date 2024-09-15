<?php

use App\Models\App;
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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(App::class);

            $table->string('name');
            $table->string('email');
            $table->string('discord');
            $table->string('tier');
            $table->string('status');
            $table->date('join_date');
            $table->date('last_charge_date');
            $table->date('next_charge_date');
            $table->date('cancel_date');
            $table->date('access_expiration_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
