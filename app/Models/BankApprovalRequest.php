<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankApprovalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'confirmation_request_id','bank_id','approved_by','declined_by','status','comment'
    ];
}
