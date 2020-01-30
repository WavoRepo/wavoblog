<?php

namespace App\Http\Controllers;

use Auth;
use App\Posts;
use App\Http\Helpers\Text;
use App\Http\Helpers\Storage;
use Illuminate\Http\Request;

class PostsController extends Controller
{

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
        // From Api
        if ($request->wantsJson()) {
            $query = $this->post->with($this->postOwner());

            // Fetch by ID
            if ($request->has('id')) {
                $posts = $query->where('id', $request->id)->first();

                return response()->json([
                    'posts' => $posts
                ]);
            }
            //  Fetch all
            if ($request->has('frontpage')) {
                $query = $query->where('status', 'Published');
            }

            $posts = $query->orderBy('created_at', 'DESC')->get();

            return response()->json([
                'posts' => $posts
            ]);
        }

        // load View template for frontend
        return view('blogpage', [
            'user' => auth()->user(),
            'base_url' => config('app.url'),
            'hasPost' =>$this->post->where('status', 'Published')->exists()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Text $text)
    {
        $user = auth()->user();

        $this->post->user_id = $user->id;
        $this->post->post_title = $request->title;
        $this->post->post_content = $request->content;
        $this->post->post_slug = $text->slugify($request->title);
        $this->post->status = $request->status;
        $this->post->save();

        // Process Image then add to post
        if ($request->hasFile('featured_image')) {
            $this->storage->savePostFeaturedImage($this->post->id, $request->featured_image);

            $this->post->featured_image = $this->storage->getPublicPath();
            $this->post->save();
        }

        return response()->json([
            'post' => $this->post->load($this->postOwner())
        ]);
    }

    /**
     * Get the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $postId
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $postId)
    {
        // This function is not yet used so we return the arguments
        return response()->json([
            'post-id' => $postId,
            'request' => $request->all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $postId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Text $text, $postId)
    {
        $post = $this->post->where('id', $postId)->first();

        // Update data
        $post->post_title = $request->title;
        $post->post_content = $request->content;
        $post->post_slug = $text->slugify($request->slug);
        $post->status = $request->status;

        // Process Image then add to post
        if ($request->hasFile('featured_image')) {
            $this->storage->savePostFeaturedImage($postId, $request->featured_image);

            $post->featured_image = $this->storage->getPublicPath();
        }

        // Then save
        $post->save();

        return response()->json([
            'post' => $post->load($this->postOwner())
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $postId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Posts $posts, $postId)
    {
        try {
            $posts->destroy($postId);
            return response()->json([
                'msg' => 'Thethe blog post was deleted.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
     * A query function for the owner of post with selected data
     */
    protected function postOwner()
    {
        return ['owner' => function ($query) {
            return $query->select('id', 'name', 'email');
        }];
    }
}
