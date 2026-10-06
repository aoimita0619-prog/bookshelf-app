<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\APIBookRequest;
use App\Http\Requests\APIEditRequest;
use App\Models\Book;
use App\Models\Genre;
use App\Http\Resources\ListResource;
use App\Http\Resources\BookResource;
use App\Http\Resources\ReviewResource;

class BookController extends Controller
{
     public function index(Request $request)
    {
        $genres = Genre::all();
        $books = Book::query()
        ->when($request->keyword, function ($query, $keyword) {
            $query->keywordSearch($keyword);
        })
        ->when($request->genre, function ($query, $genreId) {
            $query->genreSearch($genreId);
        })
        ->when($request->sort, function ($query, $sort) {
            if ($sort === 'newest') {
                $query->orderBy('created_at', 'desc');
            } elseif ($sort === 'oldest') {
                $query->orderBy('created_at', 'asc');
            } elseif ($sort === 'rating') {
                $query->withAvg('reviews', 'rating')
                      ->orderBy('reviews_avg_rating', 'desc');
            } elseif ($sort === 'title') {
                $query->orderBy('title', 'asc');
            }
        })
        ->with(['genres'])
        ->withAvg('reviews', 'rating')
        ->withCount('reviews')
        ->paginate(10)
        ->withQueryString();

        return ListResource::collection($books);
    }

     public function show(Book $book)
    {
        $book->load(['genres', 'reviews.user']);

        return new BookResource($book);
    }

    public function store(APIBookRequest $request)
   {
      $book = Book::create([
        'title' => $request->title,
        'author' => $request->author,
        'isbn' => $request->isbn,
        'published_date' => $request->published_date,
        'description' => $request->description,
        'user_id' => auth()->id(),
        'image_url' => $request->image_url,
    ]);

        $book->genres()->attach($request->genres);

       return response()->json([
        'message' => '書籍を登録しました',
        'book' => $book->load('genres'),
       ], 201);
   }

        public function update(APIEditRequest $request, Book $book)
    {
        if ($book->user_id !== auth()->id()) {
        return response()->json([
            'message' => 'この書籍を更新する権限がありません',
        ], 403);
    }

    $book->update([
        'title' => $request->title,
        'author' => $request->author,
        'isbn' => $request->isbn,
        'published_date' => $request->published_date,
        'description' => $request->description,
        'image_url' => $request->image_url,
    ]);

    $book->genres()->sync($request->genres);

    $book->load('genres');

    return response()->json([
        'message' => '書籍を更新しました',
        'book' => new BookResource($book),
    ], 200);

    }

    public function destroy(Book $book)
    {
       if ($book->user_id !== auth()->id()) {
          return response()->json([
             'message' => 'この書籍を削除する権限がありません',
          ], 403);
        }

        $book->delete();

        return response()->json([
           'message' => '書籍を削除しました',
        ], 200);
    }
}
