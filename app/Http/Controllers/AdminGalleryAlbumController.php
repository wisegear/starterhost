<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GalleryImage;
use App\Models\GalleryCategory;
use App\Models\GalleryAlbum;

class AdminGalleryAlbumController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'required|exists:gallery_categories,id',
        ]);
    
        GalleryAlbum::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
        ]);
    
        return redirect()->route('admin.gallery.index')->with('success', 'Album created successfully.');
    }

    public function edit($id)
    {
        $album = GalleryAlbum::with('category')->findOrFail($id);
        $categories = GalleryCategory::all();
        return view('admin.gallery.albums.edit', compact('album', 'categories'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'required|exists:gallery_categories,id',
        ]);
    
        $album = GalleryAlbum::findOrFail($id);
        $album->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
        ]);
    
        return redirect()->route('admin.gallery.index')->with('success', 'Album updated successfully.');
    }
    
    public function destroy($id)
    {
        $album = GalleryAlbum::with('images', 'category')->findOrFail($id);
    
        // Ensure there are no images before deleting the album
        if ($album->images->isNotEmpty()) {
            return redirect()->route('admin.gallery.index')->with('error', 'Cannot delete album with existing images.');
        }
    
        // Define the album folder path
        $albumPath = "images/gallery/{$album->category->name}/{$album->name}";
    
        // Remove the folder from storage
        if (\Storage::disk('public')->exists($albumPath)) {
            \Storage::disk('public')->deleteDirectory($albumPath);
        }
    
        // Delete album from database
        $album->delete();
    
        return redirect()->route('admin.gallery.index')->with('success', 'Album deleted successfully.');
    }
}
