<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Models\Question;
use Illuminate\Http\Response;

class BookmarksController extends Controller
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
     * @param Question $question
     * @return Response
     */
    public function store(Question $question): Response
    {
        $question->bookmarks()->attach(auth()->id());
        return $this->onSuccess(true , "question added to bookmarks.");
    }

    /**
     * @param Question $question
     * @return Response
     */
    public function destroy(Question $question): Response
    {
        $result = $question->bookmarks()->detach(auth()->id());
        return $this->onSuccess(false, "question removed from bookmarks.");
    }
}
