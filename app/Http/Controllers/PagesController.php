<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\BlogPosts;
use Carbon\Carbon;

class PagesController extends Controller
{
    public function home() {


        //get x most recent posts
        $posts = BlogPosts::where('published', true)
            ->with('blogCategories')
            ->orderBy('date', 'desc')
            ->take(4)
            ->get();
    
         return view('home', compact('posts'));
    } 

    public function contact() {

        return view('contact');
    }

    public function about() {

        return view('about');
    }
}
