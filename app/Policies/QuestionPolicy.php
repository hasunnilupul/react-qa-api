<?php

namespace App\Policies;

use App\Models\Question;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class QuestionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Question $question
     * @return bool
     */
    public function view(User $user, Question $question): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Question $question
     * @return bool
     */
    public function update(User $user, Question $question): bool
    {
        return $user->id == $question->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Question $question
     * @return bool
     */
    public function delete(User $user, Question $question): bool
    {
        return $user->id == $question->user_id && $question->answers < 1;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Question $question
     * @return Response|bool
     */
    public function restore(User $user, Question $question)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Question $question
     * @return Response|bool
     */
    public function forceDelete(User $user, Question $question)
    {
        //
    }
}
