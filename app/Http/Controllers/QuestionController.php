<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Models\Question;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function Illuminate\Support\Str;

class QuestionController extends Controller
{
    use ApiHelpers;

    /**
     * constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show', 'search']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $questions = [];
        $query = Question::with('user');
        $order = $request->query('order');
        switch ($order) {
            case 'views':
                $query = $query->orderByDesc('views');
                break;
            default:
                $query = $query->latest('created_at');
        }
        $questions = $query->paginate(25);
        return $this->onSuccess($questions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function store(Request $request): Response
    {
        $fields = $request->validate([
            'title' => 'required|string:min:10',
            'body' => 'required|string|min:10|max:1500',
        ]);
        $question = Question::create([
            'unique' => $this->generateQuestionUId(),
            'title' => $fields['title'],
            'body' => $fields['description'],
            'user_id' => $request->user()->id
        ]);

        return $this->onSuccess($question, 'Your question has been posted.');
    }

    /**
     * Display the specified resource.
     *
     * @param Question $question
     * @param string|null $slug
     * @return Response
     */
    public function show(Question $question, string $slug = null): Response
    {
        $question->load(['answers']);
        $question->increment('views');
        return $this->onSuccess($question);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Question $question
     * @param string|null $slug
     * @return Response
     * @throws AuthorizationException
     */
    public function edit(Question $question, string $slug = null): Response
    {
        $this->authorize('update', $question);
        return $this->onSuccess($question);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Question $question
     * @param string|null $slug
     * @param Request $request
     * @return Response
     * @throws AuthorizationException
     */
    public function update(Question $question, Request $request, string $slug = null): Response
    {
        $this->authorize('update', $question);
        $fields = $request->validate([
            'title' => 'required|string:min:10',
            'body' => 'required|string|min:10|max:1500',
        ]);
        $question->update([
            'title' => $fields['title'],
            'body' => $fields['description'],
        ]);
        return $this->onSuccess($question, "Your question updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Question $question
     * @param string|null $slug
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy(Question $question, string $slug = null): Response
    {
        $this->authorize('delete', $question);
        if (empty($question)) {
            return $this->onError(404, "Question not found.");
        }
        return $this->onSuccess(Question::destroy($question->id), "Your question deleted.");
    }

    /**
     * Search resources for the specified title in storage
     *
     * @param string $keyword
     * @return Response
     */
    public function search(string $keyword): Response
    {
        $questions = Question::where('title', 'like', '%' . $keyword . '%')->get();
        if (count($questions) == 0) {
            return $this->onSuccess($questions, "no results found.");
        }
        return $this->onSuccess($questions, count($questions) . " results found.");
    }
}
