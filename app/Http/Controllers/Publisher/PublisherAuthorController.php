<?php
namespace App\Http\Controllers\Publisher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Author;

class PublisherAuthorController extends Controller
{
     // Display the form to create a new author
     public function create()
     {
         return view('publisher.authors.create'); // Points to the view we'll create next
     }

     public function store(Request $request)
     {
         try {
             // Validate the input field
             $validatedData = $request->validate([
                 'new_author_name' => 'required|string|max:255|unique:authors,name',
             ], [
                 'new_author_name.unique' => 'ეს ავტორი უკვე დარეგისტრირებულია.', // Custom error message
             ]);
     
             // Create the new author
             $author = Author::create(['name' => $validatedData['new_author_name']]);
     
             // Return JSON response for AJAX
             return response()->json([
                 'success' => true,
                 'author' => $author,
             ]);
         } catch (\Illuminate\Validation\ValidationException $e) {
             // Return validation errors
             return response()->json([
                 'success' => false,
                 'errors' => $e->errors(),
             ], 422);
         } catch (\Exception $e) {
             // Handle general errors
             return response()->json([
                 'success' => false,
                 'message' => $e->getMessage(),
             ], 500);
         }
     }
     
     
}
