<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryAlbum extends Model
{
    use HasFactory;
    protected $table = 'gallery_albums';
    protected $fillable = ['name', 'category_id'];
    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(GalleryCategory::class, 'category_id');
    }

    public function images() {
        return $this->hasMany(GalleryImage::class, 'album_id', 'id');
    }
}