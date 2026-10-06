<?php

namespace App\Models;

use App\Enums\ReadingPlanStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadingPlan extends Model
{
    use HasFactory;

    protected $fillable = [

        'target_date',
        'user_id',
        'book_id',
        'status',
        'completed_at',
    ];

    protected $casts = [

        'target_date' => 'date',
        'completed_at' => 'date',
        'status' => \App\Enums\ReadingPlanStatus::class,
    ];

    public function user()
   {
       return $this->belongsTo(User::class);
   }
   public function book()
   {
       return $this->belongsTo(Book::class);
   }
}
