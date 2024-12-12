<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class Cars extends Controller
{
    //
    public function get_all_cars()
    {
        // Retrieve all cars with pagination (10 per page)
        $all_cars = Car::paginate(10);
    
        // Check if cars exist
        if ($all_cars->isEmpty()) {
            return response()->json([
                'message' => 'No cars found',
            ], 404);
        }
    
        // Return a structured response with pagination info
        return response()->json([
            'message' => 'Cars retrieved successfully',
            'data' => $all_cars->items(),
            'pagination' => [
                'current_page' => $all_cars->currentPage(),
                'last_page' => $all_cars->lastPage(),
                'per_page' => $all_cars->perPage(),
                'total' => $all_cars->total(),
            ],
        ], 200);
    }
    

    public function add_cars(Request $request){

        // Validate the incoming request data
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:255',
            'make' => 'required|string|min:2|max:255',
            'reg' => 'required|string|unique:cars,car_reg|max:255', // Ensure unique constraint works
        ]);

        try {
            // Create a new car using validated data
            $add_car = Car::create([
                'car_name' => $validated['name'],
                'car_make' => $validated['make'],
                'car_reg'  => $validated['reg'],
            ]);

            // Return a success response with the created car details
            return response()->json([
                'message' => 'Car added successfully',
                'data' => $add_car,
            ], 201); // 201 Created status
        } catch (\Exception $e) {
            // Handle errors during the creation process
            return response()->json([
                'error' => 'Failed to add car',
                'details' => $e->getMessage(),
            ], 500);
        }
    }


    public function get_car($id){

        // Find the car by its ID
        $car = Car::find($id);
    
        // Check if the car exists
        if (!$car) {
            // Return a 404 response with a meaningful error message if the car is not found
            return response()->json(['error' => 'Car not found'], 404);
        }
    
        // Return the car data in a structured JSON response with status 200
        return response()->json(['data' => $car], 200);
    }    

    public function update_car(Request $request, $id){

        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'make' => 'required|string|max:255',
            'reg' => 'required|string|max:20|unique:cars,car_reg,' . $id,  // Ensure the car registration is unique, except for the current car being updated
        ]);
    
        // Find the car by its ID
        $update_car = Car::find($id);
    
        if (!$update_car) {
            // If the car doesn't exist, return a 404 error
            return response()->json(['error' => 'Car not found'], 404);
        }
    
        // Update the car's details
        $update_car->car_name = $validatedData['name'];
        $update_car->car_make = $validatedData['make'];
        $update_car->car_reg = $validatedData['reg'];
    
        // Try saving the updated car information
        if ($update_car->save()) {
            // Return a success response
            return response()->json(['message' => 'Car updated successfully'], 200);
        } else {
            // Return an error response if saving failed
            return response()->json(['error' => 'Failed to update car'], 500);
        }
    }
    
}
