<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;
use App\Providers\RouteServiceProvider;

class ProfileController extends Controller
{
    public function index()
    {
        $profiles = Profile::all();
        return view('admin.profile.index', compact('profiles'));
    }
}
