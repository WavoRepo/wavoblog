<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Post;
use App\Http\Helpers\Text;
use App\Http\Helpers\Storage;
use App\Http\Requests\PostRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\FeaturedImage;

class PostController extends Controller
{
    use FeaturedImage;

    /**
     * Get all resource in storage.
     *
     * @param  \App\Posts  $post
     * @return \Illuminate\Http\Response
     */
    public function collection(Post $post)
    {
        return response()->json([
            'posts' => $post->with('owner')->orderBy('created_at', 'DESC')->get()
        ]);
    }

    /**
     * Get all resource in storage.
     *
     * @param  \App\Posts  $post
     * @return \Illuminate\Http\Response
     */
    public function published(Post $post)
    {
        return response()->json([
            'posts' => $post->with('owner')->where('status', 'published')->orderBy('created_at', 'DESC')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Http\Helpers\Storage  $storage
     * @param  \App\Posts  $post
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request, Storage $storage, Post $post)
    {
        $inputs = $request->validated();
        $inputs['user_id'] = auth()->id();

        $post = $post->create($inputs);

        // Process Image then add to post
        $this->saveFeaturedImage($request, $storage, $post);

        return response()->json([
            'post' => $post->load('owner')
        ]);
    }

    /**
     * Get the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $postId
     * @return \Illuminate\Http\Response
     */
    public function find(Post $post)
    {
        return response()->json([
            'methods' => 'show',
            'post' => $post->load('owner')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Http\Helpers\Storage  $storage
     * @param  \App\Posts  $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, Storage $storage, Post $post)
    {
        // Update data
        $post->update($request->validated());

        // Process Image then add to post
        $this->saveFeaturedImage($request, $storage, $post);

        return response()->json([
            'post' => $post->load('owner')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $postId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $posts)
    {
        $posts->delete();

        return response()->json([
            'msg' => 'The selected blog post was deleted.'
        ]);
    }
}
