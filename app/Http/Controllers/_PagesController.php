<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index()
    {
        $header = 'Welcome to Scheduling!';
        return view('pages.index')->with('title', $title);
    }

    //ToDo: Check if required
     public function about()
    {
        $header = 'Welcome to About!';
        return view('pages.about')->with('title', $title);
    }

    //ToDo: Check if required
    public function services()
    {
        // alternative using an array
        $data = array(
            'header' => 'Welcome to Services!',
            'services' => ['Web Design', 'Programming', 'SEO']
        );
        return view('pages.services')->with($data);
    }
}
