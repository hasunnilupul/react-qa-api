<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Response;

class ShortenLinkController extends Controller
{
    use ApiHelpers;

    /**
     * Return the specified resource unique and slug.
     *
     * @param Question $question
     * @return Response
     */
    public function question_show(Question $question): Response
    {
        return $this->onSuccess([
            'unique' => $question->unique,
            'slug' => $question->slug
        ]);
    }

    /**
     * Return specified resource parent unique and slug.
     *
     * @param Answer $answer
     * @return Response
     */
    public function answer_show(Answer $answer): Response
    {
        $answer->load('question');
        return $this->onSuccess([
            'unique' => $answer->question->unique,
            'slug' => $answer->question->slug
        ]);
    }
}
