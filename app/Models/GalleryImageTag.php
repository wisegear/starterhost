<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryImageTag extends Model
{
    use HasFactory;
    protected $table = 'gallery_image_tag';
    public $timestamps = false;  

}