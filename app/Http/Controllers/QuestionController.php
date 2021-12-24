<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Models\Question;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
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
        $questions = null;
        $order = $request->query('order');
        switch ($order) {
            case 'views':
                $questions = Question::orderByDesc('views')->paginate(25);
                break;
            default:
                $questions = Question::latest('created_at')->paginate(25);
        }
        return $this->onSuccess($questions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        //
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
            'unique' => $this->generateUId(),
            'title' => $fields['title'],
            'body' => $fields['description'],
            'user_id' => $request->user()->id
        ]);

        return $this->onSuccess($question, 'Your question has been posted.');
    }

    /**
     * Display the specified resource.
     *
     * @param string $unique
     * @param string $slug
     * @return Response
     */
    public function show(string $unique, string $slug): Response
    {
        $question = Question::whereUnique($unique)->whereSlug($slug)->first();
        $question->increment('views');
        return $this->onSuccess($question);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param string $unique
     * @param string $slug
     * @return Response
     * @throws AuthorizationException
     */
    public function edit(Request $request, string $unique, string $slug): Response
    {
        $question = Question::whereUnique($unique)->whereSlug($slug)->first();
        $this->authorize('update', $question);
        return $this->onSuccess($question);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param string $unique
     * @param string $slug
     * @return Response
     * @throws AuthorizationException
     */
    public function update(Request $request, string $unique, string $slug): Response
    {
        $question = Question::whereUnique($unique)->whereSlug($slug)->first();
        $this->authorize('update', $question);
        $fields = $request->validate([
            'title' => 'required|string:min:10',
            'body' => 'required|string|min:10|max:1500',
        ]);
        $question->update([
            'title' => $fields['title'],
            'body' => $fields['description'],
        ]);
        return $this->onSuccess($question, "Question updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param string $unique
     * @param string $slug
     * @return Response
     */
    public function destroy(Request $request, string $unique, string $slug): Response
    {
        $question = Question::whereUnique($unique)->whereSlug($slug)->first();
        $this->authorize('delete', $question);
        if (empty($question)) {
            return $this->onError(404, "Question not found.");
        }
        $data = Question::destroy($question->id);
        if ($data == 1)
            return $this->onSuccess($data, "Question deleted successfully");
        else
            return $this->onError(404, "Question delete failed.");
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
        if(count($questions)==0){
            return $this->onSuccess($questions, "no results found.");
        }
        return $this->onSuccess($questions, count($questions)." results found.");
    }
}
