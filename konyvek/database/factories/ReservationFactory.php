<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $repeats = 10;
        do {
            $user_id = User::all()->random()->id;
            $book_id = Book::all()->random()->item_id;
            $start = fake()->date();
            $reservation = Reservation::where('user_id', $user_id)
                ->where('book_id', $book_id)
                ->get();
            $repeats--;
        } while ($repeats >= 0 && count($reservation) > 0);

        return [
            'user_id' => $user_id,
            'book_id' => $book_id,
            'start' => $start,
            'message' => rand(0,1)

        ];
    }
}
