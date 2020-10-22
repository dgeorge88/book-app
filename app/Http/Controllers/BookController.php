<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Book;
use Illuminate\Support\Facades\Validator;



class BookController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get books
        $book = Book::select('id','isbn','title','author','category','price')->get();
        return response()->json($book, 200);

    }

    public function categories()
    {

        $book = Book::select('category')->distinct()->get();
        return response()->json($book, 200);
        
    }

    public function searchAuthor($author) {

        if (Book::where('author', 'like', "%{$author}%")->exists()) {
            $search = Book::where('author', 'like', "%{$author}%")
            ->select('id','isbn','title','author','category','price')
            ->get();
            return response()->json($search, 200);
        }

        return response()->json('Not found.', 404);

    }

    public function searchCategory($category) {
        if (Book::where('category', 'like', "%{$category}%")->exists()) {
            $search = Book::where('category', 'like', "%{$category}%")
            ->select('id','isbn','title','author','category','price')
            ->get();
            return response()->json($search, 200);
        }

        return response()->json('Not found.', 404);

    }

    public function searchMultiple($author, $category) {

        if (Book::where('author', 'like', "%{$author}%")->where('category', 'like', "%{$category}%")->exists()) {
            $search = Book::where('author', 'like', "%{$author}%")
            ->where('category', 'like', "%{$category}%")
            ->select('id','isbn','title','author','category','price')
            ->get();
            return response()->json($search, 200);
        }

        return response()->json('Not found.', 404);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //validate data
        $validator = Validator::make($request->all(), [
            "title" => 'required',
            "author" => 'required',
            "isbn" => 'required',
            "category" => 'required',
            "price" => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //ISBN does not contain letters
        if(preg_match("/[a-z]/i", $request->isbn)){
            return response()->json('Invalid ISBN.', 400);
        }

        //ISBN is either 10 or 13 digits
        $reduce = preg_replace('/[^0-9]/','', $request->isbn);
        $length = strlen($reduce);

        if($length == 10 Or $length == 13){
            //create book
            $book = Book::create($request->all());

            return response()->json([
                "id"=>$book->id,
                "isbn"=>$book->isbn,
                "title"=>$book->title,
                "author"=>$book->author,
                "category"=>$book->category,
                "price"=>$book->price
            ], 201);
        }
        
        return response()->json('Invalid ISBN.', 400);
    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){

        if (Book::where('id', $id)->exists()) {
            // exists
            $book = Book::select('id','isbn','title','author','category','price')->where('id', $id)->get();
            return response()->json($book, 200);
        }

        return response()->json('Not found.', 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        //update a book
        $book->update($request->all());
        return response()->json($book, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        //delete book
        $book->delete();
        return response()->json(Null, 204);
    }
}


 /*
 //multi-search function
    public function search($data) {

        $search = Book::where('isbn', 'like', "%{$data}%")
        ->orWhere('title', 'like', "%{$data}%")
        ->orWhere('author', 'like', "%{$data}%")
        ->orWhere('category', 'like', "%{$data}%")
        ->select('id','isbn','title','author','category','price')
        ->get();
        return response()->json($search, 200);
 
    }
*/
