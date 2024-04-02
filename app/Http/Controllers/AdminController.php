<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
}