<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'discord',
        'tier',
        'status',
        'join_date',
        'last_charge_date',
        'next_charge_date',
        'cancel_date',
        'access_expiration_date',
        'app_id',
        'will_pay_amount_cents'
    ];

    public function app()
    {
        return $this->belongsTo(App::class);
    }
}
