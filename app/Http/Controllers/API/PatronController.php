<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patron;
use App\Models\Book;
use Validator;

class PatronController extends Controller
{
    public function index()
    {
        $patrons = Patron::all();
        return response()->json(['data' => $patrons], 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $patron = Patron::create($data);
        return response()->json(['data' => $patron], 201);
    }

    public function show($id)
    {
        $patron = Patron::find($id);

        if (!$patron) {
            return response()->json(['message' => 'Patron not found'], 404);
        }

        return response()->json(['data' => $patron], 200);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $patron = Patron::find($id);

        if (!$patron) {
            return response()->json(['message' => 'Patron not found'], 404);
        }

        $validator = Validator::make($data, [
            'name' => 'required',
           
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $patron->update($data);
        return response()->json(['data' => $patron], 200);
    }

    public function destroy($id)
    {
        $patron = Patron::find($id);

        if (!$patron) {
            return response()->json(['message' => 'Patron not found'], 404);
        }

        $patron->delete();
        return response(['message' => 'Patron deleted successfully']);
    }
    public function borrowBook(Request $request, Patron $patron, Book $book)
    {
       
        if (!$book->isAvailable()) {
            return response()->json(['message' => 'Book is already borrowed by another patron.'], 400);
        }
        $patron->borrowedBooks()->attach($book->id, ['borrowed_at' => now(), 'due_back' => now()->addDays(14)]);

        return response()->json(['message' => 'Book borrowed successfully.'], 200);
    }

    public function returnBook(Request $request, Patron $patron, Book $book)
{
    
    if (!$patron->borrowedBooks->contains($book->id)) {
        return response()->json(['message' => 'Book was not borrowed by the patron.'], 400);
    }
    $due_back = $patron->borrowedBooks->where('id', $book->id)->first()->pivot->due_back;

    $returned_at = now();
    if ($returned_at > $due_back) {
      
        $patron->borrowedBooks()->updateExistingPivot($book->id, ['returned_at' => $returned_at]);
        return response()->json(['message' => 'Book is returned late.', 'late_fee' => $this->calculateLateFee($due_back, $returned_at)], 400);
    }
 else{
    $patron->borrowedBooks()->updateExistingPivot($book->id, ['returned_at' => $returned_at]); 
 }

    return response()->json(['message' => 'Book returned successfully.'], 200);
}

private function calculateLateFee($due_back, $returned_at)
{

    $daysOverdue = $returned_at->diffInDays($due_back);
    $lateFee = $daysOverdue * 5;
    return $lateFee;
}

}
