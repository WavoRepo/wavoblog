<?php

namespace App\Http\Controllers;

use Auth;
use App\Post;
use App\Http\Helpers\Text;
use App\Http\Helpers\Storage;
use App\Http\Requests\PostUpdateRequest;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Get all resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Post $post)
    {
        // load View template for frontend
        return view('blogpage', [
            'user' => auth()->user(),
            'base_url' => config('app.url'),
            'hasPost' => $post->where('status', 'Published')->exists()
        ]);
    }
}
