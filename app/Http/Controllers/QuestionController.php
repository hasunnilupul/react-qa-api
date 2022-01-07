<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Http\Requests\QuestionDeleteRequest;
use App\Http\Requests\QuestionEditRequest;
use App\Http\Requests\QuestionStoreRequest;
use App\Http\Requests\QuestionUpdateRequest;
use App\Models\Question;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
            case 'active':
                $query = $query->orderByDesc('updated_at');
                break;
            case 'unanswered':
                $query = $query->where('answers_count',0);
                break;
            case 'votes':
                $query = $query->orderByDesc('votes_count');
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
     * @param QuestionStoreRequest $request
     * @return Response
     * @throws Exception
     */
    public function store(QuestionStoreRequest $request): Response
    {
        $question = Question::create($request->validated());
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
        $question->load(['user']);
        $question->append(['is_bookmarked', 'bookmarks_count']);
        $question->setHidden(['bookmarks']);
        $question->__call('increment',['views']);
        return $this->onSuccess($question);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Question $question
     * @param QuestionEditRequest $request
     * @param string|null $slug
     * @return Response
     */
    public function edit(Question $question, QuestionEditRequest $request, string $slug = null): Response
    {
        return $this->onSuccess($question);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Question $question
     * @param QuestionUpdateRequest $request
     * @param string|null $slug
     * @return Response
     */
    public function update(Question $question, QuestionUpdateRequest $request, string $slug = null): Response
    {
        $question->update($request->validated());
        return $this->onSuccess($question, "Your question updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Question $question
     * @param QuestionDeleteRequest $request
     * @param string|null $slug
     * @return Response
     */
    public function destroy(Question $question, QuestionDeleteRequest $request, string $slug = null): Response
    {
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
