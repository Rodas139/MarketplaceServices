<?php

namespace App\Http\Controllers;

use App\Models\Website\PhotoGalleryAlbum;
use App\Models\Website\PhotoGalleryCategory;
use App\Models\Website\Slider;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->setContent('home')->render();
    }
}
