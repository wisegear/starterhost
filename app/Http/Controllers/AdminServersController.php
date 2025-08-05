<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servers;

class AdminServersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $servers = Servers::with('hosting')->get();
                
        return view('admin.servers.index', compact('servers'));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.servers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Validate the request data; adjust rules as needed (e.g., URL validation)
        $validatedData = $request->validate([
            'provider'  => 'required|string',
            'location'  => 'required|string',
            'cpanelUrl' => 'required|url', 
            'whmUrl'    => 'required|url', 
            'apiKey'    => 'required|string',
            'username'  => 'required|string',
            'package'   => 'required|string',
            'ns1'       => 'required|string',
            'ns2'       => 'required|string',
            'ip'        => 'required|ip', // Validates a proper IP address
        ]);

    // Create a new server record in the database
    Servers::create($validatedData);

    // Redirect back to the index page with a success message
    return redirect()
            ->route('servers.index')
            ->with('success', 'Server created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not used.
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $server = Servers::findOrFail($id);

        return view('admin.servers.edit', compact('server'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Servers $server)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'provider'  => 'required|string',
            'location'  => 'required|string',
            'cpanelUrl' => 'required|url',
            'whmUrl'    => 'required|url',
            'apiKey'    => 'required|string',
            'username'  => 'required|string',
            'package'   => 'required|string',
            'ns1'       => 'required|string',
            'ns2'       => 'required|string',
            'ip'        => 'required|ip',
        ]);

        // Update the server record with the validated data
        $server->update($validatedData);

        // Redirect to the server index page with a success message
        return redirect()
                ->route('servers.index')
                ->with('success', 'Server updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Servers $server)
    {
        $server->delete();

        return redirect()
               ->route('servers.index')
               ->with('success', 'Server deleted successfully!');
    }
}
