<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $fillable = [
        'title',
        'excerpt',
        'content',
        'category',
        'featured_image',
        'tags_json',
        'status',
    ];

    protected $casts = [
        'tags_json' => 'array',
    ];
}
