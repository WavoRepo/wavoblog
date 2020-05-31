<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
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
    protected $fillable = ['user_id', 'title', 'content', 'slug', 'featured_image', 'status'];

    /**
    * Get the owner of the post.
    */
    public function owner()
    {
        return $this->belongsTo(\App\User::class, 'user_id')->select('id', 'name', 'email');
    }

    /**
    * Scope a query to only include published post.
    *
    * @param  \Illuminate\Database\Eloquent\Builder  $query
    * @return \Illuminate\Database\Eloquent\Builder
    */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
