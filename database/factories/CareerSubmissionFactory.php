<?php

namespace Database\Factories;

use App\Models\Career;
use App\Models\CareerSubmission;
use Illuminate\Database\Eloquent\Factories\Factory;

class CareerSubmissionFactory extends Factory
{
    protected $model = CareerSubmission::class;

    public function definition()
    {
        return [
            'career_id' => Career::factory(),
            'full_name' => $this->faker->name,
            'email' => $this->faker->email,
            'mobile_phone' => $this->faker->phoneNumber,
            'resume_path' => 'career-submissions/resume.pdf',
            'message' => $this->faker->sentence,
            'status' => 'pending',
        ];
    }
}
