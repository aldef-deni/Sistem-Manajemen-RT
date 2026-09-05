<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Halaman depan mengalihkan tamu ke form masuk — sistem ini tidak punya
     * halaman publik.
     */
    public function test_halaman_depan_mengalihkan_ke_login(): void
    {
        $this->get('/')->assertRedirect(route('login'));
    }
}
