<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Dashboard content that looks like a card.
     */
    public function getServices()
    {
        return response()->json(config('dashboard.services'));
    }
}
