<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customers;


class CustomerController extends Controller
{
    public function index(Request $req){
        $customer = Customers::all();

        if($customer->count()>0){

            return response()->json([
                'status'=> 200,
                'brands' =>  $customer
            ], 200);

        }else{
            return response()->json([
                'status'=> 404,
                'message' =>  'no records found'
            ], 404);
        }
       
    }


    public function add(Request $req){
        $customers = new Customers;
        $customers->customerName = $req->customerName;
        $customers->phone = $req->phone;
        $customers->save();
        return response()->json([
            'status'=> 200,
            'customers' =>  $customers
        ], 200);        }

        public function delete(Request $req) {
            try {
                $customers = Customers::find($req->id);
                if (!$customers) {
                    return response()->json([
                        'status' => 404,
                        'error' => 'customer not found'
                    ], 404);
                }
        
                $customers->delete();
        
                return response()->json([
                    'status' => 200,
                    'message' => 'customer deleted successfully',
                    'brand' => $customers
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
                $customers = Customers::findOrFail($id);
        
                $req->validate([
                    'customerName' => 'required',
                    'phone' => 'required',
                ]);
        
                $customers->update([
                    'customerName' => $req->customerName,
                    'phone' => $req->phone,
                ]);
        
                return response()->json([
                    'status' => 200,
                    'message' => 'customer updated successfully',
                    'customer' => $customers
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
                $customers = Customers::findOrFail($id);
    
                // Return JSON response with the user
                return response()->json([
                    'status' => 200,
                    'customers' => $customers
                ], 200);
            } catch (\Exception $e) {
                // Handle exceptions (e.g., user not found)
                return response()->json([
                    'status' => 404,
                    'error' => 'customer not found'
                ], 404);
            }
        }
}




