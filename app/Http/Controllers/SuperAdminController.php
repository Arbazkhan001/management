<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuperAdmin;
use Illuminate\Support\Facades\Log;


class SuperAdminController extends Controller
{
    public function createSuperAdmin(Request $request)
    {
        try {
            // Validate input data
            $request->validate([
                'companyName' => 'required|string',
                'userName' => 'required|string|unique:super_admins',
                'email' => 'required|email|unique:super_admins',
                'mobile' => 'required|string|unique:super_admins',
                'password' => 'required|string|min:6',
            ]);
        
            // Assign the correct value for usertype (assuming superAdmin is represented by 0)
            $request->merge(['usertype' => 0]);
            
            // Create a new super admin
            $superAdmin = SuperAdmin::create($request->all());
        
            // Log the $superAdmin object
            Log::info('Super admin created successfully', ['super_admin' => $superAdmin]);
        
            // Return success response
            return response()->json(['message' => 'Super admin created successfully', 'data' => $superAdmin], 201);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error creating super admin', ['exception' => $e]);
            
            // Return error response
            return response()->json(['message' => 'Failed to create super admin', 'error' => $e->getMessage()], 500);
        }
    }


    public function update(Request $req, $id) {
        try {
            $superAdmin = SuperAdmin::findOrFail($id);
    
            $req->validate([
                'companyName' => 'required',
                'userName' => 'required',
                'email' => 'required',
                'mobile' => 'required',
                'password' => 'required|string|min:6',
            ]);
    
            $superAdmin->update([
                'companyName' => $req->companyName,
                'userName' => $req->userName,
                'email' => $req->email,
                'mobile' => $req->mobile,
                'password' => $req->password,
            ]);
    
            return response()->json([
                'status' => 200,
                'message' => 'Super admin updated successfully',
                'dealer' => $superAdmin
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    
}