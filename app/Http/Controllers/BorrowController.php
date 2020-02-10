<?php

namespace App\Http\Controllers;

use App\Borrow;
use Illuminate\Http\Request;
use Validator;
use Image;
use Auth;

class BorrowController extends Controller
{
    
    public function myBorrows(){
        $userLoans = Borrow::where('userId',auth('api')->user()->id)->latest()->paginate(10);
        return $userLoans;
    }
    public function index()
    {
        $borrows = Borrow::all();
        return $borrows;
    }

    public function getUserLoans(){
        $userId = Auth::user()->id;
        $borrows = Borrow::where('userId',$userId)->take(10)->get();
        return $borrows;
    }

    public function getUserActiveLoan(){
        $userId = Auth::user()->id;
        $borrow = Borrow::where([['userId','=',$userId],['paymentstatus','=',0]])->first();
        return $borrow;
    }

    
    public function Borrow(Request $request)
    {
        /* Validation */
        $validator = Validator::make($request->all(),[
            "amountBorrowed" => "required | numeric",
            "image" => "max:5000 | mimes:jpg,jpeg,png",
            "description" => "min:150 |max:500 | required",
            "title"=> "required | min:20 | max:60 |string",
        ]);
        if ($validator->fails()){
            return $validator->errors();
        }
        if(Borrow::where([['userId','=',Auth::user()->id],['paymentstatus','=',0]])->count()>0){
            return response()->json([
                "status" => "false",
                "error" => "You have a pending loan process"
            ]);
        }
        $fileName = "default.png";
       
        if($request->hasFile('image')) {
            $userName = Auth::user()->name;
            $image = $request->file('image');
            $imageName = $image->getClientOriginalName();
            $fileName = $userName.time() . "_project".$imageName;
    
            $directory = public_path('/images/project/');
            $imageUrl = $directory.$fileName;
            Image::make($image)->resize(200, 200)->save($imageUrl);
        }

        $borrow = new Borrow;
        $borrow->userId = Auth::user()->id;
        $borrow->amountBorrowed = $request->amountBorrowed;
        $borrow->balance=$request->amountBorrowed;
        $borrow->title = $request->title;
        $borrow->description = $request->description;
        $borrow->project_image = $fileName;
        if($borrow->save()){
            return response()->json([
                'status'=>"true",
                "success"=>"Request posted successifully !!!"
            ]);
        }
    }

    public function destroy(Borrow $borrow)
    {
        //
    }
}
