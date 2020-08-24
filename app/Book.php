<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'author_id', 'price', 'description', 'stock'
    ];

    /**
     * Get the post that owns the comment.
     */
    public function author()
    {
        return $this->belongsTo('App\Author');
    }
     /**
     * Each tag can have many links.
     *
     */
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
