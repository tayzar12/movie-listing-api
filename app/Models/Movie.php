<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'summary',
        'cover_image',
        'genre_id',
        'author_id',
        'imdb_rating',
        'pdf_file',
    ];

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function getCoverImageAttribute($value)
    {
        if ($value == 'default_movie.png') {
            return asset($value);
        }

        return asset('storage/'.$value);
    }

    public function getPdfFileAttribute($value)
    {
        if ($value == 'default_file.pdf') {
            return asset($value);
        }

        return asset('storage/'.$value);
    }

    public function getRelatedMoviesAttribute()
    {
        $movie = $this;

        $related_movies = Movie::select('id', 'title', 'cover_image', 'imdb_rating')->whereHas('genres', function ($q) use ($movie) {
            return $q->whereIn('genre_id', $movie->genres->pluck('id'));
        })
        ->whereHas('tags', function ($q) use ($movie) {
            return $q->whereIn('tag_id', $movie->tags->pluck('id'));
        })
        ->orWhere('author_id', $this->author_id)
        ->orWhere('imdb_rating', '>=', $this->imdb_rating)
        ->orderBy('imdb_rating', 'DESC')->limit(7)->get();

        return $related_movies;
    }
}
