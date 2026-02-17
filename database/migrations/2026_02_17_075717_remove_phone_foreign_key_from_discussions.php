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
        Schema::table('discussions', function (Blueprint $table) {
            $table->dropForeign(['phone_id']);
        });

        Schema::dropIfExists('phones');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('phones', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('discussions', function (Blueprint $table) {
            $table->foreign('phone_id')->references('id')->on('phones')->onDelete('cascade');
        });
    }
};
