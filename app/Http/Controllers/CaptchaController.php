<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class CaptchaController extends Controller
{
    public function generate()
    {
        $captcha = Str::random(6);
        Session::put('captcha', $captcha);
        
        return response()->json(['captcha' => $captcha]);
    }
} 