<?php
namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $restaurant = Restaurant::where('owner_id', $user->id)->first();

        if (!$restaurant) {
            return response()->json(['success' => false, 'message' => 'Restaurant not found'], 404);
        }

        $drivers = Driver::where('restaurant_id', $restaurant->id)->get();
        return response()->json(['success' => true, 'data' => $drivers]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $restaurantId = Restaurant::where('owner_id', $user->id)->value('id');

        if (!$restaurantId) {
            return response()->json(['success' => false, 'message' => 'Restaurant not found'], 403);
        }

        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'vehicle_number' => 'unique:drivers,vehicle_number,NULL,id,restaurant_id,' . $restaurantId . '|string|max:20',
        ]);

        if ($validated->fails()) {
            return response()->json(['success' => false, 'errors' => $validated->errors()], 422);
        }

        $driver = Driver::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'vehicle_number' => $request->vehicle_number,
            'restaurant_id' => $restaurantId,
        ]);

        return response()->json(['success' => true, 'data' => $driver]);
    }

    public function update(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);

        $driver->update($request->only(['name', 'phone', 'vehicle_number']));

        return response()->json(['success' => true, 'message' => 'Driver updated', 'data' => $driver]);
    }

    public function destroy($id)
    {
        $driver = Driver::findOrFail($id);
        $driver->delete();

        return response()->json(['success' => true, 'message' => 'Driver deleted']);
    }
}
