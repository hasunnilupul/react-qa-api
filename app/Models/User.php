<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that should be append
     *
     * @var array<int, string>
     */
    protected $appends = [
        'avatar',
    ];

    /**
     * get user avatar url using gravatar
     *
     * @return string
     */
    public function getAvatarAttribute(): string
    {
        $email = $this->email;
        $size = 32;

        return "https://www.gravatar.com/avatar/" . md5(strtolower(trim($email))) . "?s=" . $size;
    }

    /**
     * User's role
     *
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Questions of the user
     *
     * @return HasMany
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Answers of the user
     *
     * @return HasMany
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * User bookmarked questions
     *
     * @return BelongsToMany
     */
    public function bookmarks(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'bookmarks')->withTimestamps();
    }

    /**
     * Vote a question
     *
     * @param Question $question
     * @param int $vote
     * @return void
     */
    public function voteQuestion(Question $question, int $vote)
    {
        $voteQuestions = $this->votedQuestions();
        $this->voteQuestionOrAnswer($voteQuestions, $question, $vote);
    }

    /**
     * User voted questions
     *
     * @return MorphToMany
     */
    public function votedQuestions(): MorphToMany
    {
        return $this->morphedByMany(Question::class, 'votable');
    }

    /**
     * @param MorphToMany $relationship
     * @param Model $model
     * @param int $vote
     * @return void
     */
    private function voteQuestionOrAnswer(MorphToMany $relationship, Model $model, int $vote): void
    {
        if ($relationship->where('votable_id', $model->id)->exists()) {
            $relationship->updateExistingPivot($model, ['vote' => $vote]);
        } else {
            $relationship->attach($model, ['vote' => $vote]);
        }

        $model->load('votes');
        $downVotes = (int)$model->downVotes()->sum('vote');;
        $upVotes = (int)$model->upVotes()->sum('vote');
        $model->votes_count = $upVotes + $downVotes;
        $model->save();
    }

    /**
     * Vote an answer
     *
     * @param Answer $answer
     * @param int $vote
     * @return void
     */
    public function voteAnswer(Answer $answer, int $vote)
    {
        $voteAnswers = $this->votedAnswers();
        $this->voteQuestionOrAnswer($voteAnswers, $answer, $vote);
    }

    /**
     * User voted answers
     *
     * @return MorphToMany
     */
    public function votedAnswers(): MorphToMany
    {
        return $this->morphedByMany(Answer::class, 'votable');
    }
}
