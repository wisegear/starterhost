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
use App\Services\BlogService;

class BlogController extends Controller
{
    protected $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    public function index()
    {

        // Determine what is returned based on visitor pattern (Search, category, tag or nothing).
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

        // Get the x most recent featured posts and display in the sidebar.

        $featured = BlogPosts::where('featured', true)->orderBy('date', 'desc')->take(3)->get();

        // Get all of the categories to display in the sidebar.
        $categories = BlogCategories::all();

        // Sort the post tags and return the x most popular ones in desc order.
        $popular_tags = DB::table('blog_post_tags')
            ->leftJoin('blog_tags', 'blog_tags.id', '=', 'blog_post_tags.tag_id')
            ->select('blog_post_tags.tag_id', 'name', DB::raw('count(*) as total'))
            ->groupBy('blog_post_tags.tag_id', 'name')
            ->orderBy('total', 'desc')
            ->limit(15)
            ->get();

        // For the admin, display the posts that are unpublished in the sidebar when admin is logged in.
        $unpublished = BlogPosts::where('published', false)->get();

        return view('blog.index', compact('posts', 'categories', 'popular_tags', 'unpublished', 'featured'));
    }

    public function create()
    {
        Gate::authorize('Admin');
        $categories = BlogCategories::all();
        return view('blog.create', compact('categories'));
    }

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
            $page->image = $this->blogService->handleImageUpload($request->file('image'));
        }

        $galleryPaths = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $paths = $this->blogService->handleGalleryImageUpload($image);
                $galleryPaths[] = $paths;
            }
        }
        $page->gallery_images = json_encode($galleryPaths);

        $uploadedPaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $uploadedPaths[] = $this->blogService->optimizeAndSaveImage($image);
            }
        }
        $page->images = json_encode($uploadedPaths);
        $page->published = $request->has('published') ? 1 : 0;
        $page->featured = $request->has('featured') ? 1 : 0;
        $page->save();

        BlogTags::StoreTags($request->tags, $page->slug);

        return redirect()->action([BlogController::class, 'index'])->with('success', 'Post created successfully!');
    }

    public function show(string $slug)
    {
        $page = BlogPosts::with('BlogCategories', 'users', 'blogTags')->where('slug', $slug)->firstOrFail();
        $recentPages = BlogPosts::orderBy('date', 'desc')->take(3)->get();
        
        // Get the x most recent featured posts and display in the sidebar.
        $featured = BlogPosts::where('featured', true)->orderBy('id', 'desc')->take(3)->get();
    
        // Process the gallery images
        $galleryHtml = '';
        if (!empty($page->gallery_images)) {
            $galleryImages = json_decode($page->gallery_images, true);
            $galleryHtml = view('partials.gallery', ['galleryImages' => $galleryImages])->render();
        }
    
        // Replace {{ gallery }} placeholder with the gallery HTML
        $page->body = str_replace('{{gallery}}', $galleryHtml, $page->body);

        // Prepare "previous" and "next" post queries by date (or by ID)
        $previousPage = BlogPosts::where('date', '<', $page->date)
            ->orderBy('date', 'desc')
            ->first();

        $nextPage = BlogPosts::where('date', '>', $page->date)
            ->orderBy('date', 'asc')
            ->first();
    
        return view('blog.show', compact('page', 'recentPages', 'featured', 'previousPage', 'nextPage'));
    }

    public function edit(string $id)
    {
        Gate::authorize('Admin');
        $page = BlogPosts::findOrFail($id);
        $categories = BlogCategories::all();
        $split_tags = BlogTags::TagsForEdit($id);
        $galleryImages = json_decode($page->gallery_images, true);

        return view('blog.edit', compact('page', 'categories', 'split_tags', 'galleryImages'));
    }

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
            $this->blogService->deleteImage($page->image); // Delete old image
            $newImage = $this->blogService->handleImageUpload($request->file('image'));
            $page->image = $newImage; // Update new image path
        }

        $galleryPaths = json_decode($page->gallery_images, true) ?? [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $galleryPaths[] = $this->blogService->handleGalleryImageUpload($image);
            }
        }
        $page->gallery_images = json_encode($galleryPaths);

        $uploadedPaths = json_decode($page->images, true) ?? [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $uploadedPaths[] = $this->blogService->optimizeAndSaveImage($image);
            }
        }
        $page->images = json_encode($uploadedPaths);
        $page->published = $request->has('published') ? 1 : 0;
        $page->featured = $request->has('featured') ? 1 : 0;
        $page->save();

        BlogTags::StoreTags($request->tags, $page->slug);

        return back()->with('success', 'Post updated successfully!');
    }

    public function destroy($id)
    {
        Gate::authorize('Admin');
    
        $page = BlogPosts::findOrFail($id);
    
        // ✅ Delete the main blog image (including small, medium, large versions)
        if ($page->image) {
            $this->blogService->deleteImage($page->image);
        }
    
        // ✅ Delete additional images used inside the blog post
        $additionalImages = json_decode($page->images, true);
        if ($additionalImages) {
            foreach ($additionalImages as $image) {
                $this->blogService->deleteImage($image);
            }
        }
    
        // ✅ Delete gallery images (both original and thumbnails)
        $galleryImages = json_decode($page->gallery_images, true);
        if ($galleryImages) {
            foreach ($galleryImages as $imageSet) {
                $this->blogService->deleteImage($imageSet['original']);
                $this->blogService->deleteImage('thumbnail_' . $imageSet['original']); // ✅ Delete thumbnail too
            }
        }
    
        // ✅ Finally, delete the blog post from the database
        $page->delete();
    
        return back()->with('success', 'Post deleted successfully!');
    }
}