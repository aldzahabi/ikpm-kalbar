<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Health check — tidak membutuhkan migrasi SQLite (migrasi proyek bermodal MySQL).
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/up');

        $response->assertOk();
    }
}
