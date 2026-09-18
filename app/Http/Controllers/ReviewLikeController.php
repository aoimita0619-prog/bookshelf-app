<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewLikeController extends Controller
{
     public function toggle(Review $review)
    {
        $user = auth()->user();

        if ($user->likedReviews->contains($review->id)) {
            $user->likedReviews()->detach($review->id);
        } else {
            $user->likedReviews()->syncWithoutDetaching($review->id);
        }

        return back();
    }
}
