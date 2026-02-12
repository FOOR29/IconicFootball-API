<?php

namespace App\Http\Controllers;

use App\Models\Countries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CountriesController extends Controller
{
    // GET - List country
    public function index()
    {
        $countries = Countries::all();

        return response()->json([
            'data' => $countries
        ], 200);
    }

    // POST - Create country
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'logo' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $country = Countries::create($validator->validated());

        return response()->json([
            'message' => 'Country created successfully',
            'data' => $country
        ], 201);
    }

    // PUT - Update country
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'logo' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $country = Countries::find($id);

        if (!$country) {
            return response()->json([
                'message' => 'Country not found',
            ], 404);
        }

        $country->update($validator->validated());

        return response()->json([
            'message' => 'Country updated successfully',
            'data' => $country
        ], 200);
    }

    // DELETE - Delete country
    public function destroy($id)
    {
        $country = Countries::find($id);

        if (!$country) {
            return response()->json([
                'message' => 'Country not found',
            ], 404);
        }

        $country->delete();

        return response()->json([
            'message' => 'Country deleted successfully',
        ], 200);
    }
}