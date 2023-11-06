<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'isbn','publication_date'];
    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }
    public function isAvailable()
    {
       $book= Book::whereDoesntHave('patrons', function ($query) {
            $query->whereNotNull('returned_at');
        })->where('id', $this->id)->get();
        return $book;
    }
    public function patrons()
    {
        return $this->belongsToMany(Patron::class,'patron_book')
            ->withPivot('borrowed_at', 'due_back', 'returned_at')
            ->withTimestamps();
    }
}
