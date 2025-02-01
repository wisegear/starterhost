<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Quote;
use App\Models\Timeline;
use App\Models\BlogPosts;
use App\Models\BlogCategories;
use App\Models\BlogTags;
use App\Models\BlogPostTags;
use App\Models\GalleryCategory;
use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use App\Models\GalleryTag;
use App\Models\GalleryImageTag;
use App\Models\LinksCategories;
use App\Models\Links;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UsersSeeder::class);
        $this->call(UsersRolesSeeder::class);

        Quote::factory(50)->create();

        Timeline::factory(100)->create();

        BlogCategories::factory(6)->create();
        BlogPosts::factory(50)->create();
        BlogTags::factory(100)->create();
        BlogPostTags::factory(200)->create();

        LinksCategories::factory(8)->create();
        Links::factory(100)->create();

        // Seed gallery categories, albums, and images
        $galleryCategories = GalleryCategory::factory(5)->create();
        $galleryAlbums = GalleryAlbum::factory(20)->create();
        $galleryImages = GalleryImage::factory(100)->create();
        $galleryTags = GalleryTag::factory(100)->create();

        // Assign random tags to each gallery image
        foreach ($galleryImages as $image) {
            // Get a random number of tags
            $randomTags = $galleryTags->random(rand(1, 5))->pluck('id');

            // Sync tags with the image
            $image->tags()->sync($randomTags);
        }
    }
}
