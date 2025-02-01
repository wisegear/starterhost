<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryCategory extends Model
{
    use HasFactory;
    protected $table = 'gallery_categories';
    protected $fillable = ['name'];
    public $timestamps = false;

    public function albums()
    {
        return $this->hasMany(GalleryAlbum::class, 'category_id');
    }
}