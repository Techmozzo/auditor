<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'auditor_addresses';

    protected $fillable = ['house_number', 'city', 'state', 'country', 'zip_code', 'auditor_id'];

}
