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
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->text('provider');
            $table->text('location'); 
            $table->text('cpanelUrl');
            $table->text('whmUrl'); 
            $table->text('apiKey');
            $table->text('username');
            $table->text('package');
            $table->text('ns1'); 
            $table->text('ns2'); 
            $table->text('ip'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servers');
    }
};
