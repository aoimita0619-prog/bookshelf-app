<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReportController extends Controller
{
    public function index(Request $request){

        $userId = auth()->id();

        $ratingDistribution = Review::where('user_id', $userId)
        ->selectRaw('rating, COUNT(*) as count')
        ->groupBy('rating')
        ->pluck('count', 'rating');

        $ratingDistribution = collect([
        0 => $ratingDistribution[1] ?? 0,
        1 => $ratingDistribution[2] ?? 0,
        2 => $ratingDistribution[3] ?? 0,
        3 => $ratingDistribution[4] ?? 0,
        4 => $ratingDistribution[5] ?? 0,
       ]); 

$userReviews = Review::where('user_id', $userId)
    ->with('book.genres')
    ->get();

$genreRatings = [];

foreach ($userReviews as $review) {
    foreach ($review->book->genres as $genre) {

        if (!isset($genreRatings[$genre->id])) {
            $genreRatings[$genre->id] = [
                'id' => $genre->id,
                'name' => $genre->name,
                'count' => 0,
                'total_rating' => 0,
            ];
        }

        $genreRatings[$genre->id]['count']++;
        $genreRatings[$genre->id]['total_rating'] += $review->rating;
    }
}

$genreRatings = collect($genreRatings)
    ->map(function ($genre) {
        $genre['average_rating'] =
            $genre['total_rating'] / $genre['count'];

        unset($genre['total_rating']);

        return $genre;
    })
    ->sortByDesc('average_rating')
    ->take(5)
    ->values();


$stats = [
    'summary' => [
        'total_reviews' => Review::where('user_id', $userId)->count(),
        'books_read' => Review::where('user_id', $userId)
            ->distinct('book_id')
            ->count('book_id'),
        'average_rating' => Review::where('user_id', $userId)
            ->avg('rating') ?? 0,
    ],

    'rating_distribution' => $ratingDistribution,

    'top_rated_books' => Review::where('user_id', $userId)
        ->with('book')
        ->where('rating', '>=', 4)
        ->orderByDesc('rating')
        ->get()
        ->unique('book_id')
        ->take(5)
        ->map(function ($review) {
            return [
                'id' => $review->book->id,
                'title' => $review->book->title,
                'author' => $review->book->author,
                'rating' => $review->rating,
            ];
        })
        ->values(),

    'genre_ratings' => $genreRatings,
    ];

    return view('reports.index', compact('stats'));
    }
}
