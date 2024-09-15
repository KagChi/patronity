<?php

namespace App\Console\Commands;

use App\Models\App;
use App\Models\AuthSecret;
use App\Models\Member;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RefreshMember extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refresh-member';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto refresh existing Patreon Members';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        App::chunk(10, function ($apps) {
            foreach ($apps as $app) {
                try {
                    $authSecret = AuthSecret::where('app_id', $app->id)
                        ->firstOrFail();


                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $authSecret->client_access_token,
                    ])->get("https://www.patreon.com/api/oauth2/v2/campaigns/" . $app->patreon_id . "/members?include=user,currently_entitled_tiers&fields[member]=patron_status,email,last_charge_date,last_charge_status,next_charge_date,note,patron_status,pledge_relationship_start&fields[user]=full_name,social_connections&page[size]=10");

                    $result = $response->json();

                    Log::info("tes", $result);

                    while (isset($result['links']) && isset($result['links']['next'])) {
                        $nextResponse = Http::withHeaders([
                            'Authorization' => 'Bearer ' . $authSecret->client_access_token,
                        ])->get($result['links']['next']);

                        $next = $nextResponse->json();

                        $result['data'] = array_merge($result['data'], $next['data']);
                        $result['included'] = array_merge($result['included'] ?? [], $next['included'] ?? []);
                        if (isset($next['links']) && isset($next['links']['next'])) {
                            $result['links'] = $next['links'];
                        } else {
                            break;
                        }
                    }

                    $members = [];
                    foreach ($result['data'] as $member) {
                        $userId = $member['relationships']['user']['data']['id'];
                        $includedUser = array_filter($result['included'], function ($x) use ($userId) {
                            return $x['id'] === $userId;
                        });

                        $includedUser = array_pop($includedUser);

                        if ($includedUser && isset($includedUser['attributes']['social_connections']['discord']) && $includedUser['attributes']['social_connections']['discord'] !== null) {
                            $members[] = array_merge($member, ['included' => $includedUser]);
                        }
                    }

                    Log::info("tes", $members);

                    foreach ($members as $memberData) {
                        $existingMember = Member::where('email', $memberData['attributes']['email'])->first();
                        if ($existingMember) {
                            $existingMember->update([
                                'name' => $memberData['included']['attributes']['full_name'],
                                'discord' => $memberData['included']['attributes']['social_connections']['discord'] ? $memberData['included']['attributes']['social_connections']['discord']['user_id'] : null,
                                'tier' => $memberData['relationships']['currently_entitled_tiers']["data"] ? $memberData['relationships']['currently_entitled_tiers']["data"][0]['id'] : null,
                                'status' => $memberData['attributes']['patron_status'],
                                'join_date' => $memberData['attributes']['pledge_relationship_start'],
                                'last_charge_date' => $memberData['attributes']['last_charge_date'],
                                'next_charge_date' => $memberData['attributes']['next_charge_date'],
                                'cancel_date' => $memberData['attributes']['patron_status'] === 'declined' ? now() : null
                            ]);
                        } else {
                            Member::create([
                                'name' => $memberData['included']['attributes']['full_name'],
                                'email' => $memberData['attributes']['email'],
                                'discord' => $memberData['included']['attributes']['social_connections']['discord'] ? $memberData['included']['attributes']['social_connections']['discord']['user_id'] : null,
                                'tier' => $memberData['relationships']['currently_entitled_tiers']["data"] ? $memberData['relationships']['currently_entitled_tiers']["data"][0]['id'] : null,
                                'status' => $memberData['attributes']['patron_status'],
                                'join_date' => $memberData['attributes']['pledge_relationship_start'],
                                'last_charge_date' => $memberData['attributes']['last_charge_date'],
                                'next_charge_date' => $memberData['attributes']['next_charge_date'],
                                'cancel_date' => $memberData['attributes']['patron_status'] === 'declined' ? now() : null,
                                'app_id' => $app->id,
                            ]);
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Exception occurred while refreshing Patreon members', [
                        'id' => $app->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }
        });
    }
}
