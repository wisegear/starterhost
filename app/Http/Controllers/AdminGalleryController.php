<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GalleryImage;
use App\Models\GalleryCategory;
use App\Models\GalleryAlbum;

class AdminGalleryController extends Controller
{
    public function index()
    {
        // Fetch all images paginated by 25
        $images = GalleryImage::orderBy('id', 'desc')->with('album.category')->paginate(25);
    
        // Fetch all categories with their albums and count of images in each album
        $categories = GalleryCategory::with(['albums' => function ($query) {
            $query->withCount('images'); // Add image count for each album
        }])->get();
    
        return view('admin.gallery.index', compact('images', 'categories'));
    }


}
