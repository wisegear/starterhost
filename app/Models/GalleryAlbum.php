<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryAlbum extends Model
{
    use HasFactory;
    protected $table = 'gallery_albums';
    public $timestamps = false;

    public function GalleryCategory() {
        return $this->belongsTo(GalleryCategory::class, 'category_id', 'id');
    }

    public function GalleryImages() {
        return $this->hasMany(GalleryImage::class, 'album_id', 'id');
    }
}