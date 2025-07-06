<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'kommo_deal_id',
        'client_name',
        'client_email',
        'client_phone',
        'amount',
        'due_date',
        'custom_fields',
        'proposal_url',
    ];

    protected $casts = [
        'custom_fields' => 'array',
        'due_date' => 'datetime',
    ];
}
