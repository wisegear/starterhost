<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CpanelApiService;
use App\Models\Hosting;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Jobs\CreateCpanelAccountJob;

class AdminCpanelController extends Controller
{
    protected $cpanelApi;

    public function __construct(CpanelApiService $cpanelApiService)
    {
        $this->cpanelApi = $cpanelApiService;
    }

    //cPanel Index display for admin
    public function index()
    {
        // Get pending requests
        $pending = Hosting::where('approved', false)->with('user', 'servers')->get();

        // Call cPanel API once
        $apiResponse = $this->cpanelApi->listCpanelAccounts();

        // Check and extract account data
        if (isset($apiResponse['data']['acct'])) {
            $accounts = $apiResponse['data']['acct'];
        } else {
            $accounts = []; // fallback to empty list
            Log::warning('cPanel API returned unexpected response:', $apiResponse);
        }

        return view('admin.cpanel.index', compact('accounts', 'pending'));
    }

    // Reject the users request and reset hosting.
    public function rejectHosting(Request $request) {

        $user_id = $request->input('user_id');

        Hosting::where('user_id', $user_id)->delete();

        return back();

    }

    // Reject the users request and ban them at user level.
    public function banHosting(Request $request) {

        $user_id = $request->input('user_id');
        $user = User::find($user_id);

        Hosting::where('user_id', $user_id)->delete();
        $user->roles()->sync([1]); // sets the "Banned" role only

        return back();

    }

    // New method to create an account
    public function createAccount(Request $request)
    {
        // Prepare the data passed from the pending request ready to send on to the API in Services/cPanelApiServices
        $domain       = $request->input('domain');
        $contactEmail = $request->input('contact_email');
        $server_id    = $request->input('server_id');
        $user_id      = $request->input('user_id');

        // Call the API service to create the account
        $result = $this->cpanelApi->createAccount($domain, $contactEmail, $server_id, $user_id);

        //Set the users hosting account to approved.
        if (isset($result['status']) && $result['status'] === 1) {
            Hosting::where('user_id', $user_id)->update(['approved' => true]);
        }

        return redirect()->back()->with('message', json_encode($result));
    }

    // New method to suspend an account from the server
    public function suspendAccount(Request $request)
    {
        // Retrieve the account identifier, for example a username, from the request
        $username = $request->input('user_account');
        $server_id = $request->input('server_id');

        // Call the API service to suspend the account
        $result = $this->cpanelApi->suspendAccount($username, $server_id);

        return redirect()->back()->with('message', json_encode($result));
    }

    // New method to unsuspend an account from the server
    public function unsuspendAccount(Request $request)
    {
        // Retrieve the account identifier, for example a username, from the request
        $username = $request->input('user_account');
        $server_id = $request->input('server_id');

        // Call the API service to suspend the account
        $result = $this->cpanelApi->unsuspendAccount($username, $server_id);

        return redirect()->back()->with('message', json_encode($result));
    }
 
    // New method to delete an account from the server
    public function deleteAccount(Request $request)
    {
        $username = $request->input('user_account');
        $server_id = $request->input('server_id');

        // Call the API service to delete the account
        $result = $this->cpanelApi->deleteAccount($username, $server_id);

        // If successful, delete the matching hosting record from the DB
        if (isset($result['status']) && $result['status'] === 1) {
            Hosting::where('username', $username)
                   ->where('server_id', $server_id)
                   ->delete();
        }

        return redirect()->back()->with('message', json_encode($result));
    }

}
