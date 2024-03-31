<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dealer;
use App\Models\Customers;



class DealerController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'brand_id' => 'required|exists:brands,id',
            'units' => 'required|integer',
            'vehicle_number' => 'required|string',
        ]);
    
        // Retrieve the customer details
        $customer = Customers::findOrFail($validatedData['customer_id']);
    
        // Create the dealer instance and assign the validated data
        $dealer = new Dealer;
        $dealer->customer_id = $validatedData['customer_id'];
        $dealer->brand_id = $validatedData['brand_id'];
        $dealer->units = $validatedData['units'];
        $dealer->vehicle_number = $validatedData['vehicle_number'];
    
        // Save the dealer instance
        $dealer->save();
    
        // Return a JSON response with the created dealer data and customer name
        return response()->json([
            'status' => 200,
            'message' => 'Dealer created successfully',
            'dealer' => [
                'customer_name' => $customer->customerName,
                'customer_id' => $dealer->customer_id,
                'brand_id' => $dealer->brand_id,
                'units' => $dealer->units,
                'vehicle_number' => $dealer->vehicle_number
            ]
        ], 200);
    }

public function index(Request $req){
    $dealer = Dealer::all();
   

    if($dealer->count()>0){

        return response()->json([
            'status'=> 200,
            'dealer' =>  $dealer
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
        $dealer = Dealer::find($req->id);
        if (!$dealer) {
            return response()->json([
                'status' => 404,
                'error' => 'dealer not found'
            ], 404);
        }

        $dealer->delete();

        return response()->json([
            'status' => 200,
            'message' => 'dealer deleted successfully',
            'dealer' => $dealer
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
        $dealer = Dealer::findOrFail($id);

        $req->validate([
            'customer_id' => 'required|exists:customers,id',
            'brand_id' => 'required|exists:brands,id',
            'units' => 'required|integer',
            'vehicle_number' => 'required|string',
        ]);

        $dealer->update([
            'customer_id' => $req->customer_id,
            'brand_id' => $req->brand_id,
            'units' => $req->units,
            'vehicle_number' => $req->vehicle_number,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'customer updated successfully',
            'dealer' => $dealer
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
        $dealer = Dealer::findOrFail($id);

        // Return JSON response with the user
        return response()->json([
            'status' => 200,
            'dealer' => $dealer
        ], 200);
    } catch (\Exception $e) {
        // Handle exceptions (e.g., user not found)
        return response()->json([
            'status' => 404,
            'error' => 'dealer not found'
        ], 404);
    }
}

        }
        

