<?php

namespace App\Http\Controllers;

use Auth;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Request $request)
    {
        $user = Auth::user();

        // Fist page to display
        $page = 'dashboard';

        // Override page if the page beside dashboard
        $segments = $request->segments();
        if(count($segments)) {
            $page = end($segments);
        }

        return view('admin', [
            'user' => $user,
            'page' => $page,
            'base_url' => config('app.url')
        ]);
    }
}
