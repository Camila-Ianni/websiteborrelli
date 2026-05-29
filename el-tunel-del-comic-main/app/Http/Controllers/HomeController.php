<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use App\Models\Publisher;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $publishers = Publisher::all();
        $newReleases = Comic::where('is_new_release', true)
            ->with('publisher')
            ->latest()
            ->take(4)
            ->get();
        $featured = Comic::where('is_featured', true)
            ->with('publisher')
            ->latest()
            ->take(3)
            ->get();

        return view('home', compact('publishers', 'newReleases', 'featured'));
    }
}
