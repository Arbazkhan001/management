<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Brands;
use App\Models\Customers;
use App\Models\CustomerOrder;

class CustomerOrderController extends Controller
{

    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'customer_ids' => 'required|array',
                'customer_ids.*' => 'required|exists:customers,id',
                'customerNames' => 'required|array',
                'customerNames.*' => 'required|string',
                'brand_ids' => 'required|array',
                'brand_ids.*' => 'required|exists:brands,id',
                'brandNames' => 'required|array',
                'brandNames.*' => 'required|string',
                'units' => 'required|integer',
                'date' => 'required|string',
            ]);

            $customerOrders = [];

            // Loop through each set of data and create customer orders
            foreach ($validatedData['customer_ids'] as $index => $customerId) {
                // Create a new customer order instance
                $customerOrder = new CustomerOrder;
                $customerOrder->customer_id = $customerId;
                $customerOrder->customerName = $validatedData['customerNames'][$index]; // Use the corresponding customer name
                $customerOrder->brand_id = $validatedData['brand_ids'][$index];
                $customerOrder->brandName = $validatedData['brandNames'][$index]; // Use the corresponding brand name
                $customerOrder->units = $validatedData['units'];
                $customerOrder->date = $validatedData['date'];

                // Save the customer order instance
                $customerOrder->save();

                // Add the saved customer order to the response array
                $customerOrders[] = $customerOrder;
            }

            // Return a JSON response with the created customer orders
            return response()->json([
                'status' => 200,
                'message' => 'Customer orders created successfully',
                'customer_orders' => $customerOrders
            ], 200);
        } catch (ValidationException $e) {
            // Return validation error response
            return response()->json([
                'status' => 422,
                'error' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Return generic error response
            return response()->json([
                'status' => 500,
                'error' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }
}
