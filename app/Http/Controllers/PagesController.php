<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\BlogPosts;
use App\Models\GalleryImage;
use App\Models\Quote;

class PagesController extends Controller
{
    public function home() {

        // Get x random gallery images
        $gallery = GalleryImage::orderby('id', 'desc')->with('album')->inRandomOrder()->take(4)->get();

        // Get a random quote
        $quote = Quote::inRandomOrder()->first();

        //get x most recent posts
        $posts = BlogPosts::where('published', true)
            ->with('blogCategories')
            ->orderBy('date', 'desc')
            ->take(4)
            ->get();
    
         return view('home', compact('posts', 'gallery', 'quote'));
    }

    public function article(String $slug) {

        // Get the current article id
        $page = Article::with('articles', 'user')->where('slug', $slug)->first();

        // Get all articles related to the article category
        $allPages = $page->articles->article()->select('id', 'slug', 'title')->get();

        // Get 3 most recent blog posts
        $posts = BlogPosts::orderBy('date', 'desc')->take(3)->get();

        return view('article.show', compact('page', 'allPages', 'posts'));
    }      

    public function contact() {

        return view('contact');
    }

    public function about() {

        return view('about');
    }
}
