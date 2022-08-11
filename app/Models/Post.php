<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'user_id',
    ];

    protected $casts = [
        'body' => 'array',
    ];

    /**
     * Get the comments for the post.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the user that owns the post.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'post_user', 'post_id', 'user_id');
    }

    /**
     * Get upper case for title.
     * 
     * @return string
     */
    public function getTitleUpperCaseAttribute()
    {
        return strtoupper($this->title);
    }

    /**
     * Set lower case for title.
     * 
     * @param string $value The value to set.
     * 
     * @return string
     */
    public function setTitleLowerCaseAttribute($value)
    {
        $this->attributes['title'] = strtolower($value);
    }
}
