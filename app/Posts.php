<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'post_title', 'post_content', 'post_slug', 'featured_image', 'status'];

    /**
     * Get the owner of the post.
     */
    public function owner()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }
}
