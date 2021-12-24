<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;

class AcceptAnswerController extends Controller
{
    use ApiHelpers;

    public function __invoke(Answer $answer)
    {
        $answer->question->acceptAnswer($answer);
        return $this->onSuccess($answer->id,"answer accepted as the best answer.");
    }
}
