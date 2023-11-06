<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = ['first_name', 'last_name'];
    use HasFactory;
    public function books()
    {
        return $this->belongsToMany(Book::class);
    }
}
