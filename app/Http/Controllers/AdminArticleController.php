<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\ArticleCategories;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Str;
use App\Services\ImageService;

class AdminArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Article::all();

        return view('admin.article.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ArticleCategories::all();
        
        return view('admin.article.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ImageService $imageService)
    {
        // Define the path to store article images
        $image_path = public_path('assets/images/articles/');
    
        // Prepare a new database entry.
        $page = new Article;
    
        $page->date = $request->date;
        $page->order = $request->article_order;
        $page->title = $request->title;
        $page->summary = $request->summary;
        $page->slug = Str::slug($page->title, '-');
        $page->text = $request->text;
        $page->user_id = Auth::user()->id;
        $page->articles_id = $request->category;
    
        // Handle featured image upload if it exists
        if ($request->hasFile('image')) {
            // Use the ImageService to handle the image upload and generate different sizes
            $imagePaths = $imageService->handleImageUpload($request->file('image'), 'assets/images/articles/');
    
            // Store each image path in the respective database columns
            $page->original_image = $imagePaths['original'];
            $page->small_image = $imagePaths['small'];
            $page->medium_image = $imagePaths['medium'];
            $page->large_image = $imagePaths['large'];
        }
    
        // Handle additional images for the editor
        $uploadedPaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $imageService->optimizeAndSaveImage($image);
                $uploadedPaths[] = $imagePath;
            }
        }
    
        // Store the image paths in the `images` JSON column
        $page->images = json_encode($uploadedPaths);
    
        // Check if the article is to be published
        $page->published = $request->has('published') ? 1 : 0;
    
        // Save the new article to the database
        $page->save();
    
        return redirect()->action([AdminArticleController::class, 'index'])->with('success', 'Article created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //  Not used
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
        $categories = ArticleCategories::all();
        $page = Article::findorFail($id);

        return view('admin.article.edit', compact('page', 'categories'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, ImageService $imageService)
    {
        // Retrieve the existing article from the database
        $page = Article::findOrFail($id);
    
        // Update the article fields
        $page->date = $request->date;
        $page->order = $request->article_order;
        $page->title = $request->title;
        $page->summary = $request->summary;
        $page->slug = Str::slug($page->title, '-');
        $page->text = $request->text;
        $page->user_id = Auth::user()->id;
        $page->articles_id = $request->category;
    
        // Handle featured image upload if a new image is uploaded
        if ($request->hasFile('image')) {
            // Delete the old images if they exist
            $imageService->deleteImage([
                $page->original_image,
                $page->small_image,
                $page->medium_image,
                $page->large_image
            ]);
    
            // Upload and resize the new image, storing paths for multiple sizes
            $imagePaths = $imageService->handleImageUpload($request->file('image'), 'assets/images/articles/');
    
            // Store the new image paths in their respective columns
            $page->original_image = $imagePaths['original'];
            $page->small_image = $imagePaths['small'];
            $page->medium_image = $imagePaths['medium'];
            $page->large_image = $imagePaths['large'];
        }
    
        // Handle additional images for the editor
        $uploadedPaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $imageService->optimizeAndSaveImage($image);
                $uploadedPaths[] = $imagePath;
            }
        }
    
        // Merge new images with existing ones (if any)
        $existingImages = json_decode($page->images) ?? [];
        $updatedImages = array_merge($existingImages, $uploadedPaths);
    
        // Store the updated image paths in the 'images' field
        $page->images = json_encode($updatedImages);
    
        // Check if the article is to be published
        $page->published = $request->has('published') ? 1 : 0;
    
        // Save the updated article to the database
        $page->save();
    
        return back()->with('success', 'Article updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, ImageService $imageService)
    {
        // Retrieve the article by ID
        $page = Article::findOrFail($id);
    
        // Delete all associated featured image sizes, including the original, if they exist
        $imageService->deleteImage([
            $page->original_image,
            $page->small_image,
            $page->medium_image,
            $page->large_image
        ]);
    
        // Delete all images stored in the `images` JSON field, if they exist
        $additionalImages = json_decode($page->images);
        if ($additionalImages) {
            foreach ($additionalImages as $imagePath) {
                $imageService->deleteImage([$imagePath]); // Use the deleteImage method for each additional image
            }
        }
    
        // Delete the article from the database
        $page->delete();
    
        // Redirect or return a response
        return back()->with('success', 'Article deleted successfully!');
    }
}
