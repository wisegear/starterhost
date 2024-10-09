<?php

namespace App\Http\Controllers;
use App\Models\Quote;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;


class QuotesController extends Controller
{

    public function __construct()
    {
         // Handle user authentication for each of the methods.
       $this->middleware('auth', ['except' => ['index', 'show']]);      
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        /* If the visitor has searched the quotes do this */

        if (isset($_GET['search'])) {
           
            $quotes = Quote::orderBy('id', 'desc')->where('published', true)
                ->where(function ($query) {
                    $query->where('author', 'LIKE', '%' . $_GET['search'] . '%')
                        ->orWhere('text', 'LIKE', '%' . $_GET['search'] . '%');
                })->paginate(12);
        
        /* If there was no search return all published quotes */  

        } elseif (isset($_GET['author'])) {

            $author = str_replace('-' , ' ', $_GET['author']);

            $quotes = Quote::orderBy('id', 'desc')->where(function ($query) use ($author) {
                $query->where('author', 'LIKE', '%' . $author . '%')
                      ->where('published', true);
             })->paginate(12);            
        
        } else {

            $quotes = Quote::orderBy('id', 'desc')->where('published', true)->paginate(12);

        }

        /* Get top x author names */

        $top_authors = Quote::where('published', true)->distinct()->limit(10)->pluck('author');

        /* Get unpublished quotes */

        $unpublished = Quote::where('published', false)->get();

        /* Get total number of quotes */

        $numberOfQuotes = Quote::count();

        return view('quotes.index', compact('quotes', 'top_authors', 'unpublished', 'numberOfQuotes'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        Gate::authorize('Admin');

        return view('quotes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        Gate::authorize('Admin');

        $validated = $request->validate([
            'quote_author' => 'required|string|max:255',
            'quote_text' => 'required|string',
        ]);

        $new_quote = new Quote;

        $new_quote->author = $validated['quote_author'];
        $new_quote->text = $validated['quote_text'];

        $new_quote->published = $request->has('published') ? 1 : 0;

        $new_quote->save();

        return back()->with('success', 'Quote Successfully Added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //  Not using this one.
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        Gate::authorize('Admin');

        $quote = Quote::find($id);
        
        return view('quotes.edit', compact('quote'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        Gate::authorize('Admin');

        $validated = $request->validate([
            'quote_author' => 'required|string|max:255',
            'quote_text' => 'required|string',
        ]);

        $edit_quote = Quote::find($id);

        $edit_quote->author = $validated['quote_author'];
        $edit_quote->text = $validated['quote_text'];

        $edit_quote->published = $request->has('published') ? 1 : 0;

        $edit_quote->save();

        return back()->with('success', 'Quote Successfully edited!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        Gate::authorize('Admin');
        
        Quote::destroy($id);

        return back();
    }
}
