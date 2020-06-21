<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Category;
use App\Http\Helpers\Text;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    protected $postCategory;

    /**
     * Get all resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function collection(Request $request, Category $category)
    {
        return response()->json([
            'categories' => $category->all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request, Category $category)
    {
        $category = $category->create($request->validated());

        return response()->json([
            'category' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $postId
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return response()->json([
            'category' => $category
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $postId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $parentId = $category->id;
        $category->delete();
        $category->where('parent_id', $parentId)->update(['parent_id' => 0]);

        return response()->json([
            'msg' => 'The selected blog post was deleted.'
        ]);
    }
}
