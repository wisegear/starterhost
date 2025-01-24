<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    use HasFactory;
    protected $table = 'gallery_images';
    public $timestamps = false;

    public function ImageTags() {
        return $this->belongsToMany(GalleryTag::class, 'gallery_image_tags', 'image_id', 'tag_id'); 
    }

    public function GalleryAlbum() {
        return $this->belongsTo(GalleryAlbum::class, 'album_id', 'id');
    }

    public function User() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}