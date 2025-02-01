<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryTag extends Model
{
    use HasFactory;
    protected $table = 'gallery_tags';
    protected $fillable = ['name'];
    public $timestamps = false;

    public function GalleryTags()
    {
        return $this->belongsToMany(GalleryTag::class);
    }

    public function ImageTags() 
    {
        return $this->belongsToMany(GalleryImage::class, 'gallery_image_tag');
    }

    public function images()
    {
        return $this->belongsToMany(GalleryImage::class, 'gallery_image_tag', 'tag_id', 'gallery_image_id');
    }
}