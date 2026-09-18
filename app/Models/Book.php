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
        'isbn',
        'published_date',
        'description',
        'user_id',
        'image_url',
    ];

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'book_genre');
    }

     public function favoriteUsers()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function reviews()
    {
    return $this->hasMany(Review::class);
    }

    public function scopeKeywordSearch($query, $keyword)
    {
        if (!empty($keyword)) {
        $query->where('title', 'like', '%' . $keyword . '%')
             ->orWhere('author', 'like', '%' . $keyword . '%');
        }
        return $query;
    }

    public function scopeGenreSearch($query, $genreId)
{
    return $query->whereHas('genres', function ($query) use ($genreId) {
        $query->where('genres.id', $genreId);
    });
}
}
