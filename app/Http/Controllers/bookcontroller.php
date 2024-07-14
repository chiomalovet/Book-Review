<?php

namespace App\Http\Controllers;

use App\Models\book;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class bookcontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title= $request->query('title');
        $filter= $request->input('filter', '');

        $book = book::when($title, function($query, $title)
        {
            return $query->title($title);
            ;
        });
        
         $book = match($filter)
            {
               'popular_last_month' => $book->popularLastMonth(),
               'popular_last_6months' => $book->popularLast6Months(),
               'highest_rated_last_month' => $book->HighestRatedLastMonth(),
               'highest_rated_last_6months' => $book->HighestRatedLast6Months(),

               default => $book->latest()->WithAverageRating()->WithReviewCount()
            };

        // $book = $book->get(); 
        $cacheKey= 'book:' . $filter . ':' . $title;
        $book= 
        // cache()->remember(
            // $cacheKey,
            //  3600, fn() =>
            // $perpage=10;
           $book=$book->get();
            // );
            // $book = book::paginate(10);
        return view('book.index', ['books'=> $book]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $cacheKey= 'book:' . $id;
        $book= cache()->remember(
            $cacheKey, 
            3600,
             fn()=>book::with([
            'review' => fn($query)=>$query->latest()
              ])->WithAverageRating()->WithReviewCount()->findOrFail($id)
        );
        return view('book.show', ['book' => $book]);
    
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
