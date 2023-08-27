<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function posts() {
        return $this->belongsToMany(Post::class, 'post_label');
    }
}
