<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GalleryImage;
use App\Models\GalleryCategory;
use App\Models\GalleryAlbum;

class AdminGalleryCategoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:150']);
        GalleryCategory::create(['name' => $request->name]);
    
        return redirect()->route('admin.gallery.index')->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = GalleryCategory::findOrFail($id);
        return view('admin.gallery.categories.edit', compact('category'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:150',
        ]);
    
        $category = GalleryCategory::findOrFail($id);
        $category->update(['name' => $request->name]);
    
        return redirect()->route('admin.gallery.index')->with('success', 'Category updated successfully.');
    }
    
    public function destroy($id)
    {
        $category = GalleryCategory::with('albums')->findOrFail($id);
    
        // Ensure there are no albums before deleting the category
        if ($category->albums->isNotEmpty()) {
            return redirect()->route('admin.gallery.index')->with('error', 'Cannot delete category with existing albums.');
        }
    
        // Define the category folder path
        $categoryPath = "images/gallery/{$category->name}";
    
        // Remove the folder from storage
        if (\Storage::disk('public')->exists($categoryPath)) {
            \Storage::disk('public')->deleteDirectory($categoryPath);
        }
    
        // Delete category from database
        $category->delete();
    
        return redirect()->route('admin.gallery.index')->with('success', 'Category deleted successfully.');
    }
}
