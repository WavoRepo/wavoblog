<?php

namespace App\Http\Controllers;

use Auth;
use App\Posts;
use App\Http\Helpers\Text;
use App\Http\Helpers\Storage;
use App\Http\Requests\PostUpdateRequest;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $post;
    protected $storage;

    public function __construct(Posts $post, Storage $storage)
    {
        $this->post = $post;
        $this->storage = $storage;
    }
    /**
     * Get all resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // load View template for frontend
        return view('blogpage', [
            'user' => auth()->user(),
            'base_url' => config('app.url'),
            'hasPost' =>$this->post->where('status', 'Published')->exists()
        ]);
    }
}
