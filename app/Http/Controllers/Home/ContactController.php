<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Setting;

class ContactController extends Controller
{
    public function index(){
        $setting=Setting::first();
        $page=Page::find(11);
        return view('home.contact',compact('setting','page'));
    }
}
