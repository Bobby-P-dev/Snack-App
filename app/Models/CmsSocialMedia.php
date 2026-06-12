<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsSocialMedia extends Model
{
    protected $table = 'cms_social_medias';
    protected $fillable = ['platform', 'url', 'icon_name', 'order', 'is_active'];
}
