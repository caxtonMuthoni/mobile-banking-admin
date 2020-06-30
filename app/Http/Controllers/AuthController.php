<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\Profile;
class AuthController extends Controller
{
  

    public function getUsers(){
        // return User::all();
        $users = User::latest()->paginate(10);
        return $users;
    }
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request)
    {
        $request->validate([
            'FirstName' => 'required|string',
            'MiddleName' => 'required|string',
            'LastName' => 'required|string',
            'NationalID' => 'required|numeric|unique:users',
            'PhoneNumber' => 'required | max:10 | min:10 | regex:/(07)[0-9]{8}/ | unique:users',
            'City' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string'
        ]);
        $user = new User([
            'FirstName' => $request->FirstName,
            'MiddleName' => $request->MiddleName,
            'LastName' => $request->LastName,
            'NationalID' => $request->NationalID,
            'PhoneNumber' => $request->PhoneNumber,
            'City' => $request->City,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
         $status = $user->save();
         if($status){
             $profile = new Profile();
             $profile->UserId = $user->id;
             $profile->save();
         }
        return response()->json([
             'status'=>"true",
            'message' => 'Successfully created user!'
        ], 201);
    }
  
    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'PhoneNumber' => 'required|string',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credentials = request(['PhoneNumber', 'password']);
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Access Denied'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    public function updateUser(Request $request,$id){

        $userToEdit = User::find($id);
        /* Validation */

        $request->validate([
            'FirstName' => 'required|string',
            'MiddleName' => 'required|string',
            'LastName' => 'required|string',
            'NationalID' => 'required|numeric',
            'PhoneNumber' => 'required | max:10 | min:10 | regex:/(07)[0-9]{8}/',
            'City' => 'required|string',
            'email' => 'required|string|email|unique:users,email,'.$userToEdit->id,
            'password' => 'sometimes|string'
        ]);
        
            $userToEdit->FirstName = $request->FirstName;
            $userToEdit->MiddleName = $request->MiddleName;
            $userToEdit->LastName = $request->LastName;
            $userToEdit->NationalID = $request->NationalID;
            $userToEdit->PhoneNumber = $request->PhoneNumber;
            $userToEdit->City = $request->City;
            $userToEdit->email = $request->email;
            $userToEdit->password = bcrypt($request->password);
            if($userToEdit->save()){
                return response()->json([
                    'status'=>true,
                    "success"=>"User updated successifully"
                ]);
            }
    }
  
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
  
    

    public function destroy($id){
        $user = User::find($id);
        $user->delete();
        if($user->delete()){
            return response()->json([
                'status'=>true,
                'success'=>"user deleted"
            ]);
        }
    }
    public function loadUser(){
        $user = auth('api')->user();
        return $user;
    }
   public function loadUserWithId($id){
       $user = User::find($id);
       return $user;
   }

   
}