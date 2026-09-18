<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BookRequest;
use App\Models\User;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Review;

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
        ->paginate(10)
        ->withQueryString();

    return view('books.index', compact('books', 'genres'));
    }

    public function create()
   {
         $genres = Genre::all();
       return view('books.create', compact('genres'));
   }
    public function store(BookRequest $request)
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

        return redirect()->route('books.index');
   }

    public function show($id)
   {
        $book = Book::findOrFail($id);

        return view('books.show', compact('book'));
   }

    public function edit(Book $book){  
       $this->authorize('update', $book);
        $genres = Genre::all();
        
        return view('books.edit',compact('book','genres'));
    }

     public function update(BookRequest $request, Book $book)
    {
        $this->authorize('update', $book);

        $book->update($request->only([
          'title',
          'author',
          'isbn',
          'description',
          'image_url',
          'publish_date',
        ]));

         $book->genres()->sync($request->input('genres', []));
        return redirect()->route('books.index');

    }

    
    public function delete($id)
    {
        Book::findOrFail($id)->delete();
        return redirect()->route('books.index');
    }


}
