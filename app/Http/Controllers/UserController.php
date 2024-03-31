<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
     /**
     * Create User
     * @param Request $request
     * @return User 
     */
    public function createUser(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(), 
            [
                'name' => 'required',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|string|min:8',
                'mobile' => 'required|string|regex:/^[0-9]{10}$/',
                'usertype' => ['required','integer',Rule::in([0,1,2])],
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'mobile' => $request->mobile,
                'usertype' => $request->usertype,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            if ($user->flag == 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'User login not allowed.',
                ], 401);
            }

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function index(Request $req){
        $users = User::all();

        if($users->count()>0){

            return response()->json([
                'status'=> 200,
                'users' =>  $users
            ], 200);

        }else{
            return response()->json([
                'status'=> 404,
                'message' =>  'no records found'
            ], 404);
        }
       
    }

    public function update(Request $req, $id) {
        try {
            $users = User::findOrFail($id);
    
            $req->validate([
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
            ]);
    
            $users->update([
                'name' => $req->name,
                'email' => $req->email,
                'password' => $req->password,
            ]);
    
            return response()->json([
                'status' => 200,
                'message' => 'users updated successfully',
                'users' => $users
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete(Request $req) {
        try {
            $users = User::find($req->id);
            if (!$users) {
                return response()->json([
                    'status' => 404,
                    'error' => 'users not found'
                ], 404);
            }
    
            $users->delete();
    
            return response()->json([
                'status' => 200,
                'message' => 'users deleted successfully',
                'user' => $users
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            // Retrieve the user from the database
            $user = User::findOrFail($id);

            // Return JSON response with the user
            return response()->json([
                'status' => 200,
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            // Handle exceptions (e.g., user not found)
            return response()->json([
                'status' => 404,
                'error' => 'User not found'
            ], 404);
        }
    }
}