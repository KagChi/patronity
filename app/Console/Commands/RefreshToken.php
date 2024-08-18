<?php

namespace App\Console\Commands;

use App\Models\AuthSecret;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RefreshToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refresh-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto refresh existing Patreon Tokens';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        AuthSecret::chunk(50, function ($authSecrets) {
            foreach ($authSecrets as $authSecret) {
                try {
                    $response = Http::withOptions([])
                        ->withHeaders([
                            'Content-Type' => 'application/json',
                        ])
                        ->post("https://www.patreon.com/api/oauth2/token?" . http_build_query([
                            'grant_type' => 'refresh_token',
                            'refresh_token' => $authSecret->client_refresh_token,
                            'client_id' => $authSecret->client_id,
                            'client_secret' => $authSecret->client_secret,
                        ]), []);

                    $responseBody = $response->json();

                    if ($response->successful() && isset($responseBody['access_token']) && isset($responseBody['refresh_token'])) {
                        AuthSecret::updateOrCreate(
                            ['id' => $authSecret->id],
                            [
                                'client_access_token' => $responseBody['access_token'],
                                'client_refresh_token' => $responseBody['refresh_token'],
                                'expires_at' => now()->addSeconds($responseBody['expires_in'])->toDateTimeString()
                            ]
                        );

                        Log::info('Patreon tokens updated successfully', ['id' => $authSecret->id]);
                    } else {
                        Log::error('Failed to refresh Patreon tokens', [
                            'id' => $authSecret->id,
                            'response_status' => $response->status(),
                            'response_body' => $responseBody
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Exception occurred while refreshing Patreon tokens', [
                        'id' => $authSecret->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }
        });
    }
}
