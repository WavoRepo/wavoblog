<?php

namespace App\Http\Controllers;

use Auth;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Dashboard content that looks like a card.
     */
    public function GetServices()
    {
        return response()->json(config('dashboard.services'));
    }
}
