<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiFullTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_api_flow()
    {
        /*
         * |--------------------------------------------------------------------------
         * | 1️⃣ CREATE CLUB
         * |--------------------------------------------------------------------------
         */
        $clubResponse = $this->postJson('/api/clubs', [
            'name' => 'FC Barcelona',
            'logo' => 'https://res.cloudinary.com/test/barcelona.png'
        ]);

        $clubResponse
            ->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'FC Barcelona'
            ]);

        $clubId = $clubResponse->json('data.id');

        /*
         * |--------------------------------------------------------------------------
         * | 2️⃣ CREATE COUNTRY
         * |--------------------------------------------------------------------------
         */
        $countryResponse = $this->postJson('/api/countries', [
            'name' => 'Argentina',
            'logo' => 'https://res.cloudinary.com/test/arg.png'
        ]);

        $countryResponse
            ->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'Argentina'
            ]);

        $countryId = $countryResponse->json('data.id');

        /*
         * |--------------------------------------------------------------------------
         * | 3️⃣ CREATE PLAYER
         * |--------------------------------------------------------------------------
         */
        $playerResponse = $this->postJson('/api/players', [
            'known_as' => 'Messi',
            'full_name' => 'Lionel Andrés Messi',
            'img' => 'https://res.cloudinary.com/test/messi.png',
            'prime_season' => '2011-2012',
            'prime_position' => 'CF',
            'preferred_foot' => 'left',
            'spd' => 93,
            'sho' => 92,
            'pas' => 88,
            'dri' => 97,
            'def' => 28,
            'phy' => 66,
            'prime_rating' => 95,
            'club_id' => $clubId,
            'country_id' => $countryId
        ]);

        $playerResponse
            ->assertStatus(201)
            ->assertJsonFragment([
                'known_as' => 'Messi'
            ]);

        $playerId = $playerResponse->json('data.id');

        /*
         * |--------------------------------------------------------------------------
         * | 4️⃣ GET PLAYER WITH RELATIONS
         * |--------------------------------------------------------------------------
         */
        $this
            ->getJson("/api/players/{$playerId}?include=club,country")
            ->assertStatus(200)
            ->assertJsonFragment([
                'known_as' => 'Messi'
            ]);

        /*
         * |--------------------------------------------------------------------------
         * | 5️⃣ UPDATE PLAYER (PUT)
         * |--------------------------------------------------------------------------
         */
        $this
            ->putJson("/api/players/{$playerId}", [
                'known_as' => 'Leo Messi',
                'full_name' => 'Lionel Andrés Messi',
                'img' => 'https://res.cloudinary.com/test/messi.png',
                'prime_season' => '2011-2012',
                'prime_position' => 'CF',
                'preferred_foot' => 'left',
                'spd' => 94,
                'sho' => 93,
                'pas' => 89,
                'dri' => 97,
                'def' => 30,
                'phy' => 70,
                'prime_rating' => 96,
                'club_id' => $clubId,
                'country_id' => $countryId
            ])
            ->assertStatus(200)
            ->assertJsonFragment([
                'known_as' => 'Leo Messi'
            ]);

        /*
         * |--------------------------------------------------------------------------
         * | 6️⃣ PATCH PLAYER
         * |--------------------------------------------------------------------------
         */
        $this
            ->patchJson("/api/players/{$playerId}", [
                'sho' => 99
            ])
            ->assertStatus(200)
            ->assertJsonFragment([
                'sho' => 99
            ]);

        /*
         * |--------------------------------------------------------------------------
         * | 7️⃣ DELETE PLAYER
         * |--------------------------------------------------------------------------
         */
        $this
            ->deleteJson("/api/players/{$playerId}")
            ->assertStatus(200);

        /*
         * |--------------------------------------------------------------------------
         * | 8️⃣ DELETE CLUB
         * |--------------------------------------------------------------------------
         */
        $this
            ->deleteJson("/api/clubs/{$clubId}")
            ->assertStatus(200);

        /*
         * |--------------------------------------------------------------------------
         * | 9️⃣ DELETE COUNTRY
         * |--------------------------------------------------------------------------
         */
        $this
            ->deleteJson("/api/countries/{$countryId}")
            ->assertStatus(200);
    }
}
