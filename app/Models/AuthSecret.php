<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthSecret extends Model
{
    use HasFactory;

    protected $fillable = [
        "client_access_token", "client_refresh_token", "app_id"
    ];

    public function app() {
        return $this->belongsTo(App::class);
    }
}
