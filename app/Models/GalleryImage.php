<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class GalleryImage extends Model
{
    use HasFactory;
    protected $table = 'gallery_images';
    protected $fillable = [
        'image', 
        'title', 
        'date_taken', 
        'location', 
        'summary', 
        'text', 
        'album_id', 
        'user_id', 
        'slug',
        'name',
    ];
    public $timestamps = false;

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function tags() {
        return $this->belongsToMany(GalleryTag::class, 'gallery_image_tag', 'gallery_image_id', 'tag_id'); 
    }

    public function album() {
        return $this->belongsTo(GalleryAlbum::class, 'album_id', 'id');
    }

    public function category()
    {
        return $this->hasOneThrough(
            GalleryCategory::class,  // The related model (Category)
            GalleryAlbum::class,    // The intermediate model (Album)
            'id',                   // Foreign key on the albums table
            'id',                   // Foreign key on the categories table
            'album_id',             // Local key on the images table
            'category_id'           // Local key on the albums table
        );
    }

    public function User() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}