<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

use App\Book;
use Auth;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Book::with('author')->get());
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:50', 'min:2', 'unique:books'],
            'author_id' => ['required','min:1'],
            'price' => ['required','min:1','regex:/^\d+(\.\d{1,2})?$/'],
            'stock' => ['required','min:1','regex:/^\d+?$/'],
            'description' => ['required', 'string', 'max:255']
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validator($request->all())->validate();
        Book::create($request->all());
        return response()->json([
            'success'     => "Book register successfully !"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Make a booking.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function booking(Request $request)
    {
        Validator::make($request->all(), [
            'book_id' => ['required','min:1'],
        ]);
        $book = Book::with("author")->get()->find($request->book_id);
        if($book->stock>0){
            $user = Auth::user();
            $user->books()->attach($request->book_id);
            $book->stock--;
            $book->save();
            return  response()->json([
                'success'     => "Booking successfully (".$book->name.")",
                'book' => $book
            ]);
        }
        return response()->json([
            'danger'     => "No stock (".$book->name.")"
        ]);
    }
    /**
     * Cancel a booking.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cancelBooking(Request $request)
    {
        Validator::make($request->all(), [
            'id' => ['required','min:1'],
            'pivot' => ['required']     
        ]);

        $book = Book::with("author")->get()->find($request->id);
        $book->stock++;
        $book->save();
        $user = Auth::user();
        $user->books()->wherePivot('id',$request->pivot["id"])->detach();
        
        return  response()->json([
            'success'     => "Booking cancel successfully (".$book->name.")",
            'book' => $book
        ]);
    }
}
