<?php

use App\Http\Middleware\AuthorizationHeader;
use App\Models\App;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware([AuthorizationHeader::class])->group(function () {
    Route::get('/api/{id}/members', function (Request $request, $id) {
        $app = App::where('patreon_id', $id)
            ->first();

        return Member::where('app_id', $app->id)->paginate();
    });

    Route::get('/api/{id}/members/{member_id}', function (Request $request, $id, $member_id) {
        $app = App::where('patreon_id', $id)
            ->first();

        $member = Member::where(['discord' => $member_id, 'app_id' => $app->id])->first();
        if (!$member) {
            return response()->json(['message' => 'Member Not Found'], 404);
        }
        return response()->json(['data' => $member]);
    });
});
