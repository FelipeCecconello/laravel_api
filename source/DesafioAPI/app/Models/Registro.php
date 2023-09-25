<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    use HasFactory;

    protected $fillable = [
        'deleted',
        'type',
        'message',
        'is_identified',
        'whistleblower_name',
        'whistleblower_birth',
        'created_at',
    ];

    public $timestamps = false;
}
