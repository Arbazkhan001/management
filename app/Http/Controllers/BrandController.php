<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brands;

class BrandController extends Controller
{
    public function index(Request $req){
        try {
            // Attempt to retrieve all brands
            $brands = Brands::all();
    
            // Check if any brands were found
            if($brands->count() > 0){
                // Return JSON response with brands if found
                return response()->json([
                    'status' => 200,
                    'brands' => $brands
                ], 200);
            } else {
                // Return JSON response indicating no records found
                return response()->json([
                    'status' => 404,
                    'message' => 'No records found'
                ], 404);
            }
        } catch (\Exception $e) {
            // Return JSON response for any exceptions
            return response()->json([
                'status' => 500,
                'error' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }


    public function add(Request $req){
        try {
            // Validate request data if necessary
            $validatedData = $req->validate([
                'brandName' => 'required|string',
                'ownerName' => 'required|string',
                'numberOfCrates' => 'required|integer'
            ]);
    
            // Create a new Brands instance and assign request data
            $brands = new Brands;
            $brands->brandName = $validatedData['brandName'];
            $brands->ownerName = $validatedData['ownerName'];
            $brands->numberOfCrates = $validatedData['numberOfCrates'];
    
            // Save the Brands instance
            $brands->save();
    
            // Return JSON response with the saved Brands instance
            return response()->json([
                'status' => 200,
                'brands' => $brands
            ], 200);
        } catch (\Exception $e) {
            // Return JSON response for any exceptions
            return response()->json([
                'status' => 500,
                'error' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }


            public function delete(Request $req) {
                try {
                    $brand = Brands::find($req->id);
                    if (!$brand) {
                        return response()->json([
                            'status' => 404,
                            'error' => 'Brand not found'
                        ], 404);
                    }
            
                    $brand->delete();
            
                    return response()->json([
                        'status' => 200,
                        'message' => 'Brand deleted successfully',
                        'brand' => $brand
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
                $brand = Brands::findOrFail($id);
        
                // Validate the request data
                $req->validate([
                    'brandName' => 'required',
                    'ownerName' => 'required',
                    'numberOfCrates' => 'required'
                ]);
        
                // Update the brand with the provided data
                $brand->update([
                    'brandName' => $req->brandName,
                    'ownerName' => $req->ownerName,
                    'numberOfCrates' => $req->numberOfCrates,
                ]);
        
                // Return a success response
                return response()->json([
                    'status' => 200,
                    'message' => 'Brand updated successfully',
                    'brand' => $brand
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
            $brand = Brands::findOrFail($id);

            // Return JSON response with the user
            return response()->json([
                'status' => 200,
                'brand' => $brand
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