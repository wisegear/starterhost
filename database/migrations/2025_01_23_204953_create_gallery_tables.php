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
        // Create categories table
        Schema::create('gallery_categories', function(Blueprint $table)
        {
            $table->id('id');
            $table->string('name', 150);
            
        });

        // Create Album table
        Schema::create('gallery_albums', function(Blueprint $table)
        {
            $table->id('id');
            $table->string('name', 150);
            $table->UnsignedBigInteger('category_id');

            $table->foreign('category_id')
            ->references('id')
            ->on('gallery_categories')
            ->onDelete('cascade');
            
        });        

        // Create Image Table
        Schema::create('gallery_images', function (Blueprint $table) {
            $table->id('id');
            $table->text('image')->nullable();
            $table->date('date_taken')->nullable();
            $table->string('title', 150);
            $table->string('slug', 200);
            $table->text('summary');
            $table->string('location', 200);
            $table->boolean('featured')->default(false);
            $table->boolean('published')->default(true);
            $table->text('text');
            $table->UnsignedBigInteger('user_id');
            $table->UnsignedBigInteger('album_id');     
            $table->timestamps();  

            // Create foreign keys

            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');
            
            $table->foreign('album_id')
            ->references('id')
            ->on('gallery_albums')
            ->onDelete('cascade');
        
        });


        // Create Gallery tags table
        Schema::create('gallery_tags', function(Blueprint $table)
        {
            $table->id('id');
            $table->string('name', 50)->unique();

        });            

        // Create pivot table for Image tags
        Schema::create('gallery_image_tag', function (Blueprint $table) {
            $table->unsignedBigInteger('tag_id');
            $table->unsignedBigInteger('gallery_image_id'); // Updated column name

            // Create foreign keys
            $table->foreign('tag_id')
                ->references('id')
                ->on('gallery_tags')
                ->onDelete('cascade');

            $table->foreign('gallery_image_id')
                ->references('id')
                ->on('gallery_images')
                ->onDelete('cascade');

            // Composite primary key
            $table->primary(['tag_id', 'gallery_image_id']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('gallery_categories');
        Schema::drop('gallery_albums');
        Schema::drop('gallery_tags');
        Schema::drop('gallery_image_tags');
    }
};