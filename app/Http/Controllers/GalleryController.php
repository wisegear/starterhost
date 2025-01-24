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

            $category = GalleryCategory::where('name', $_GET['category'])->first();

            $results = GalleryAlbum::with('GalleryCategory')->where('category_id', $category->id)->paginate(16);

        } elseif (isset($_GET['album'])) {

            $album = GalleryAlbum::where('name', $_GET['album'])->first();

            $results = GalleryImage::with('GalleryAlbum')->where('album_id', $album->id)->paginate(16);
        
        } elseif (isset($_GET['tag'])) {

            $tag = GalleryTag::where('name', $_GET['tag'])->first();

            if ($tag) {
                $results = GalleryImage::whereHas('ImageTags', function ($query) use ($tag) {
                    $query->where('gallery_tags.id', $tag->id); // Specify the table name for the 'id' column
                })->paginate(16);
            } else {
                $results = collect(); // Return empty collection if tag is not found
            }            

        } else {

            $results = GalleryImage::paginate(16);
        
        }
        

        $imageTotal = GalleryImage::count();
        $categories = GalleryCategory::all();
        
        $popularTags = DB::table('gallery_image_tags')
            ->leftJoin('gallery_tags', 'gallery_tags.id', '=', 'gallery_image_tags.tag_id')
            ->select('gallery_image_tags.tag_id', 'name', DB::raw('count(*) as total'))
            ->groupBy('gallery_image_tags.tag_id', 'name')
            ->orderBy('total', 'desc')
            ->limit(15)
            ->get();

        return view('gallery.index', compact('imageTotal', 'categories', 'popularTags', 'results'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $page = GalleryImage::with('ImageTags')->where('slug', $slug)->first();

        $imageTotal = GalleryImage::count();
        $categories = GalleryCategory::all();

        $popularTags = DB::table('gallery_image_tags')
            ->leftJoin('gallery_tags', 'gallery_tags.id', '=', 'gallery_image_tags.tag_id')
            ->select('gallery_image_tags.tag_id', 'name', DB::raw('count(*) as total'))
            ->groupBy('gallery_image_tags.tag_id', 'name')
            ->orderBy('total', 'desc')
            ->limit(15)
            ->get();

        return view('gallery.show', compact('imageTotal', 'categories', 'popularTags', 'page'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
