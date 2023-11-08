<?php

/**
 * MyClass File Doc Comment
 * php version 8.1
 *
 * @category Factories
 * @package  Database\Factories
 * @author   Marcos Motta <mrcsmotta1@gmail.com>
 * @license  MIT License
 * @link     https://github.com/mrcsmotta1/sistema-gerenciamento-pastelaria
 */

namespace Database\Factories;

use App\Providers\StringWithLimitProvider;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Arquivo Class Product Type.
 *
 * Factories Customer.
 *
 * @phpcs
 * php version 8.1
 *
 * @category Factories
 * @package  Database\Factories
 * @author   Marcos Motta <mrcsmotta1@gmail.com>
 * @license  MIT License
 * @link     https://github.com/mrcsmotta1/sistema-gerenciamento-pastelaria
 */
class ProductTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = FakerFactory::create('pt_BR');
        $faker->addProvider(new StringWithLimitProvider($faker));

        return [
            'name' => $faker->stringWithLimit(3, 50)
        ];
    }
}
