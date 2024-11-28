<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaceVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'place_id',
        'name',
        'description',
        'image',
        'youtube_url',
        'twitter_url',
        'twitter_video_url',
        'publisher'
    ];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function tags()
    {
        return $this->belongsToMany(PlaceVideoTag::class, 'place_tag_videos');
    }
}
