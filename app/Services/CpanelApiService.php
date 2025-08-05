<?php

namespace App\Services;
use App\Models\Servers;
use App\Models\Hosting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;  // Import Log facade

class CpanelApiService
{

    private function getWhmConnection($serverId)
    {
        $server = Servers::findOrFail($serverId);

        return [
            'whmUrl'        => $server->whmUrl,
            'apiKey'        => $server->apiKey,
            'adminUsername' => $server->username,
            'plan'          => $server->package // optional if needed elsewhere
        ];
    }

    public function listCpanelAccounts()
    {

        $server = Servers::find(1);
        
        $whmUrl = $server->whmUrl;
        $apiKey = $server->apiKey;
        $admin_username = $server->username;

        $response = Http::withHeaders([
            'Authorization' => 'whm ' . $admin_username . ':' . $apiKey  // Correct header for WHM API
        ])->get("{$whmUrl}/json-api/listaccts", [
            'api.version' => 1  // Specify API version
        ]);

        Log::debug('cPanel API Response: ', $response->json());
        return $response->json();
    }

    public function createAccount($domain, $contactEmail, $server_id, $user_id)
    {
        $server = Servers::find($server_id);
        $hosting = Hosting::where('user_id', $user_id)->first();

        $whmUrl = $server->whmUrl;
        $apiKey = $server->apiKey;
        $admin_username = $server->username;
        $plan = $server->package;
        set_time_limit(300); // 5 minutes

        $response = Http::withOptions([
            'verify' => false,          // disables SSL verification (safe on trusted internal WHM)
            'http_errors' => false,     // prevents Laravel from throwing exceptions on HTTP 500/502
            'timeout' => 300,           // increases timeout to 5 minutes
        ])->withHeaders([
            'Authorization' => 'whm ' . $admin_username . ':' . $apiKey
        ])->get("{$whmUrl}/json-api/createacct", [
            'api.version'    => 1,
            'username'       => $hosting->username,
            'domain'         => $domain,
            'password'       => $hosting->password,
            'contactemail'   => $contactEmail,
            'plan'           => $plan,
        ]);

        // Debug log: status and raw body
        Log::debug('Create Account Response Status: ' . $response->status());
        Log::debug('Create Account Response Body: ' . $response->body());

        // Try to parse JSON, safely
        try {
            $data = $response->json();
            Log::debug('Create Account Summary:', [
                'status' => $data['metadata']['result'],
                'message' => $data['metadata']['reason'] ?? 'Unknown'
            ]);

            return [
                'status' => $data['metadata']['result'],
                'message' => $data['metadata']['reason'] ?? 'Unknown'
            ];
        } catch (\Throwable $e) {
            Log::error('Failed to decode WHM response: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Invalid response from WHM'
            ];
        }
    }

    public function suspendAccount($username, $server_id)
    {
        $conn = $this->getWhmConnection($server_id);

        // WHM suspendacct API endpoint
        $response = Http::withOptions([
            'verify' => false,
            'http_errors' => false,
            'timeout' => 60,
        ])->withHeaders([
            'Authorization' => 'whm ' . $conn['adminUsername'] . ':' . $conn['apiKey'],
        ])->get("{$conn['whmUrl']}/json-api/suspendacct", [
            'api.version' => 1,
            'user'        => $username,
            'reason'      => 'Suspended by admin' // optional but good practice
        ]);

        Log::debug("Suspend Account Response for {$username}: " . $response->body());

        try {
            $data = $response->json();
            return [
                'status' => $data['metadata']['result'],
                'message' => $data['metadata']['reason'] ?? 'Unknown'
            ];
        } catch (\Throwable $e) {
            Log::error("Failed to decode suspend response: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Invalid response from WHM'
            ];
        }
    }

    public function unsuspendAccount($username, $server_id)
    {
        $conn = $this->getWhmConnection($server_id);

        // WHM suspendacct API endpoint
        $response = Http::withOptions([
            'verify' => false,
            'http_errors' => false,
            'timeout' => 60,
        ])->withHeaders([
            'Authorization' => 'whm ' . $conn['adminUsername'] . ':' . $conn['apiKey'],
        ])->get("{$conn['whmUrl']}/json-api/unsuspendacct", [
            'api.version' => 1,
            'user'        => $username,
            'reason'      => 'Suspended by admin' // optional but good practice
        ]);

        Log::debug("Suspend Account Response for {$username}: " . $response->body());

        try {
            $data = $response->json();
            return [
                'status' => $data['metadata']['result'],
                'message' => $data['metadata']['reason'] ?? 'Unknown'
            ];
        } catch (\Throwable $e) {
            Log::error("Failed to decode unsuspend response: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Invalid response from WHM'
            ];
        }
    }

    public function deleteAccount($username, $server_id)
    {
        $conn = $this->getWhmConnection($server_id);

        // WHM removeacct API endpoint
        $response = Http::withOptions([
            'verify' => false,
            'http_errors' => false,
            'timeout' => 60,
        ])->withHeaders([
            'Authorization' => 'whm ' . $conn['adminUsername'] . ':' . $conn['apiKey'],
        ])->get("{$conn['whmUrl']}/json-api/removeacct", [
            'api.version' => 1,
            'user'        => $username,
            'reason'      => 'Deleted by admin'
        ]);

        Log::debug("Delete Account Response for {$username}: " . $response->body());

        try {
            $data = $response->json();
            return [
                'status' => $data['metadata']['result'],
                'message' => $data['metadata']['reason'] ?? 'Unknown'
            ];
        } catch (\Throwable $e) {
            Log::error("Failed to decode delete response: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Invalid response from WHM'
            ];
        }
    }
    
}
