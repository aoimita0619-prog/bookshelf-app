<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\ReadingPlanStatus;
use App\Http\Requests\ReadingPlanRequest;
use App\Http\Requests\ReadingPlanEditRequest;
use App\Models\ReadingPlan;
use App\Models\Book;



class ReadingPlanController extends Controller
{
    public function index(Request $request){

        $currentStatus = $request->input('status');

        $query = ReadingPlan::with('book')
        ->where('user_id', auth()->id());

       if ($currentStatus !== null && $currentStatus !== '') {
            $query->where('status', $currentStatus);
        }

       $readingPlans = $query
        ->orderBy('target_date')
        ->get();

    return view('reading-plans.index', compact('currentStatus', 'readingPlans'));
    
    }

    public function create()
   {
       $books = Book::all();
       return view('reading-plans.create', compact('books'));
   }

    public function store(ReadingPlanRequest $request)
    {
        $readingPlan = ReadingPlan::create([

        'user_id' => auth()->id(),
        'book_id' => $request->book_id,
        'target_date' => $request->target_date,
        'status' => 'want_to_read',

    ]);
        return redirect()->route('reading-plans.index');
    }

    public function edit(ReadingPlan $plan){  
    
        abort_unless($plan->user_id == auth()->id(), 403);
        return view('reading-plans.edit',['readingPlan' => $plan,]);
    }

     public function update(ReadingPlanEditRequest $request, ReadingPlan $plan)
    {
       abort_unless($plan->user_id == auth()->id(), 403);

    $plan->update([
        'target_date' => $request->target_date,
    ]);

        return redirect()->route('reading-plans.index');

    }

    public function delete($id)
    {
        ReadingPlan::findOrFail($id)->delete();
        return redirect()->route('reading-plans.index');
    }

    public function complete(ReadingPlan $plan){

       $plan->update([
          'status' => ReadingPlanStatus::Completed,
          'completed_at' => now(),
        ]);

    return redirect()->route('reading-plans.index');

    }
}
