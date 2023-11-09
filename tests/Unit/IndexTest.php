<?php

/**
 * MyClass File Doc Comment
 * php version 8.1
 *
 * @category Tests
 * @package  Tests\Unit\Api
 * @author   Marcos Motta <mrcsmotta1@gmail.com>
 * @license  MIT License
 * @link     https://github.com/mrcsmotta1/sistema-gerenciamento-pastelaria
 */

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class IndexTest
 *
 * This file deals with index access tests.
 *
 * @phpcs
 * php version 8.1
 *
 * @category Tests
 * @package  Tests\Unit\Api
 * @author   Marcos Motta <mrcsmotta1@gmail.com>
 * @license  MIT License
 * @link     https://github.com/mrcsmotta1/sistema-gerenciamento-pastelaria
 */
class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_accessing_the_index(): void
    {
        $response = $this->getJson('/');

        $response->assertStatus(200);

        $response->assertJson([
            [
                "message" => "Hello World, It's workink!",
            ],
        ]);
    }
}
