<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Models\Answer;
use App\Models\Question;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
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
     * @param Question $question
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function store(Request $request, Question $question): Response
    {
        $fields = $request->validate([
            'body' => 'required|string|min:10|max:1500',
        ]);
        $answer = $question->answers()->create([
            'unique' => $this->generateAnswerUId(),
            'body' => $fields['body'],
            'user_id' => $request->user()->id,
        ]);
        return $this->onSuccess($answer, 'Your answer has been submitted.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Question $question
     * @param Answer $answer
     * @return Response
     * @throws AuthorizationException
     */
    public function edit(Question $question, Answer $answer): Response
    {
        $this->authorize('update', $answer);
        return $this->onSuccess($answer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Answer $answer
     * @return Response
     */
    public function update(Request $request, Question $question, Answer $answer): Response
    {
        $this->authorize('update', $answer);
        $answer->update($request->validate([
            'body' => 'required|string|min:10|max:1500',
        ]));
        return $this->onSuccess($answer, "Your answer has been updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Question $question
     * @param Answer $answer
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy(Question $question, Answer $answer): Response
    {
        $this->authorize('delete', $answer);
        $answer->delete();
        return $this->onSuccess('Your answer has been deleted.');
    }
}
