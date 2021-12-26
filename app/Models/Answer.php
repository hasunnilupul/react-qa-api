<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Answer extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'unique',
        'body',
        'user_id'
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];
    /**
     * The attributes that should be append
     *
     * @var array<int, string>
     */
    protected $appends = [
        'body_html',
        'created_date',
        'updated_date',
    ];

    /**
     * On model access
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::created(function ($answer) {
            $answer->question->increment('answers_count');
            $answer->question->save();
        });
        static::deleted(function ($answer) {
            $answer->question->decrement('answers_count');
            $answer->question->save();
        });
    }

    /**
     * return answer body as html
     *
     * @return string
     */
    public function getBodyHtmlAttribute(): string
    {
        return \Parsedown::instance()->text($this->body);
    }

    /**
     * return is answer accepted
     *
     * @return bool
     */
    public function getIsAcceptedAttribute(): bool
    {
        return $this->id == $this->question->answer_id;
    }

    /**
     * return created_date as diffForHumans
     *
     * @return mixed
     */
    public function getCreatedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * return updated_date as diffForHumans
     *
     * @return mixed
     */
    public function getUpdatedDateAttribute()
    {
        return $this->updated_at->diffForHumans();
    }

    /**
     * Question of the answer
     *
     * @return BelongsTo
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * User of the answer
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Answer votes
     *
     * @return MorphToMany
     */
    public function votes(): MorphToMany
    {
        return $this->morphToMany(User::class, 'votable');
    }
}
