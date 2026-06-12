<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsCarousel extends Model
{
    protected $fillable = ['title', 'image_url', 'description', 'link_url', 'order', 'is_active'];
}
