<?php

namespace App\Http\Library;

use App\Models\Answer;
use App\Models\Question;
use Exception;

trait UIDTrait
{
    /**
     * Generate unique id for answer
     *
     * @return int
     * @throws Exception
     */
    protected function generateAnswerUId(): int
    {
        $uid = random_int(1000000000, 9999999999);
        if (Answer::whereUnique($uid)->exists()) {
            return $this->generateAnswerUId();
        }
        return $uid;
    }

    /**
     * Generate unique id for question
     *
     * @return int
     * @throws Exception
     */
    protected function generateQuestionUId(): int
    {
        $uid = random_int(1000000000, 9999999999);
        if (Question::whereUnique($uid)->exists()) {
            return $this->generateQuestionUId();
        }
        return $uid;
    }
}
