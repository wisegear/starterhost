<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogPosts;
use App\Models\BlogCategories;
use App\Models\BlogTags;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Str;
use App\Services\ImageService;
use App\Http\Controllers\ContentImageController;

class BlogController extends Controller
{

    protected $imageService;

    // Constructor injection for ImageService
    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       // If the visitor uses the search box.

       if (isset($_GET['search']))
       {

           $posts = BlogPosts::where(function ($query) {
               $query->where('title', 'LIKE', '%' . $_GET['search'] . '%')
                     ->orWhere('body', 'LIKE', '%' . $_GET['search'] . '%');            

       })
           ->paginate(6);

      }  elseif (isset($_GET['category'])) {

           $posts = BlogPosts::GetCategories($_GET['category']);
      
      } elseif (isset($_GET['tag'])) {

       $posts = BlogPosts::GetTags($_GET['tag']);

      } else {

       $posts = BlogPosts::with('BlogCategories', 'BlogTags', 'Users')
                ->where('published', true)
                ->orderBy('date', 'desc')
                ->paginate(10);

       }

       $categories = BlogCategories::all();

       $popular_tags = DB::table('blog_post_tags')
       ->leftjoin('blog_tags', 'blog_tags.id', '=', 'blog_post_tags.tag_id')
       ->select('blog_post_tags.tag_id', 'name', DB::raw('count(*) as total'))
       ->groupBy('blog_post_tags.tag_id', 'name')
       ->orderBy('total', 'desc')
       ->limit(15)
       ->get();

       $unpublished = \App\Models\BlogPosts::where('published', false)->get();

        return view('blog.index', compact('posts', 'categories', 'popular_tags', 'unpublished'));

        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('Admin');

        $categories = BlogCategories::all();

        return view('blog.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ImageService $imageService)
    {
        Gate::authorize('Admin');
    
        // Prepare a new database entry for the blog post
        $post = new BlogPosts;
    
        $post->date = $request->date;
        $post->title = $request->title;
        $post->excerpt = $request->excerpt;
        $post->slug = Str::slug($post->title, '-');
        $post->body = $request->body;
        $post->user_id = Auth::user()->id;
        $post->categories_id = $request->category;
    
        if ($request->hasFile('image')) {
            // Use handleImageUpload for the main blog post image to create multiple sizes
            $imagePaths = $imageService->handleImageUpload($request->file('image'));
            
            // Store each image path
            $post->original_image = $imagePaths['original'];
            $post->small_image = $imagePaths['small'];
            $post->medium_image = $imagePaths['medium'];
            $post->large_image = $imagePaths['large'];
        }
        
        // Handle additional images for use in the editor (single version)
        $uploadedPaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Use optimizeAndSaveImage for editor images to create a single optimized version
                $imagePath = $imageService->optimizeAndSaveImage($image);
                $uploadedPaths[] = $imagePath;
            }
        }
        
        // Store the additional image paths in the 'images' column as JSON
        $post->images = json_encode($uploadedPaths);
    
        // Check if the post is to be published
        $post->published = $request->has('published') ? 1 : 0;
    
        // Check whether the post is featured
        $post->featured = $request->has('featured') ? 1 : 0;
    
        // Save the post to the database
        $post->save();
    
        // Sync the tags to the post
        BlogTags::StoreTags($request->tags, $post->slug);
    
        return redirect()->action([BlogController::class, 'index'])->with('success', 'Post created successfully! Images are available for use.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $post = BlogPosts::with('BlogCategories', 'users', 'blogTags')->where('slug', $slug)->first();
        $recentPosts = BlogPosts::orderBy('date', 'desc')->take(3)->get();

        return view('blog.show', compact('post', 'recentPosts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        Gate::authorize('Admin');

        $post = BlogPosts::find($id);
        $categories = BlogCategories::all();
        $split_tags = BlogTags::TagsForEdit($id);

        return view('blog.edit', compact('post', 'categories', 'split_tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, ImageService $imageService)
    {
        Gate::authorize('Admin');
    
        // Retrieve the existing post from the database
        $post = BlogPosts::findOrFail($id);
    
        // Update the post fields
        $post->date = $request->date;
        $post->title = $request->title;
        $post->excerpt = $request->excerpt;
        $post->slug = Str::slug($post->title, '-');
        $post->body = $request->body;
        $post->user_id = Auth::user()->id;
        $post->categories_id = $request->category;
    
        if ($request->hasFile('image')) {
            // Delete the old images
            $imageService->deleteImage([
                $post->original_image,
                $post->small_image,
                $post->medium_image,
                $post->large_image
            ]);
        
            // Handle the new blog post image upload with multiple sizes
            $imagePaths = $imageService->handleImageUpload($request->file('image'));
        
            // Store the new image paths
            $post->original_image = $imagePaths['original'];
            $post->small_image = $imagePaths['small'];
            $post->medium_image = $imagePaths['medium'];
            $post->large_image = $imagePaths['large'];
        }
        
        // Handle additional images for the editor (single optimized version)
        $uploadedPaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $imageService->optimizeAndSaveImage($image);
                $uploadedPaths[] = $imagePath;
            }
        }
        
        // Merge new images with existing ones
        $existingImages = json_decode($post->images) ?? [];
        $updatedImages = array_merge($existingImages, $uploadedPaths);
        
        // Store the updated image paths in the 'images' field
        $post->images = json_encode($updatedImages);
    
        // Check if the post is to be published
        $post->published = $request->has('published') ? 1 : 0;
    
        // Check whether the post is featured
        $post->featured = $request->has('featured') ? 1 : 0;
    
        // Save the updated post to the database
        $post->save();
    
        // Sync the tags
        BlogTags::StoreTags($request->tags, $post->slug);
        
        return back()->with('success', 'Post updated successfully with new images!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, ImageService $imageService)
    {

        Gate::authorize('Admin');
        
        // Retrieve the post by ID
        $post = BlogPosts::findOrFail($id);
    
        // Delete all associated image sizes, including the original, if they exist
        $imageService->deleteImage([
            $post->original_image,
            $post->small_image,
            $post->medium_image,
            $post->large_image
        ]);
    
        // Delete all images stored in the `images` JSON field, if they exist
        $additionalImages = json_decode($post->images);
        if ($additionalImages) {
            foreach ($additionalImages as $imagePath) {
                $imageService->deleteImage([$imagePath]); // Use the deleteImage method for each additional image
            }
        }
    
        // Delete the post from the database
        $post->delete();
    
        return back()->with('success', 'Post deleted successfully!');
    }

}
