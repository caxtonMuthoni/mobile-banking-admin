<?php

namespace App\Http\Controllers;

use App\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reviews = Review::where('status',true)->latest()->limit(5)->get();
        return $reviews;
    }

    public function getAll(){
        $reviews = Review::all();
        return $reviews;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
             'title' => 'required',
             'review' => 'required'
        ]);

        $review = new Review;
        $review->userId = auth('api')->user()->id;
        $review->title = $request->title;
        $review->review = $request->review;
        if($review->save()){
            return response()->json([
                'status' => 'true',
                'success' => 'review was sent successifully !!!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->id;
       $review = Review::where('id',$id)->first();
       if ($review->status) {
        $review->status = false;
       } else {
        $review->status = true;
       }
       
       
       if($review->save()){
           return response()->json([
               'status' => 'true',
               'success' => 'review was updated successifully !!!'
           ]);
       }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review)
    {
        //
    }
}
