<?php


namespace App\Http\Controllers\App;


use App\Http\Controllers\Controller;

class NavigationController extends Controller
{
    public function dashboard()
    {
        return view('dashboard.index');
    }

    public function candidates()
    {
        return view('candidates.index');
    }

}