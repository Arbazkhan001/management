<?php

namespace App\Http\Controllers;


use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
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
            $validateUser = Validator::make(
                $request->all(),
                [
                    'company_name' => 'required|string|max:255|unique:admins',
                    'user_name' => 'required|string|max:255',
                    'super_admin_id' => 'required|exists:super_admins,id',
                    'email' => 'required|string|email|max:255|unique:admins,email',
                    'password' => 'required|string|min:8',
                    'mobile' => 'required|string|max:20', // Adjust the max length according to your requirements
                    'usertype' => 'required|in:1,2'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = Admin::create([
                'company_name' => $request->company_name,
                'super_admin_id' => $request->super_admin_id,
                'user_name' => $request->user_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => Hash::make($request->password),
                'usertype' => $request->usertype,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
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
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if (!Auth::guard('admin')->attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = Admin::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'usertype' => $user->usertype,
                'super_admin_id' => $user->super_admin_id,
                'Company_name' => $user->company_name,
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
        $admin = Admin::all();

        if($admin->count()>0){

            return response()->json([
                'status'=> 200,
                'brands' =>  $admin
            ], 200);

        }else{
            return response()->json([
                'status'=> 404,
                'message' =>  'no records found'
            ], 404);
        }
       
    }

    public function delete(Request $req) {
        try {
            $admin = Admin::find($req->id);
            if (!$admin) {
                return response()->json([
                    'status' => 404,
                    'error' => 'Brand not found'
                ], 404);
            }
    
            $admin->delete();
    
            return response()->json([
                'status' => 200,
                'message' => 'admin deleted successfully',
                'admin' => $admin
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $req, $id) {
        try {
            // Find the brand by ID
            $admin = Admin::findOrFail($id);
    
            // Validate the request data
            $req->validate([
                'company_name' => 'required',
                'super_admin_id' => 'required',
                'user_name' => 'required',
                'email' => 'required',
                'mobile' => 'required',
                'password' => 'required',
                'usertype' => 'required'
            ]);
    
            // Update the brand with the provided data
            $admin->update([
                'company_name' => $req->company_name,
                'super_admin_id' => $req->super_admin_id,
                'user_name' => $req->user_name,
                'email' => $req->email,
                'mobile' => $req->mobile,
                'password' => $req->password,
                'usertype' => $req->usertype,
            ]);
    
            // Return a success response
            return response()->json([
                'status' => 200,
                'message' => 'Admin/Company updated successfully',
                'brand' => $admin
            ], 200);
        } catch (\Exception $e) {
            // Return an error response if any exception occurs
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
            $admin = Admin::findOrFail($id);

            // Return JSON response with the user
            return response()->json([
                'status' => 200,
                'admin' => $admin
            ], 200);
        } catch (\Exception $e) {
            // Handle exceptions (e.g., user not found)
            return response()->json([
                'status' => 404,
                'error' => 'brand not found'
            ], 404);
        }
    }

}