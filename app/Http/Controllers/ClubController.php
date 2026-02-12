<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Clubs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClubController extends Controller
{
    // GET - List club
    public function index()
    {
        $clubs = Clubs::all();

        return response()->json([
            'data' => $clubs
        ], 200);
    }

    // POST - Create club
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

        $club = Clubs::create($validator->validated());

        return response()->json([
            'message' => 'Club created successfully',
            'data' => $club
        ], 201);
    }

    // PUT - Update club
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

        $club = Clubs::find($id);

        if (!$club) {
            return response()->json([
                'message' => 'Club not found',
            ], 404);
        }

        $club->update($validator->validated());

        return response()->json([
            'message' => 'Club updated successfully',
            'data' => $club
        ], 200);
    }

    // DELETE - Delete club
    public function destroy($id)
    {
        $club = Clubs::find($id);

        if (!$club) {
            return response()->json([
                'message' => 'Club not found',
            ], 404);
        }

        $club->delete();

        return response()->json([
            'message' => 'Club deleted successfully',
        ], 200);
    }
}
