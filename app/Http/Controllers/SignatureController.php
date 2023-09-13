<?php

namespace App\Http\Controllers;

use App\Services\SignNow;

class SignatureController extends Controller
{
    public function connect()
    {
        (new SignNow())->checkConnection();
    }
}
