<?php

namespace App\Http\Controllers;
use App\Models\Genre;
use App\Models\Book;

use Illuminate\Http\Request;
use App\Http\Requests\GenreRequest;
use App\Http\Requests\GenreEditRequest;

class GenreController extends Controller
{
    public function index(Request $request)
    {
       $genres = Genre::withCount('books')->get();

       return view('genres.index', compact('genres'));
    }

    public function show(Genre $genre)
   {
        $books = $genre->books()->paginate(10);
        return view('genres.show', compact('books', 'genre'));
   }

   public function delete($id)
    {
        Genre::findOrFail($id)->delete();
        return redirect()->route('genres.index');
    }
    
    public function create()
   {
       return view('genres.create');
   }

    public function store(GenreRequest $request)
   {
        $genre = Genre::create([
        'name' => $request->name,
    ]);
        return redirect()->route('genres.index');
   }

    public function edit(Genre $genre){  

        return view('genres.edit',compact('genre'));
    }

     public function update(GenreEditRequest $request, Genre $genre)
    {
        $genre->update($request->only([
          'name',
        ]));

        return redirect()->route('genres.index');

    }

}
