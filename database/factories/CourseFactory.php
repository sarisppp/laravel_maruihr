<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {  
        return [
            'course' =>$this->faker->text(10),
            'description' =>$this->faker->text(35),
            'speaker' =>$this->faker->text(5),
            'place' =>$this->faker->text(20),
            'hour' =>$this->faker->numberBetween(1,8),
            'date' =>$this->faker->date($format = 'Y-m-d', $max = 'now'),
            'time' =>$this->faker->time($format = 'H:i:s', $max = 'now')
        ];
    }
}
