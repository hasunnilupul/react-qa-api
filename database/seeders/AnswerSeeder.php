<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Answer::create([
            'unique'=>random_int(1000000000,9999999999),
            'question_id' => Question::find(1)->id,
            'body' => Str::random(150),
            'user_id' => User::find(1)->id,
        ]);
        Answer::create([
            'unique'=>random_int(1000000000,9999999999),
            'question_id' => Question::find(2)->id,
            'body' => Str::random(150),
            'user_id' => User::find(2)->id,
        ]);
        Answer::create([
            'unique'=>random_int(1000000000,9999999999),
            'question_id' => Question::find(2)->id,
            'body' => Str::random(150),
            'user_id' => User::find(1)->id,
        ]);
    }
}
