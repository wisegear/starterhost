<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Timeline;

class TimelineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // Determine if a year has been selected and action.
        if (isset($_GET['year']))
        {
           $year = $_GET['year'];
  
           // If the year is < 1933
           if ( $year === 'earlier' ) {
  
              $events = Timeline::where('published', true)->whereYear('date', '<', 1933)->orderBy('date', 'asc')->paginate(50);
           
           // If the year is > 1945
           } elseif ( $year === 'later' ) {
  
              $events = Timeline::where('published', true)->whereYear('date', '>', 1945)->orderBy('date', 'asc')->paginate(50);
           
           // If the year is between 1933-1945
           } else {
  
              $events = Timeline::where('published', true)->whereYear('date', $year)->orderBy('date', 'asc')->paginate(50);
  
           }
        
        // did the user use the search box?
        } elseif (isset($_GET['search']))
        {
           $events = Timeline::where('published', true)
              ->where(function ($query) {
                 $query->where('title', 'LIKE', '%' . $_GET['search'] . '%')
                    ->orWhere('text', 'LIKE', '%' . $_GET['search'] . '%');
                    
           })->paginate(50);
  
        } else {    
           $events = Timeline::where('published', true)->orderBy('date', 'asc')->paginate(50);
        }

        $years = Timeline::all()->map(function($query) {
            return $query->date->year;
        })->unique()
          ->filter(function ($year) {
            return $year >= 1933 && $year <= 1945; // Filter the years between 1933 and 1945
        })
        ->sort();

        return view('timeline.index', compact('events', 'years'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('Admin');
        return view('timeline.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('Admin');

        $validated = $request->validate([
            'timeline_date' => 'required|date',
            'timeline_title' => 'required|string|max:255',
            'timeline_text' => 'required|string',
        ]);

        $new_event = new Timeline;

        $new_event->date = $validated['timeline_date'];
        $new_event->title = $validated['timeline_title'];
        $new_event->text = $validated['timeline_text'];

        $new_event->published = $request->has('published') ? 1 : 0;

        $new_event->save();

        return back()->with('success', 'Event Successfully Added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $event = Timeline::find($id);

        return view('timeline.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Gate::authorize('Admin');

        $validated = $request->validate([
            'timeline_date' => 'required|date',
            'timeline_title' => 'required|string|max:255',
            'timeline_text' => 'required|string',
        ]);

        $update_event = Timeline::find($id);

        $update_event->date = $validated['timeline_date'];
        $update_event->title = $validated['timeline_title'];
        $update_event->text = $validated['timeline_text'];

        $update_event->published = $request->has('published') ? 1 : 0;

        $update_event->save();

        return back()->with('edited', 'Event Successfully Updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Gate::authorize('Admin');
        
        Timeline::destroy($id);

        return back();
    }
}
