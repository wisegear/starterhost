<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GalleryCategory;
use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use App\Models\GalleryTag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Services\GalleryService;
use Intervention\Image\Laravel\Facades\Image;
class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // Determine the view basedon the options selected - default, search, category, tag

        if (isset($_GET['search'])) {
        
            $searchTerm = $_GET['search'];
            $results = GalleryImage::where(function ($query) use ($searchTerm) {
                $query->where('title', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('summary', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('text', 'LIKE', '%' . $searchTerm . '%');
            })->paginate(16);


        } elseif (isset($_GET['category'])) {

            $slug = ($_GET['category']);

            // Convert slug back to category name
            $category = str_replace('-', ' ', $slug);

            $category = GalleryCategory::where('name', $category)->first();

            $results = GalleryAlbum::with('category')->where('category_id', $category->id)->paginate(16);

        } elseif (isset($_GET['album'])) {

            $slug = ($_GET['album']);

            // Convert slug back to category name
            $album = str_replace('-', ' ', $slug);

            $album = GalleryAlbum::where('name', $album)->first();

            $results = GalleryImage::with('album')->where('album_id', $album->id)->paginate(16);
        
        } elseif (isset($_GET['tag'])) {

            $tag = GalleryTag::where('name', $_GET['tag'])->first();

            if ($tag) {
                $results = GalleryImage::whereHas('tags', function ($query) use ($tag) {
                    $query->where('gallery_tags.id', $tag->id); // Specify the table name for the 'id' column
                })->paginate(16);
            } else {
                $results = collect(); // Return empty collection if tag is not found
            }            

        } else {

            $results = GalleryImage::where('published', true)->orderBy('id', 'desc')->paginate(16);
        
        }
        

        $imageTotal = GalleryImage::count();
        $categories = GalleryCategory::all();
        $unpublished = GalleryImage::where('published', false)->get();
        
        $popularTags = DB::table('gallery_image_tag')
            ->leftJoin('gallery_tags', 'gallery_tags.id', '=', 'gallery_image_tag.tag_id')
            ->select('gallery_image_tag.tag_id', 'name', DB::raw('count(*) as total'))
            ->groupBy('gallery_image_tag.tag_id', 'name')
            ->orderBy('total', 'desc')
            ->limit(15)
            ->get();

        return view('gallery.index', compact('imageTotal', 'categories', 'popularTags', 'results', 'unpublished'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('Admin');

        $categories = GalleryCategory::with('albums')->get();
        $albums = GalleryAlbum::all();

        return view('gallery.create', compact('categories', 'albums'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, GalleryService $galleryService)
    {

        Gate::authorize('Admin');

        // Validate the request input
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'title' => 'required|string|max:150',
            'date_taken' => 'nullable|date',
            'location' => 'nullable|string|max:200',
            'summary' => 'nullable|string',
            'text' => 'nullable|string',
            'category_name' => 'required|string', // Expect the category name
            'album_name' => 'required|string',    // Expect the album name
            'tags' => 'nullable|string',          // Optional, comma-separated tags
        ]);
    
        // Upload the image and get the file name
        $imageName = $galleryService->handleGalleryImageUpload(
            $request->file('image'),
            $request->input('category_name'), // Pass the category name
            $request->input('album_name')    // Pass the album name
        );
    
        // Find the album based on the name and category
        $album = GalleryAlbum::where('name', $request->input('album_name'))
            ->whereHas('category', function ($query) use ($request) {
                $query->where('name', $request->input('category_name'));
            })
            ->first();
    
        if (!$album) {
            return back()->withErrors(['album_name' => 'The selected album does not exist.']);
        }
    
        // Save the image metadata to the database
        $galleryImage = GalleryImage::create([
            'image' => $imageName, // Store only the file name
            'title' => $request->title,
            'date_taken' => $request->date_taken,
            'location' => $request->location,
            'summary' => $request->summary,
            'text' => $request->text,
            'album_id' => $album->id,
            'user_id' => auth()->id(),
            'slug' => \Str::slug($request->title),
        ]);
    
        // Process and attach tags
        if ($request->filled('tags')) {
            $tags = explode(',', $request->input('tags')); // Split tags by comma
            $tagIds = [];
    
            foreach ($tags as $tagName) {
                $tagName = trim($tagName); // Remove extra spaces
                if (!empty($tagName)) {
                    // Find or create the tag
                    $tag = GalleryTag::firstOrCreate(['name' => $tagName]);
                    $tagIds[] = $tag->id;
                }
            }
    
            // Attach tags to the image using the pivot table
            $galleryImage->tags()->sync($tagIds);
        }
    
        // Redirect back with a success message
        return redirect()->route('gallery.index')->with('success', 'Image uploaded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {

        $page = GalleryImage::with('tags')->where('slug', $slug)->first();

        $imageTotal = GalleryImage::count();
        $categories = GalleryCategory::all();
        $unpublished = GalleryImage::where('published', false)->get();

        $popularTags = DB::table('gallery_image_tag')
            ->leftJoin('gallery_tags', 'gallery_tags.id', '=', 'gallery_image_tag.tag_id')
            ->select('gallery_image_tag.tag_id', 'name', DB::raw('count(*) as total'))
            ->groupBy('gallery_image_tag.tag_id', 'name')
            ->orderBy('total', 'desc')
            ->limit(15)
            ->get();

        return view('gallery.show', compact('imageTotal', 'categories', 'popularTags', 'page', 'unpublished'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        Gate::authorize('Admin');

        // Load the image with related tags, album, and category
        $image = GalleryImage::with('tags', 'album.category')->findOrFail($id);

        // Load categories with their albums
        $categories = GalleryCategory::with('albums')->get();

        return view('gallery.edit', compact('image', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id, GalleryService $galleryService)
    {
        Gate::authorize('Admin');
        // Validate the request input
        $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Image is optional for update
            'title' => 'required|string|max:150',
            'date_taken' => 'nullable|date',
            'location' => 'nullable|string|max:200',
            'summary' => 'required|string',
            'text' => 'nullable|string',
            'category_name' => 'required|string', // Expect the category name
            'album_name' => 'required|string',    // Expect the album name
            'tags' => 'nullable|string',          // Optional, comma-separated tags
        ]);
    
        // Find the existing gallery image
        $galleryImage = GalleryImage::with('album.category')->findOrFail($id);
    
        // Current album and category details
        $currentCategoryName = $galleryImage->album->category->name;
        $currentAlbumName = $galleryImage->album->name;
        $currentImageName = $galleryImage->image;
    
        // Find the new album based on the name and category
        $newAlbum = GalleryAlbum::where('name', $request->input('album_name'))
            ->whereHas('category', function ($query) use ($request) {
                $query->where('name', $request->input('category_name'));
            })
            ->first();
    
        if (!$newAlbum) {
            return back()->withErrors(['album_name' => 'The selected album does not exist.']);
        }
    
        // Check if the album has changed
        $isAlbumChanged = ($currentAlbumName !== $newAlbum->name || $currentCategoryName !== $newAlbum->category->name);
    
        // If a new image is uploaded, delete the old one
        if ($request->hasFile('image')) {
            $galleryService->deleteGalleryImages($currentImageName, $currentCategoryName, $currentAlbumName);
    
            // Upload the new image
            $newImageName = $galleryService->handleGalleryImageUpload(
                $request->file('image'),
                $request->input('category_name'),
                $request->input('album_name')
            );
    
            $galleryImage->image = $newImageName;
        } elseif ($isAlbumChanged) {
            // If only the album is changed, move the image to the new album folder
            $galleryService->moveGalleryImages(
                $currentImageName,
                $currentCategoryName,
                $currentAlbumName,
                $newAlbum->category->name,
                $newAlbum->name
            );
        }
    
        // Update category and album in the database
        $galleryImage->album_id = $newAlbum->id;
    
        // Update other fields
        $galleryImage->title = $request->title;
        $galleryImage->date_taken = $request->date_taken;
        $galleryImage->location = $request->location;
        $galleryImage->summary = $request->summary;
        $galleryImage->text = $request->text;
    
        // Handle published and featured checkboxes
        $galleryImage->published = $request->has('published') ? true : false;
        $galleryImage->featured = $request->has('featured') ? true : false;
    
        // Update tags
        if ($request->filled('tags')) {
            // Split, trim, and filter tags
            $tags = array_filter(array_map('trim', explode(',', $request->input('tags'))));
    
            // Debug: Log the processed tags
            \Log::info('Processed Tags:', $tags);
    
            $tagIds = [];
            foreach ($tags as $tagName) {
                if (!empty($tagName)) {
                    // Find or create the tag
                    $tag = GalleryTag::firstOrCreate(['name' => $tagName]);
                    $tagIds[] = $tag->id;
                }
            }
    
            // Debug: Log the tag IDs being synced
            \Log::info('Tag IDs to sync:', $tagIds);
    
            // Sync tags with the image
            $galleryImage->tags()->sync($tagIds);
        } else {
            // Debug: Log when no tags are provided
            \Log::info('No tags provided. Detaching all tags.');
            $galleryImage->tags()->detach();
        }
    
        // Save the updated image
        $galleryImage->save();
    
        return redirect()->back()->with('success', 'Image updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, GalleryService $galleryService)
    {
        Gate::authorize('Admin');
        
        // Find the image by ID
        $galleryImage = GalleryImage::with('album.category')->findOrFail($id);
    
        // Retrieve the necessary details for deletion
        $categoryName = $galleryImage->album->category->name;
        $albumName = $galleryImage->album->name;
        $imageName = $galleryImage->image;
    
        // Delete the associated image files
        $galleryService->deleteGalleryImages($imageName, $categoryName, $albumName);
    
        // Detach all tags associated with the image
        $galleryImage->tags()->detach();
    
        // Delete the database entry
        $galleryImage->delete();
    
        return redirect()->route('gallery.index')->with('success', 'Image deleted successfully.');
    }
}
