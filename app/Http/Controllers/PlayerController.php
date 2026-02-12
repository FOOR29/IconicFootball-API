<?php

namespace App\Http\Controllers;

use App\Models\Players;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class PlayerController extends Controller
{
    // GET
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 20);

        $include = explode(',', $request->input('include', ''));

        $query = Players::query();

        if (in_array('club', $include)) {
            $query->with('club');
        }

        if (in_array('country', $include)) {
            $query->with('country');
        }

        $cacheKey = 'players_page_' . $request->input('page', 1)
            . '_per_' . $perPage
            . '_include_' . implode('-', $include);

        $players = Cache::remember($cacheKey, 60, function () use ($query, $perPage) {
            return $query->paginate($perPage);
        });

        if ($players->isEmpty()) {
            return response()->json([
                'message' => 'Players not found',
                'status' => 200
            ], 200);
        }

        return response()->json([
            'data' => $players->items(),
            'meta' => [
                'current_page' => $players->currentPage(),
                'per_page' => $players->perPage(),
                'total' => $players->total(),
                'last_page' => $players->lastPage(),
            ],
        ], 200);
    }

    // GET FOR ID
    public function show(Request $request, $id)
    {
        $include = explode(',', $request->get('include', ''));

        $query = Players::query();

        if (in_array('club', $include)) {
            $query->with('club');
        }

        if (in_array('country', $include)) {
            $query->with('country');
        }

        $cacheKey = 'player_' . $id . '_include_' . implode('-', $include);

        $player = Cache::remember($cacheKey, 60, function () use ($query, $id) {
            return $query->find($id);
        });

        if (!$player) {
            return response()->json([
                'message' => 'Player not found',
                'status' => 404
            ], 404);
        }

        return response()->json([
            'player' => $player,
            'status' => 200
        ], 200);
    }

    // POST
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'known_as' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'img' => 'required|url',
            'prime_season' => 'required|string|max:255',
            'prime_position' => 'required|string|max:255',
            'preferred_foot' => 'required|string|in:left,right,both',
            'spd' => 'required|integer|min:0|max:100',
            'sho' => 'required|integer|min:0|max:100',
            'pas' => 'required|integer|min:0|max:100',
            'dri' => 'required|integer|min:0|max:100',
            'def' => 'required|integer|min:0|max:100',
            'phy' => 'required|integer|min:0|max:100',
            'prime_rating' => 'required|integer|min:0|max:100',
            'club_id' => 'required',
            'country_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $player = Players::create($validator->validated());
        Cache::flush();

        return response()->json([
            'message' => 'Player created successfully',
            'data' => $player
        ], 201);
    }

    // DELETE
    public function destroy($id)
    {
        $player = Players::find($id);

        if (!$player) {
            return response()->json([
                'message' => 'Player not found',
            ], 404);
        }

        $player->delete();
        Cache::flush();

        return response()->json([
            'message' => 'Player deleted successfully',
        ], 200);
    }

    // PUT
    public function update(Request $request, $id)
    {
        $player = Players::find($id);

        if (!$player) {
            return response()->json([
                'message' => 'Player not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'known_as' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'img' => 'required|url',
            'prime_season' => 'required|string|max:20',
            'prime_position' => 'required|string|max:255',
            'preferred_foot' => 'required|string|in:left,right,both',
            'spd' => 'required|integer|min:0|max:100',
            'sho' => 'required|integer|min:0|max:100',
            'pas' => 'required|integer|min:0|max:100',
            'dri' => 'required|integer|min:0|max:100',
            'def' => 'required|integer|min:0|max:100',
            'phy' => 'required|integer|min:0|max:100',
            'prime_rating' => 'required|integer|min:0|max:100',
            'club_id' => 'required',
            'country_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $player->update($validator->validated());
        Cache::flush();

        return response()->json([
            'message' => 'Player updated successfully',
            'data' => $player
        ], 200);
    }

    // PATCH
    public function updatePartial(Request $request, $id)
    {
        $player = Players::find($id);

        if (!$player) {
            return response()->json([
                'message' => 'Player not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'known_as' => 'string|max:255',
            'full_name' => 'string|max:255',
            'img' => 'url',
            'prime_season' => 'string|max:20',
            'prime_position' => 'string|max:255',
            'preferred_foot' => 'string|in:left,right,both',
            'spd' => 'integer|min:0|max:100',
            'sho' => 'integer|min:0|max:100',
            'pas' => 'integer|min:0|max:100',
            'dri' => 'integer|min:0|max:100',
            'def' => 'integer|min:0|max:100',
            'phy' => 'integer|min:0|max:100',
            'prime_rating' => 'integer|min:0|max:100',
            'club_id' => '',
            'country_id' => '',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // only update the fields sended
        $player->update($validator->validated());
        Cache::flush();

        return response()->json([
            'message' => 'Player updated successfully',
            'data' => $player
        ], 200);
    }
}
