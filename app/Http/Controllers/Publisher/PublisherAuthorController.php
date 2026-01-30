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
            $validated = $request->validate([
    'name'    => 'nullable|string|max:255',
    'name_en' => 'nullable|string|max:255',
    'name_ru' => 'nullable|string|max:255',
]);

if (!($validated['name'] || $validated['name_en'] || $validated['name_ru'])) {
    return response()->json([
        'success' => false,
        'errors' => ['name' => ['At least one name is required']]
    ], 422);
}

$author = Author::create($validated);

return response()->json([
    'success' => true,
    'author'  => $author
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
