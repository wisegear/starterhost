<?php

namespace App\Services;

use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogService
{
    protected string $path = 'images/blog/'; // Storage path

    public function handleImageUpload($image)
    {
        $path = 'images/blog'; // Storage path
    
        // Ensure directory exists
        Storage::disk('public')->makeDirectory($path);
    
        // Generate a unique filename
        $imageName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $image->getClientOriginalExtension();
    
        // ✅ Store the original image first
        $originalPath = Storage::disk('public')->putFileAs($path, $image, $imageName);
    
        // ✅ Use the stored image as the source for resizing
        $fullOriginalPath = Storage::disk('public')->path($path . '/' . $imageName);
    
        // Resize and save different versions
        $this->resizeAndSaveImage($fullOriginalPath, 'small_' . $imageName, 350, 200, 50);
        $this->resizeAndSaveImage($fullOriginalPath, 'medium_' . $imageName, 800, 300, 75);
        $this->resizeAndSaveImage($fullOriginalPath, 'large_' . $imageName, 1200, 400, 75);
    
        return $imageName;
    }

    public function handleGalleryImageUpload($image)
    {
        $path = 'images/blog/'; // ✅ Store gallery images in blog/gallery
    
        // Generate a unique image name
        $imageName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $image->getClientOriginalExtension();
    
        // Ensure directory exists
        Storage::disk('public')->makeDirectory($path);
    
        // Paths for the original and thumbnail images
        $originalPath = $path . $imageName;
        $thumbnailPath = $path . 'thumbnail_' . $imageName;
    
        // Save the original image
        $image->storeAs($path, $imageName, 'public');
    
        // Create the thumbnail (350x200)
        Image::read($image->getRealPath())
            ->resize(350, 200, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->crop(350, 200)
            ->save(Storage::disk('public')->path($thumbnailPath), 70);
    
        // Return only filenames (not full paths)
        return [
            'original' => $imageName,
            'thumbnail' => 'thumbnail_' . $imageName,
        ];
    }
    
    protected function resizeAndSaveImage($originalPath, $newName, $width, $height, $quality)
    {
        $path = 'images/blog'; // Storage path
        $newPath = Storage::disk('public')->path($path . '/' . $newName);
    
        Image::read($originalPath) // ✅ Read from stored image
            ->cover($width, $height, 'center')
            ->save($newPath, $quality);
    }

    public function deleteImage($imageName)
    {
        $imageName = basename($imageName); // ✅ Ensure we're working with just the filename
    
        // Paths to the different image sizes
        $imagePaths = [
            'public/images/blog/' . $imageName,           // ✅ Original
            'public/images/blog/small_' . $imageName,    // ✅ Small
            'public/images/blog/medium_' . $imageName,   // ✅ Medium
            'public/images/blog/large_' . $imageName,    // ✅ Large
            'public/images/blog/thumbnail_' . $imageName, // ✅ Thumbnail (if exists)
        ];
    
        // Loop through all sizes and delete them if they exist
        foreach ($imagePaths as $path) {
            if (Storage::exists($path)) {
                Storage::delete($path);
            }
        }
    }

    public function deleteGalleryImages($galleryImages)
    {
        foreach ($galleryImages as $imageSet) {
            if (isset($imageSet['original'])) {
                Storage::disk('public')->delete($imageSet['original']);
            }
            if (isset($imageSet['thumbnail'])) {
                Storage::disk('public')->delete($imageSet['thumbnail']);
            }
        }
    }

    public function optimizeAndSaveImage($image)
    {
        $path = 'images/blog/'; // ✅ Store in the main blog folder
    
        // Generate a unique image name
        $imageName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $image->getClientOriginalExtension();
        
        // Ensure directory exists
        Storage::disk('public')->makeDirectory($path);
    
        // Create the full path for storing the optimized image
        $optimizedPath = $path . $imageName;
    
        // Resize & Save the image with 75% quality to reduce file size
        Image::read($image->getRealPath())
            ->save(Storage::disk('public')->path($optimizedPath), 75);
    
        return $imageName; // ✅ Store only the filename in the database
    }
    

    public function destroy($id)
    {
        Gate::authorize('Admin');
    
        $page = BlogPosts::findOrFail($id);
    
        // ✅ Delete the main blog image
        if ($page->image) {
            $this->blogService->deleteImage($page->image);
        }
    
        // ✅ Delete additional images stored in the blog folder
        $additionalImages = json_decode($page->images, true);
        if ($additionalImages) {
            foreach ($additionalImages as $imagePath) {
                $this->blogService->deleteImage($imagePath);
            }
        }
    
        // ✅ Delete gallery images
        $galleryImages = json_decode($page->gallery_images, true);
        if ($galleryImages) {
            foreach ($galleryImages as $imageSet) {
                $this->blogService->deleteImage($imageSet['original']);
                $this->blogService->deleteImage($imageSet['thumbnail']);
            }
        }
    
        $page->delete();
    
        return back()->with('success', 'Post deleted successfully!');
    }
}


