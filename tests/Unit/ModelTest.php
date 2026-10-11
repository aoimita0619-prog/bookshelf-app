<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Book;
use App\Models\Review;
use App\Models\Genre;
use App\Models\User;
use App\Models\Favorite;

class ModelTest extends TestCase
{
     public function test_model_can_be_instantiated(): void
    {
        $book = new Book();

        $this->assertInstanceOf(Book::class, $book);
    }

    public function test_model_uses_books_table(): void
    {
        $book = new Book();

        $this->assertSame('books', $book->getTable());
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function favoriteUsers(){
        return $this->belongsToMany(User::class, 'favorites');
    }
}
