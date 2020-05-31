<?php
namespace App\Http\Traits;

trait FeaturedImage
{
    /**
     * @param [type] $request
     * @param [type] $storage
     * @param [type] $post
     */
    protected function saveFeaturedImage($request, $storage, $post)
    {
        if ($request->hasFile('featured_image')) {
            $storage->savePostFeaturedImage($post->id, $request->featured_image);

            $post->update(['featured_image' => $storage->getPublicPath()]);
        }
    }
}
