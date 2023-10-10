<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signatory extends Model
{
    use HasFactory;

    protected $fillable = [
       'name','email','phone','confirmation_request_id','status', 'expired_at', 'token', 'signed_at'
    ];
}
