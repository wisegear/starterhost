<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Links;
use App\Models\LinksCategories;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use App\Services\LinkService;

class LinksController extends Controller
{
    protected $linkService;

    public function __construct(LinkService $linkService)
    {
        $this->linkService = $linkService; // ✅ Ensures correct reference
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       // If the visitor uses the search box.
       $categoryName = $_GET['category'] ?? null;
    
       if ($categoryName) {
           $links = Links::whereHas('link_category', function ($query) use ($categoryName) {
               $query->where('name', $categoryName);
           })->with('link_category')->orderBy('id', 'asc')->paginate(6);
           
           // Append category query parameter to pagination links
           $links->appends(['category' => $categoryName]);
       } else {
           $links = Links::orderBy('id', 'asc')->paginate(6);
       }
    
       $categories = LinksCategories::all();
       $unpublished = Links::where('published', false)->get();
    
       return view('links.index', compact('links', 'categories', 'unpublished'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = LinksCategories::all();
        return view('links.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('Admin');

        $page = new Links;
        $page->title = $request->title;
        $page->url = $request->url;
        $page->slug = Str::slug($request->title, '-');
        $page->description = $request->text;
        $page->category_id = $request->category;
        $page->published = $request->has('published') ? 1 : 0;

        if ($request->hasFile('image')) {
            $imagePath = $this->linkService->handleLinkImageUpload($request->file('image'));
            $page->image = $imagePath;
        }

        $page->save();

        return redirect()->action([LinksController::class, 'index'])->with('success', 'Link created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $page = Links::findOrFail($id);
        $categories = LinksCategories::all();
        return view('links.edit', compact('page', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        Gate::authorize('Admin');

        $page = Links::findOrFail($id);
        $page->title = $request->title;
        $page->url = $request->url;
        $page->slug = Str::slug($request->title, '-');
        $page->description = $request->text;
        $page->category_id = $request->category;
        $page->published = $request->has('published') ? 1 : 0;

        if ($request->hasFile('image')) {
            $page->image = $this->linkService->updateImage($request->file('image'), $page->image);
        }

        $page->save();

        return redirect()->action([LinksController::class, 'index'])->with('success', 'Link updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Gate::authorize('Admin');

        $page = Links::findOrFail($id);

        if ($page->image) {
            $this->linkService->deleteImage($page->image);
        }

        $page->delete();

        return back()->with('success', 'Link deleted successfully!');
    }
}

