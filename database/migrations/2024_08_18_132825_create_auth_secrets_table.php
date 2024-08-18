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
        Schema::create('auth_secrets', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignIdFor(App::class);
            $table->string("client_id");
            $table->string("client_secret");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_secrets');
    }
};
