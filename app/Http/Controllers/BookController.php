<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
                $query->orderBy('published_date', 'desc');
            } elseif ($sort === 'oldest') {
                $query->orderBy('published_date', 'asc');
            } elseif ($sort === 'rating') {
                $query->orderBy('rating', 'desc');
            }elseif ($sort === 'title') {
                $query->orderBy('title', 'asc');
            }
        })
        ->paginate(10)
        ->withQueryString();
        
        

        return view('books.index', compact('books','genres'));
    }

    public function create(Request $request)
    {
        return view('books.create');
    }
}
