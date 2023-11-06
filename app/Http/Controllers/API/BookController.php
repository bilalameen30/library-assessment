<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use Validator;
class BookController extends Controller
{
        public function index()
        {
            $books = Book::all();
            return response()->json(['data' => $books], 200);
        }
    
        public function store(Request $request)
        {
            $data=$request->all();
            $validator = Validator::make($data, [
                'title' => 'required',
                'description' => 'required',
                'isbn' => 'required|unique:books',
                'publication_date' => 'required|date',
                
            ]);
    
            if($validator->fails()){
                return response(['error' => $validator->errors(), 'Validation Error']);
            }
    
    
            $book = Book::create($data);
            return response()->json(['data' => $book], 201);
        }
    
        public function show($id)
        {
            $book = Book::find($id);
    
            if (!$book) {
                return response()->json(['message' => 'Book not found'], 404);
            }
    
            return response()->json(['data' => $book], 200);
        }
    
        public function update(Request $request, $id)
        { 
            $data=$request->all();
            $book = Book::find($id);
    
            if (!$book) {
                return response()->json(['message' => 'Book not found'], 404);
            }
            $validator = Validator::make($data, [
                'title' => 'required',
                'description' => 'required',
                'isbn' => 'required|unique:books,isbn,' . $book->id,
                'publication_date' => 'required|date',
                
            ]);
    
            if($validator->fails()){
                return response(['error' => $validator->errors(), 'Validation Error']);
            }
    
            $book->update($data);
            return response()->json(['data' => $book], 200);
        }
    
        public function destroy($id)
        {
            $book = Book::find($id);
    
            if (!$book) {
                return response()->json(['message' => 'Book not found'], 404);
            }
    
            $book->delete();
            return response(['message' => ' Book deleted successfully']);
        }
        public function search(Request $request)
        {
        
            $query = $request->input('query');
      
            if (!$query) {
                return response()->json(['message' => 'Please provide a search query.'], 400);
            }
    
            $books = Book::where('title', 'like', "%$query%")
                ->orWhereHas('authors', function ($qdata) use ($query) {
                    $qdata->where('first_name', 'like', "%$query%")
                        ->orWhere('last_name', 'like', "%$query%");
                })
                ->get();
    
            return response()->json(['data' => $books], 200);
        }
    }

