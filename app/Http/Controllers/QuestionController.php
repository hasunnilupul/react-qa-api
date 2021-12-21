<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use function Illuminate\Support\Str;

class QuestionController extends Controller
{
    /**
     * constructor.
     */
    public function __construct(){
        $this->middleware('auth:sanctum')->only(['store','update','destroy']);
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
                $questions = Question::orderByDesc('created_at')->paginate(25);
        }
        return response($questions);
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
            'description' => 'required|string|min:10|max:1500',
        ]);
        $question = Question::create([
            'unique' => random_int(1000000000, 9999999999),
            'title' => $fields['title'],
            'slug' => Str::slug($fields['title']),
            'description' => $fields['description'],
            'user_id' => $request->user()->id
        ]);

        return response([
            'question' => $question,
            'message' => 'Your question has been posted.'
        ]);
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
        $question = Question::where('unique', $unique)->where('slug', $slug)->first();
        return response($question);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Question $question
     * @return Response
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Question $question
     * @return Response
     */
    public function update(Request $request, Question $question)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Question $question
     * @return Response
     */
    public function destroy(Question $question)
    {
        //
    }
}
