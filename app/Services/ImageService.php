<?php

namespace App\Services;

use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImageService
{

    public function handleImageUpload($image)
    {

        $path = '/assets/images/uploads/';

        // Define a unique image name
        $imageName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $image->getClientOriginalExtension();

        // Define paths for different sizes
        $originalPath = $path . 'original_' . $imageName;
        $smallPath = $path . 'small_' . $imageName;
        $mediumPath = $path . 'medium_' . $imageName;
        $largePath = $path . 'large_' . $imageName;

        // Move the original image to the original path
        $image->move(public_path($path), 'original_' . $imageName);

        // Read the original image and resize it for small, medium, and large versions with specified quality
        // Resize and save the small image: 350x175, 50% quality
        Image::read(public_path($originalPath))
            ->cover(350, 200, 'center')
            ->save(public_path($smallPath), 50);

        // Resize and save the medium image: 800x300, 75% quality
        Image::read(public_path($originalPath))
            ->cover(800, 300, 'center')
            ->save(public_path($mediumPath), 75);

        // Resize and save the large image: 1200x300, 75% quality
        Image::read(public_path($originalPath))
            ->cover(1200, 400, 'center')
            ->save(public_path($largePath), 75);

        // Return the paths for each image size
        return [
            'original' => $originalPath,
            'small' => $smallPath,
            'medium' => $mediumPath,
            'large' => $largePath,
        ];
    }

    public function deleteImage($imagePaths)
    {
        // If $imagePaths is an array of multiple image paths
        if (is_array($imagePaths)) {
            foreach ($imagePaths as $imagePath) {
                if (!empty($imagePath) && file_exists(public_path($imagePath))) {
                    unlink(public_path($imagePath));  // Delete each file
                }
            }
        } elseif (is_string($imagePaths)) {
            // If $imagePaths is a single string, delete that one image
            if (!empty($imagePaths) && file_exists(public_path($imagePaths))) {
                unlink(public_path($imagePaths));
            }
        }
    }    

        public function optimizeAndSaveImage($image, $path = '/assets/images/uploads/')
        {
            // Generate a unique image name
            $imageName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $image->getClientOriginalExtension();
    
            // Create the full path for storing the optimized image
            $optimizedPath = $path . $imageName;
    
            // Ensure the path exists
            if (!file_exists(public_path($path))) {
                mkdir(public_path($path), 0755, true);
            }
    
            Image::read($image->getRealPath())
            ->save(public_path($optimizedPath), 50);  // Save with 75% quality to reduce file size
    
            return $optimizedPath;
        }

        public function handleLinkImageUpload($image)
        {
            $path = '/assets/images/uploads/';
            
            // Generate a unique image name
            $imageName = 'link_' . pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $image->getClientOriginalExtension();
            $fullImagePath = public_path($path . $imageName);
            $relativeImagePath = $path . $imageName; // Only the relative path
        
            // Ensure the directory exists
            if (!file_exists(public_path($path))) {
                mkdir(public_path($path), 0755, true);
            }
        
            // Read, resize, crop, and save the image at 200x200 pixels
            Image::read($image->getRealPath())
                ->resize(200, 200, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->crop(200, 200)
                ->save($fullImagePath, 75);
        
            return $relativeImagePath; // Return only the relative path
        }
    }


