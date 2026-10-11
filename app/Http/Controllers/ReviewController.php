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

        return redirect()->route('books.show', $book->id);
    }

    public function delete(Review $review)
    {
        $this->authorize('delete', $review);

        // 削除前に書籍IDを取得する
        $bookId = $review->book_id;

        $review->delete();

        return redirect()->route('books.show', $bookId);
    }

    public function edit(Review $review)
    {
        $this->authorize('update', $review);

        return view('reviews.edit', compact('review'));
    }

    public function update(ReviewRequest $request, Review $review)
    {
        $this->authorize('update', $review);

        $review->update($request->only([
            'rating',
            'comment',
        ]));

        return redirect()->route('books.show', $review->book_id);
    }
}
