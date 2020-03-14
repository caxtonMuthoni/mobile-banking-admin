<?php

namespace App\Http\Controllers;

use App\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schools = School::all();
        return $schools;
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
             'Name' => 'required| max:70 | unique:schools',
             'Location' => 'required | max:10',
             'Paybill' => 'required | numeric | max:6 | numeric | unique:schools'

         ]);

         $school = new School;
         $school->Name =$request->Name;
         $school->Paybill = $request->Paybill;
         $school->Location = $request->Location;
         if($school->save()){
             return response()->json([
                 "status"=>"true",
                 "success"=> "School added successifully !!!"
             ]);
         }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\School  $school
     * @return \Illuminate\Http\Response
     */
    public function show(School $school)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\School  $school
     * @return \Illuminate\Http\Response
     */
    public function edit(School $school)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\School  $school
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $school = School::find($id);
        
        $this->validate($request,[
            'Name' => 'required| max:70 | unique:schools,Name,'.$school->id,
            'Location' => 'required | max:10',
            'Paybill' => 'required | numeric | max:6 | numeric | unique:schools,Paybill,'.$school->id

        ]);

        
        $school->Name =$request->Name;
        $school->Paybill = $request->Paybill;
        $school->Location = $request->Location;
        if($school->save()){
            return response()->json([
                "status"=>"true",
                "success"=> "School updated successifully !!!"
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\School  $school
     * @return \Illuminate\Http\Response
     */
    public function destroy(School $school)
    {
        //
    }
}
