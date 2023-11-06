<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patron extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'email'];
    public function borrowedBooks()
    {
        return $this->belongsToMany(Book::class,'patron_book')->withPivot('borrowed_at', 'due_back','returned_at')
            ->withTimestamps();
    }
    
}
