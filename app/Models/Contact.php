<?php

namespace App\Models;

use App\Models\Concerns\ClearsDashboardCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use ClearsDashboardCache;
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'terms_accepted',
        'terms_accepted_time',
        'ip_address',
        'user_agent',
        'is_replied',
        'reply_message',
        'replied_at',
    ];

    protected $casts = [
        'terms_accepted'      => 'boolean',
        'is_replied'          => 'boolean',
        'terms_accepted_time' => 'datetime',
        'replied_at'          => 'datetime',
    ];
}
