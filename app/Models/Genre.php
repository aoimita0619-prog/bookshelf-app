<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

     use HasFactory;
    
    protected $fillable = [
        'content',
    ];

    public function books()
    {
        return $this->belongsToMany(Item::class, 'book_genre');
    }
}
