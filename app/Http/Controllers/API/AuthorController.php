<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Author;
use App\Models\Book;
use Validator;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::all();
        return response()->json(['data' => $authors], 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $author = Author::create($data);
        return response()->json(['data' => $author], 201);
    }

    public function show($id)
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json(['message' => 'Author not found'], 404);
        }

        return response()->json(['data' => $author], 200);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $author = Author::find($id);

        if (!$author) {
            return response()->json(['message' => 'Author not found'], 404);
        }

        $validator = Validator::make($data, [
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $author->update($data);
        return response()->json(['data' => $author], 200);
    }

    public function destroy($id)
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json(['message' => 'Author not found'], 404);
        }

        $author->delete();
        return response(['message' => 'Author deleted successfully']);
    }
    public function assignAuthor(Request $request, Book $book, Author $author)
    {
      
        $bookIds = $request->input('book_ids', []);

        if (empty($bookIds)) {
            return response()->json(['message' => 'No books provided for assignment.'], 400);
        }
        $existingBooks = Book::whereIn('id', $bookIds)->get();
        
        if ($existingBooks->count() !== count($bookIds)) {
            return response()->json(['message' => 'Some book IDs provided do not exist.'], 400);
        }
        $author->books()->syncWithoutDetaching($bookIds);

        return response()->json(['message' => 'Books assigned to the author.'], 200);
    }
    public function showBooks(Author $author)
    {
        $books = $author->books;

        return response()->json(['data' => $books], 200);
    }
}

