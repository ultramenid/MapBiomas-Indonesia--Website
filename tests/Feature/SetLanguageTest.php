<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SetLanguageTest extends TestCase
{
    use RefreshDatabase;
    public function test_valid_language_prefix_sets_locale(): void
    {
        $response = $this->get('/id');
        $response->assertOk();
        $this->assertEquals('id', app()->getLocale());

        $response = $this->get('/en');
        $response->assertOk();
        $this->assertEquals('en', app()->getLocale());
    }

    public function test_query_parameter_fuzzing_does_not_override_locale(): void
    {
        // Attackers fuzzing with ?lang=wp_Config.php~ or similar
        $response = $this->get('/en?lang=wp_Config.php~');
        $response->assertOk();

        // Must strictly remain 'en', never set to attacker query string
        $this->assertEquals('en', app()->getLocale());
    }

    public function test_invalid_language_route_prefix_returns_404(): void
    {
        $response = $this->get('/wp_Config.php~');
        $response->assertNotFound();

        $response = $this->get('/.env.old');
        $response->assertNotFound();
    }
}
