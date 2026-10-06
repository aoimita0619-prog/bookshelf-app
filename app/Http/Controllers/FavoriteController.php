<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function toggle(Book $book) { 
        $user = auth()->user(); 
        if ($user->favoriteBooks->contains($book->id)) { 
            
        $user->favoriteBooks()->detach($book->id); 
            
        } else { $user->favoriteBooks()->syncWithoutDetaching($book->id); 
            
        } return back(); 
    }

    public function index(){

        $books = auth()->user()->favoriteBooks()->paginate(10);

        return view('favorites.index', compact('books'));
    }
}
