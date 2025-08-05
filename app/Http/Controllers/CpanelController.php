<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hosting;
use App\Models\Servers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CpanelController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        // Check if the logged in user has hosting if yes return information if false just false.
        $hosting = Hosting::with(['user', 'Servers'])
                                ->where('user_id', $user->id)
                                ->first();

        $hasHosting = !is_null($hosting);
        
        return view('cpanel.index', compact('hasHosting', 'hosting'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $servers = Servers::all();

        return view('cpanel.create', compact('servers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the required fields
        $validatedData = $request->validate([
            'server_id' => 'required|exists:servers,id',
            'domain'    => 'required|string',
            'country'   => 'required|string',
            'reason'    => 'required|string',
        ]);
    
        // Generate a unique cPanel username and a strong password
        $username = $this->generateUniqueCpanelUsername(8); // you can adjust the length (max 16 chars)
        $password = $this->generateCpanelPassword(16); // adjust length as needed

    
        // Create the new hosting record
        $hosting = Hosting::create([
            'user_id'  => Auth::id(),            // Set the authenticated user's ID
            'server_id'=> $validatedData['server_id'],
            'username' => $username,
            'password' => $password,
            'domain'   => $validatedData['domain'],
            'country'  => $validatedData['country'],
            'reason'   => $validatedData['reason'],
            'approved' => false,                 // Set approved to false initially
        ]);
    
        // Redirect the user with a success message
        return redirect()->route('hosting.index')
                         ->with('success', 'Hosting request submitted successfully.');
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

//
// Used to make absolutely sure the username and password generated meet the cPanel rules without errors.
//

/**
 * Generate a unique cPanel-compliant username.
 * The username will start with a letter and contain only lowercase letters and numbers.
 *
 * @param int $length
 * @return string
 */
private function generateUniqueCpanelUsername($length = 8)
{
    do {
        $username = $this->generateCpanelUsername($length);
    } while (Hosting::where('username', $username)->exists());

    return $username;
}

/**
 * Generate a random cPanel username.
 *
 * @param int $length
 * @return string
 */
private function generateCpanelUsername($length)
{
    // Define the character sets
    $letters = 'abcdefghijklmnopqrstuvwxyz';
    $others = 'abcdefghijklmnopqrstuvwxyz0123456789';

    // Ensure the first character is a letter
    $username = $letters[random_int(0, strlen($letters) - 1)];

    // Generate the rest of the username
    for ($i = 1; $i < $length; $i++) {
        $username .= $others[random_int(0, strlen($others) - 1)];
    }

    // Truncate in case length exceeds cPanel's maximum (16 chars)
    return substr($username, 0, 16);
}

/**
 * Generate a strong password that includes a mix of letters, numbers, and symbols.
 *
 * @param int $length
 * @return string
 */
private function generateCpanelPassword($length = 16)
{
    // Define character sets for the password
    $lower   = 'abcdefghijklmnopqrstuvwxyz';
    $upper   = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numbers = '0123456789';
    $symbols = '!@#$%^&*()_-=+;:,.?';

    // A combined pool of characters
    $all = $lower . $upper . $numbers . $symbols;

    // Ensure at least one character from each category is present
    $password = '';
    $password .= $lower[random_int(0, strlen($lower) - 1)];
    $password .= $upper[random_int(0, strlen($upper) - 1)];
    $password .= $numbers[random_int(0, strlen($numbers) - 1)];
    $password .= $symbols[random_int(0, strlen($symbols) - 1)];

    // Fill the remaining length with random characters from the entire pool
    for ($i = 4; $i < $length; $i++) {
        $password .= $all[random_int(0, strlen($all) - 1)];
    }

    // Shuffle the characters to randomize their order and return
    return str_shuffle($password);
}
}
