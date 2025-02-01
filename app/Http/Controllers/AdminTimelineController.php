<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Timeline;

class AdminTimelineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $pages = Timeline::orderBy('id', 'desc')->paginate(25);

        return view('admin.timeline.index', compact('pages'));
    }
}
