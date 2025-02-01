<?php

namespace App\Services;

use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;

class LinkService
{
    protected string $path = 'images/links/'; // ✅ Correct storage path

    /**
     * Handle the upload of a new link image.
     */
    public function handleLinkImageUpload($image)
    {
        // Generate a unique image name
        $imageName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $image->getClientOriginalExtension();
        
        // ✅ Store only the filename in the database
        $relativeImagePath = $imageName;
        $fullImagePath = Storage::disk('public')->path($this->path . $imageName);

        // Ensure the directory exists
        Storage::disk('public')->makeDirectory($this->path);

        // Read, resize, crop, and save the image at 200x200 pixels
        Image::read($image->getRealPath())
            ->resize(200, 200, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->crop(200, 200)
            ->save($fullImagePath, 75);

        return $relativeImagePath; // ✅ Return only the filename
    }

    /**
     * Delete an image from storage.
     */
    public function deleteImage($imageName)
    {
        // ✅ Make sure we're working with only the filename
        $imageName = basename($imageName); 

        // ✅ Construct the full storage path
        $imagePath = 'public/' . $this->path . $imageName;

        // ✅ Debugging output (ONLY if needed)
        // dd('Checking path:', $imagePath, Storage::exists($imagePath));

        if (Storage::exists($imagePath)) {
            return Storage::delete($imagePath);
        }
        return false;
    }

    /**
     * Update an existing image: delete old image, upload new one.
     */
    public function updateImage($newImage, $oldImageName = null)
    {
        // ✅ Delete old image if it exists
        if ($oldImageName && Storage::exists('public/' . $this->path . basename($oldImageName))) {
            $this->deleteImage($oldImageName);
        }

        // ✅ Upload new image
        return $this->handleLinkImageUpload($newImage);
    }
}



