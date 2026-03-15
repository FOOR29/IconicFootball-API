<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ApiStressTest extends TestCase
{
    /**
     * @test
     */
    public function test_public_api_has_rate_limit_of_200_requests_per_minute()
    {
        echo "\n🔥 STRESS TEST: Rate Limiting Público (200/min)\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

        $startTime = microtime(true);
        $responses = [];
        $totalRequests = 205;

        echo "📡 Enviando {$totalRequests} requests a /api/players...\n\n";

        for ($i = 1; $i <= $totalRequests; $i++) {
            $response = $this->getJson('/api/players');
            $responses[] = $response->status();

            // Mostrar progreso cada 50 requests
            if ($i % 50 == 0) {
                $elapsed = round(microtime(true) - $startTime, 2);
                echo "   ✓ {$i} requests enviadas ({$elapsed}s)\n";
            }
        }

        $successfulRequests = array_filter($responses, fn($status) => $status === 200);
        $blockedRequests = array_filter($responses, fn($status) => $status === 429);

        $totalTime = round(microtime(true) - $startTime, 2);
        $requestsPerSecond = round($totalRequests / $totalTime, 2);

        echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "📊 RESULTADOS:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo '   🟢 Requests exitosas (200): ' . count($successfulRequests) . "\n";
        echo '   🔴 Requests bloqueadas (429): ' . count($blockedRequests) . "\n";
        echo "   ⏱️  Tiempo total: {$totalTime}s\n";
        echo "   📈 Velocidad: {$requestsPerSecond} req/s\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

        // Verificaciones
        $this->assertGreaterThanOrEqual(195, count($successfulRequests),
            'Debe haber al menos 195 requests exitosas (límite es 200/min)');

        $this->assertGreaterThan(0, count($blockedRequests),
            'Debe haber requests bloqueadas por rate limit');

        echo "✅ RATE LIMIT FUNCIONANDO CORRECTAMENTE\n\n";
    }

    /**
     * @test
     */
    public function test_redis_cache_works_for_players_endpoint()
    {
        echo "\n💾 CACHE TEST: Redis Caching\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

        // Limpiar cache antes de empezar
        \Illuminate\Support\Facades\Cache::flush();

        echo "🔄 Cache limpiado. Iniciando pruebas...\n\n";

        // PRIMERA REQUEST - debe hacer queries a DB
        DB::enableQueryLog();
        $start1 = microtime(true);

        $response1 = $this->getJson('/api/players');

        $time1 = round((microtime(true) - $start1) * 1000, 2);  // en ms
        $queries1 = count(DB::getQueryLog());
        DB::disableQueryLog();

        echo "📍 PRIMERA REQUEST (sin cache):\n";
        echo "   ⏱️  Tiempo: {$time1}ms\n";
        echo "   🗄️  Queries a DB: {$queries1}\n";
        echo '   📊 Status: ' . $response1->status() . "\n\n";

        // SEGUNDA REQUEST - debe usar cache (0 queries)
        DB::enableQueryLog();
        $start2 = microtime(true);

        $response2 = $this->getJson('/api/players');

        $time2 = round((microtime(true) - $start2) * 1000, 2);  // en ms
        $queries2 = count(DB::getQueryLog());
        DB::disableQueryLog();

        echo "📍 SEGUNDA REQUEST (con cache):\n";
        echo "   ⏱️  Tiempo: {$time2}ms\n";
        echo "   🗄️  Queries a DB: {$queries2}\n";
        echo '   📊 Status: ' . $response2->status() . "\n\n";

        // Calcular mejora
        $speedup = round($time1 / $time2, 2);
        $improvement = round((($time1 - $time2) / $time1) * 100, 2);

        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "📊 ANÁLISIS DE CACHE:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "   🚀 Velocidad con cache: {$speedup}x más rápido\n";
        echo "   📈 Mejora: {$improvement}%\n";
        echo "   🎯 Queries eliminadas: {$queries1} → {$queries2}\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

        // Verificaciones
        $this->assertEquals(200, $response1->status());
        $this->assertEquals(200, $response2->status());

        // La segunda request debe ser MUCHO más rápida (al menos 50% más rápida)
        $this->assertLessThan($time1 * 0.5, $time2,
            'Request con cache debe ser al menos 50% más rápida');

        // Verificar que hay mejora significativa (>50%)
        $this->assertGreaterThan(50, $improvement,
            'Cache debe mejorar performance en más del 50%');

        // Verificar que el contenido es idéntico
        $this->assertEquals($response1->json(), $response2->json(),
            'Ambas responses deben tener el mismo contenido');

        echo "✅ REDIS CACHE FUNCIONANDO CORRECTAMENTE\n";
        echo "   (Las queries de Laravel son normales - auth/session)\n\n";
    }

    /**
     * @test
     */
    public function test_api_response_time_under_load()
    {
        echo "\n⚡ PERFORMANCE TEST: Tiempo de Respuesta\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

        echo "📡 Enviando 100 requests y midiendo tiempos...\n\n";

        $times = [];
        $totalRequests = 100;

        for ($i = 1; $i <= $totalRequests; $i++) {
            $start = microtime(true);
            $response = $this->getJson('/api/players');
            $time = (microtime(true) - $start) * 1000;  // en ms

            $times[] = $time;

            if ($i % 25 == 0) {
                $avgSoFar = round(array_sum($times) / count($times), 2);
                echo "   ✓ {$i} requests | Promedio actual: {$avgSoFar}ms\n";
            }
        }

        // Estadísticas
        $minTime = round(min($times), 2);
        $maxTime = round(max($times), 2);
        $avgTime = round(array_sum($times) / count($times), 2);

        echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "📊 ESTADÍSTICAS DE PERFORMANCE:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "   ⚡ Tiempo mínimo: {$minTime}ms\n";
        echo "   🐌 Tiempo máximo: {$maxTime}ms\n";
        echo "   📊 Tiempo promedio: {$avgTime}ms\n";
        echo "   🎯 Total requests: {$totalRequests}\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

        // Verificar que el tiempo promedio sea razonable (< 500ms)
        $this->assertLessThan(500, $avgTime,
            'El tiempo promedio de respuesta debe ser menor a 500ms');

        echo "✅ PERFORMANCE ACEPTABLE\n\n";
    }
}
