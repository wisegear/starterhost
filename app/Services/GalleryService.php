<?php

namespace App\Services;

use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Models\GalleryCategory;
use App\Models\GalleryAlbum;

class GalleryService
{
    public function handleGalleryImageUpload($image, $categoryName, $albumName)
    {
        // Ensure category and album exist or create them
        $category = $this->ensureCategoryExists($categoryName);
        $album = $this->ensureAlbumExists($albumName, $category);
    
        // Base folder path for the gallery
        $basePath = "images/gallery/{$categoryName}/{$albumName}";
    
        // Generate a unique image name
        $imageName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $image->getClientOriginalExtension();
    
        // Paths for different image sizes
        $originalPath = "{$basePath}/{$imageName}";
        $smallPath = "{$basePath}/small_{$imageName}";
        $largePath = "{$basePath}/large_{$imageName}";
    
        // Save the original image
        $storedPath = $image->storeAs($basePath, $imageName, 'public');
    
        // Define the full path to the stored image
        $fullStoredImagePath = storage_path("app/public/{$originalPath}");
    
        // Ensure the file exists before processing
        if (!file_exists($fullStoredImagePath)) {
            \Log::error("File not found at: " . $fullStoredImagePath);
            return false;
        }
    
        // Create the small image (375x150)
        Image::read($fullStoredImagePath)
            ->resize(375, 150, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->save(storage_path("app/public/{$smallPath}"), 75);
    
        // Create the large image (1000x600)
        Image::read($fullStoredImagePath)
            ->resize(1000, 600, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->save(storage_path("app/public/{$largePath}"), 75);
    
        return $imageName;
    }

    private function ensureCategoryExists($categoryName)
    {
        return GalleryCategory::firstOrCreate(['name' => $categoryName], ['name' => $categoryName]);
    }

    private function ensureAlbumExists($albumName, $category)
    {
        return GalleryAlbum::firstOrCreate(
            ['name' => $albumName, 'category_id' => $category->id],
            ['name' => $albumName, 'category_id' => $category->id]
        );
    }

    public function deleteGalleryImages($imageName, $categoryName, $albumName)
    {
        $basePath = "images/gallery/{$categoryName}/{$albumName}";
    
        $paths = [
            "{$basePath}/{$imageName}",
            "{$basePath}/small_{$imageName}",
            "{$basePath}/large_{$imageName}",
        ];
    
        foreach ($paths as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }

    public function moveGalleryImages($imageName, $currentCategory, $currentAlbum, $newCategory, $newAlbum)
    {
        $currentBasePath = "images/gallery/{$currentCategory}/{$currentAlbum}";
        $newBasePath = "images/gallery/{$newCategory}/{$newAlbum}";
    
        // Ensure the new folder exists
        if (!Storage::disk('public')->exists($newBasePath)) {
            Storage::disk('public')->makeDirectory($newBasePath);
        }
    
        // List of image versions to move
        $versions = ['', 'small_', 'large_'];
    
        foreach ($versions as $prefix) {
            $currentPath = "{$currentBasePath}/{$prefix}{$imageName}";
            $newPath = "{$newBasePath}/{$prefix}{$imageName}";
    
            if (Storage::disk('public')->exists($currentPath)) {
                Storage::disk('public')->move($currentPath, $newPath);
            }
        }
    }
}
