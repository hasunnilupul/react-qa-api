<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Http\Requests\AnswerDeleteRequest;
use App\Http\Requests\AnswerEditRequest;
use App\Http\Requests\AnswerStoreRequest;
use App\Http\Requests\AnswerUpdateRequest;
use App\Models\Answer;
use App\Models\Question;
use Exception;
use Illuminate\Http\Response;

class AnswerController extends Controller
{
    use ApiHelpers;

    /**
     * constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AnswerStoreRequest $request
     * @param Question $question
     * @return Response
     * @throws Exception
     */
    public function store(Question $question, AnswerStoreRequest $request): Response
    {
        $answer = $question->answers()->create($request->validated());
        return $this->onSuccess($answer, 'Your answer has been submitted.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Question $question
     * @param Answer $answer
     * @param AnswerEditRequest $request
     * @return Response
     */
    public function edit(Question $question, Answer $answer, AnswerEditRequest $request): Response
    {
        return $this->onSuccess($answer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Question $question
     * @param Answer $answer
     * @param AnswerUpdateRequest $request
     * @return Response
     */
    public function update(Question $question, Answer $answer, AnswerUpdateRequest $request): Response
    {
        $answer->update($request->validated());
        return $this->onSuccess($answer, "Your answer has been updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Question $question
     * @param Answer $answer
     * @param AnswerDeleteRequest $request
     * @return Response
     */
    public function destroy(Question $question, Answer $answer, AnswerDeleteRequest $request): Response
    {
        $answer->delete();
        return $this->onSuccess('Your answer has been deleted.');
    }
}
