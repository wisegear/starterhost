<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quote;

class AdminQuotesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $pages = Quote::orderBy('id', 'desc')->paginate(25);

        return view('admin.quotes.index', compact('pages'));
    }
}
