<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dealer;


class DealerController extends Controller
{
    public function store(Request $request)
    {
        try{
$validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'brand_id' => 'required|exists:brands,id',
            'units' => 'required|integer',
            'vehicle_number' => 'required|string',
        ]);

        $dealer = Dealer::create($validatedData);

        return response()->json([
        'message' => 'Dealer created successfully',
         'data' => $dealer], 201);

     } catch (\Exception $e) {
        return response()->json([
            'status' => 500,
            'error' => 'Internal server error: ' . $e->getMessage(), $e,
        ], 500);
    }
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
        

