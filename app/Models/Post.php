<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function images() {
        return $this->hasMany(Image::class);
    }

    public function likes() {
        return $this->hasMany(Like::class);
    }

    public function labels() {
        return $this->belongsToMany(Label::class, 'post_label');
    }

    public function tags() {
        return $this->belongsToMany(Tag::class);
    }
}
