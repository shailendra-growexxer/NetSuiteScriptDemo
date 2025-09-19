<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(3);
        $author = fake()->name();
        $isbn = fake()->unique()->isbn13();
        
        return [
            'isbn' => $isbn,
            'title' => $title,
            'author' => $author,
            'description' => fake()->paragraph(3),
            'price' => fake()->randomFloat(2, 5, 99),
            'pages' => fake()->numberBetween(100, 800),
            'publisher' => fake()->company(),
            'published_date' => fake()->dateTimeBetween('-10 years', 'now'),
            'genre' => fake()->randomElement(['Fiction', 'Non-Fiction', 'Science Fiction', 'Mystery', 'Romance', 'Biography', 'History', 'Self-Help']),
            'language' => fake()->randomElement(['English', 'Spanish', 'French', 'German', 'Italian']),
            'stock' => fake()->numberBetween(0, 500),
            'is_active' => fake()->boolean(90),
        ];
    }
}
