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
        Schema::create('hosting', function (Blueprint $table) {
            $table->id();
            $table->Biginteger('user_id')->unsigned();
            $table->BigInteger('server_id')->unsigned();
            $table->text('username');
            $table->string('plan')->default('Default');
            $table->string('password');
            $table->string('domain');
            $table->string('country');
            $table->text('reason');
            $table->boolean('approved');
            $table->timestamps();
        
            // Create foreign keys

            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');

            $table->foreign('server_id')
            ->references('id')
            ->on('servers')
            ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hosting');
    }
};
