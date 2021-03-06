<?php

namespace App\Models;

use App\Http\Library\VotableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Parsedown;

class Question extends Model
{
    use HasFactory, VotableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'unique',
        'title',
        'slug',
        'body',
        'answer_id',
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
     * The attributes that should be appended
     *
     * @var array<int, string>
     */
    protected $appends = [
        'excerpt',
        'status',
        'created_date',
        'updated_date',
    ];

    /**
     * set title and slug
     *
     * @param string $value
     */
    public function setTitleAttribute(string $value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    /**
     * set and clean body
     *
     * @param string $value
     * @return void
     */
    public function setBodyAttribute(string $value)
    {
        $this->attributes['body'] = clean($value);
    }

    /**
     * return body limited to a length
     *
     * @return string
     */
    public function getExcerptAttribute(): string
    {
        return Str::limit(strip_tags($this->bodyHtml()), 250);
    }

    private function bodyHtml(): string
    {
        return Parsedown::instance()->text($this->body);
    }

    /**
     * return question status
     *
     * @return string
     */
    public function getStatusAttribute(): string
    {
        if ($this->answers_count > 0) {
            if ($this->answer_id) {
                return 'accepted';
            }
            return 'answered';
        }
        return 'unanswered';
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
     * return bookmarks_count
     *
     * @return mixed
     */
    public function getBookmarksCountAttribute()
    {
        return $this->bookmarks->count();
    }

    /**
     * return is_bookmarked attribute
     *
     * @return bool
     */
    public function getIsBookmarkedAttribute(): bool
    {
        return $this->isBookmarked();
    }

    /**
     * return question is bookmarked by current user
     *
     * @return bool
     */
    public function isBookmarked(): bool
    {
        return $this->bookmarks->where('user_id', auth()->id())->count() > 0;
    }

    /**
     * User of the question
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Question answers
     *
     * @return HasMany
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class)->without('question')->orderBy('votes_count', 'desc');
    }

    /**
     * Question answers unordered
     *
     * @return HasMany
     */
    public function answersUnordered(): HasMany
    {
        return $this->hasMany(Answer::class)->without('question');
    }

    /**
     * Question bookmarked users
     *
     * @return BelongsToMany
     */
    public function bookmarks(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'bookmarks')->withTimestamps();
    }

    /**
     * Accept an answer
     *
     * @param Answer $answer
     */
    public function acceptAnswer(Answer $answer)
    {
        $this->answer_id = $answer->id;
        $this->save();
    }
}
