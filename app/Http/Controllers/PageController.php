<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    public function home()
    {
        return Inertia::render('Public/Home');
    }

    // , [
    //         'canLogin' => Route::has('login'),
    //         'canRegister' => Route::has('register'),
    //         'laravelVersion' => Application::VERSION,
    //         'phpVersion' => PHP_VERSION,
    //     ]

    public function about()
    {
        return Inertia::render('Public/About');
    }

    public function agent()
    {
        return Inertia::render('Public/Agent');
    }

    public function services()
    {
        return Inertia::render('Public/Services');
    }
    public function properties()
    {
        return Inertia::render('Public/Properties');
    }
    public function contact()
    {
        return Inertia::render('Public/Contact');
    }
}
