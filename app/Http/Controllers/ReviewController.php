<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Review;
use App\Http\Requests\ReviewRequest;

class ReviewController extends Controller
{
    public function store(ReviewRequest $request, Book $book)
    {
        Review::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'comment' => $request->comment,
            'rating' => $request->rating,
        ]);

        return back();
    }
}
