<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        Question::create([
            'unique' => random_int(1000000000,9999999999),
            'title' => 'Question one',
            'slug' => 'question-one',
            'body' => 'This is the question one.',
            'user_id' => User::find(1)->id
        ]);
        Question::create([
            'unique' => random_int(1000000000,9999999999),
            'title' => 'Question two',
            'slug' => 'question-two',
            'body' => 'This is the question two.',
            'user_id' => User::find(1)->id
        ]);
    }
}
