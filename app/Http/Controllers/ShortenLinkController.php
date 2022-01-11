<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Models\Question;
use Illuminate\Http\Response;

class ShortenLinkController extends Controller
{
    use ApiHelpers;

    /**
     * Display the specified resource.
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
}
