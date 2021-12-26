<?php

namespace App\Http\Library;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait VotableTrait
{

    /**
     * Model votes
     *
     * @return MorphToMany
     */
    public function votes(): MorphToMany
    {
        return $this->morphToMany(User::class, 'votable');
    }

    /**
     * @return MorphToMany
     */
    public function upVotes(): MorphToMany
    {
        return $this->votes()->wherePivot('vote', 1);
    }

    /**
     * @return MorphToMany
     */
    public function downVotes(): MorphToMany
    {
        return $this->votes()->wherePivot('vote', -1);
    }
}
