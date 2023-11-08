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

use App\Providers\PhoneFormatProvider;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Arquivo Class CustomerController.
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
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $faker = FakerFactory::create('pt_BR');
        $faker->addProvider(new PhoneFormatProvider($faker));

        return [
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'phone' => $faker->phoneNumberWithFormat(),
            'address' => $faker->streetAddress,
            'complement' => $faker->streetName,
            'neighborhood' => $faker->city,
            'zipcode' => $faker->postcode,
            'date_of_birth' => $faker->date,
        ];
    }
}
