<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaceVideoTag extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function videos()
    {
        return $this->belongsToMany(PlaceVideo::class, 'place_tag_videos');
    }
}
