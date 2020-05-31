<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\PostCategory;
use App\Http\Helpers\Text;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostCategoryController extends Controller
{
    protected $postCategory;

    public function __construct(PostCategory $postCategory)
    {
        $this->postCategory = $postCategory;
    }
    /**
     * Get all resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Text $text)
    {

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

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $postId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Posts $posts, $postId)
    {
        if ($posts->destroy($postId)) {
            return response()->json([
                'msg' => 'The selected blog post was deleted.'
            ]);
        }

        return response()->json([
            'msg' => 'The selected blog post was not deleted, resources not found.'
        ], 404);
    }
}
