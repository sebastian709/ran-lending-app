<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    use SoftDeletes;

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
