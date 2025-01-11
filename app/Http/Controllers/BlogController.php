<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogPosts;
use App\Models\BlogCategories;
use App\Models\BlogTags;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\ImageService;

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
        if (isset($_GET['search'])) {
            $searchTerm = $_GET['search'];
            $posts = BlogPosts::where(function ($query) use ($searchTerm) {
                $query->where('title', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('body', 'LIKE', '%' . $searchTerm . '%');
            })->paginate(6);
            $posts->appends(['search' => $searchTerm]);
        } elseif (isset($_GET['category'])) {
            $category = $_GET['category'];
            $posts = BlogPosts::GetCategories($category);
            $posts->appends(['category' => $category]);
        } elseif (isset($_GET['tag'])) {
            $tag = $_GET['tag'];
            $posts = BlogPosts::GetTags($tag)->paginate(6);
            $posts->appends(['tag' => $tag]);
        } else {
            $posts = BlogPosts::with('BlogCategories', 'BlogTags', 'Users')
                ->where('published', true)
                ->orderBy('date', 'desc')
                ->paginate(10);
        }

        $categories = BlogCategories::all();

        $popular_tags = DB::table('blog_post_tags')
            ->leftJoin('blog_tags', 'blog_tags.id', '=', 'blog_post_tags.tag_id')
            ->select('blog_post_tags.tag_id', 'name', DB::raw('count(*) as total'))
            ->groupBy('blog_post_tags.tag_id', 'name')
            ->orderBy('total', 'desc')
            ->limit(15)
            ->get();

        $unpublished = BlogPosts::where('published', false)->get();

        return view('blog.index', compact('posts', 'categories', 'popular_tags', 'unpublished'));
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

    public function show(string $slug)
    {
        $page = BlogPosts::with('BlogCategories', 'users', 'blogTags')->where('slug', $slug)->first();
        $recentPages = BlogPosts::orderBy('date', 'desc')->take(3)->get();
    
        // Process the gallery images
        $galleryHtml = '';
        if (!empty($page->gallery_images)) {
            $galleryImages = json_decode($page->gallery_images, true);
            $galleryHtml = view('partials.gallery', ['galleryImages' => $galleryImages])->render();
        }
    
        // Replace {{ gallery }} placeholder with the gallery HTML
        $page->body = str_replace('{{gallery}}', $galleryHtml, $page->body);
    
        return view('blog.show', compact('page', 'recentPages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('Admin');

        $page = new BlogPosts;
        $page->date = $request->date;
        $page->title = $request->title;
        $page->summary = $request->summary;
        $page->slug = Str::slug($page->title, '-');
        $page->body = $request->body;
        $page->user_id = Auth::user()->id;
        $page->categories_id = $request->category;

        if ($request->hasFile('image')) {
            $imagePaths = $this->imageService->handleImageUpload($request->file('image'));
            $page->original_image = $imagePaths['original'];
            $page->small_image = $imagePaths['small'];
            $page->medium_image = $imagePaths['medium'];
            $page->large_image = $imagePaths['large'];
        }

        $galleryPaths = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $paths = $this->imageService->handleGalleryImageUpload($image);
                $galleryPaths[] = $paths;
            }
        }
        $page->gallery_images = json_encode($galleryPaths);

        $uploadedPaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $this->imageService->optimizeAndSaveImage($image);
                $uploadedPaths[] = $imagePath;
            }
        }
        $page->images = json_encode($uploadedPaths);
        $page->published = $request->has('published') ? 1 : 0;
        $page->featured = $request->has('featured') ? 1 : 0;
        $page->save();

        BlogTags::StoreTags($request->tags, $page->slug);

        return redirect()->action([BlogController::class, 'index'])->with('success', 'Post created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        Gate::authorize('Admin');
        $page = BlogPosts::findOrFail($id);
        $categories = BlogCategories::all();
        $split_tags = BlogTags::TagsForEdit($id);
        $galleryImages = json_decode($page->gallery_images, true);

        return view('blog.edit', compact('page', 'categories', 'split_tags', 'galleryImages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Gate::authorize('Admin');

        $page = BlogPosts::findOrFail($id);
        $page->date = $request->date;
        $page->title = $request->title;
        $page->summary = $request->summary;
        $page->slug = Str::slug($page->title, '-');
        $page->body = $request->body;
        $page->user_id = Auth::user()->id;
        $page->categories_id = $request->category;

        if ($request->hasFile('image')) {
            $this->imageService->deleteImage([
                $page->original_image,
                $page->small_image,
                $page->medium_image,
                $page->large_image
            ]);

            $imagePaths = $this->imageService->handleImageUpload($request->file('image'));
            $page->original_image = $imagePaths['original'];
            $page->small_image = $imagePaths['small'];
            $page->medium_image = $imagePaths['medium'];
            $page->large_image = $imagePaths['large'];
        }

        $galleryPaths = json_decode($page->gallery_images, true) ?? [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $paths = $this->imageService->handleGalleryImageUpload($image);
                $galleryPaths[] = $paths;
            }
        }
        $page->gallery_images = json_encode($galleryPaths);

        $uploadedPaths = json_decode($page->images, true) ?? [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $this->imageService->optimizeAndSaveImage($image);
                $uploadedPaths[] = $imagePath;
            }
        }
        $page->images = json_encode($uploadedPaths);
        $page->published = $request->has('published') ? 1 : 0;
        $page->featured = $request->has('featured') ? 1 : 0;
        $page->save();

        BlogTags::StoreTags($request->tags, $page->slug);

        return back()->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Gate::authorize('Admin');

        $page = BlogPosts::findOrFail($id);
        $this->imageService->deleteImage([
            $page->original_image,
            $page->small_image,
            $page->medium_image,
            $page->large_image
        ]);

        $additionalImages = json_decode($page->images, true);
        if ($additionalImages) {
            foreach ($additionalImages as $imagePath) {
                $this->imageService->deleteImage($imagePath);
            }
        }

        $galleryImages = json_decode($page->gallery_images, true);
        if ($galleryImages) {
            $this->imageService->deleteGalleryImages($galleryImages);
        }

        $page->delete();

        return back()->with('success', 'Post deleted successfully!');
    }
}