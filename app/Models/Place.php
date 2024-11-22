<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'address',
        'user_id',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_place');
    }

    public function images()
    {
        return $this->hasMany(PlaceImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(PlaceImage::class)->where('is_primary', true);
    }
}
