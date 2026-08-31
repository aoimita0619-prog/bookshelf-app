<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

     protected $fillable = [
        'title',
        'author',
        'ISBN',
        'publish_date',
        'detail',
        'img',
    ];

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'book_genre');
    }

     public function favoriteUsers()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function scopeKeywordSearch($query, $keyword)
    {
        if (!empty($keyword)) {
        $query->where('title', 'like', '%' . $keyword . '%');
        }
        return $query;
    }
}
