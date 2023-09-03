<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\MovieCollection;
use App\Http\Resources\MovieResource;
use App\Models\Comment;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class MovieController extends BaseController
{
    /**
     * Display a listing of the movies
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Movie::with('genres', 'author')->latest()->get();

        $collection = MovieCollection::collection($data);
        $paginatedResult = $this->paginatedData($collection);

        return $this->sendResponse($paginatedResult, 'Movies retrieved successfully.');
    }

    /**
     * Create a movie
     *
     * @formData genres array
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
           'title' => 'required',
           'summary' => 'required',
           'cover_image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
           'author_id' => 'required|exists:authors,id',
           'imdb_rating' => 'required|numeric|min:0|max:10',
           'pdf_file' => 'required|mimes:pdf|max:10000',
       ]);

        $input = $request->all();
        $input['cover_image'] = $request->file('cover_image')->store('image', 'public');
        $input['pdf_file'] = $request->file('pdf_file')->store('pdf', 'public');

        $movie = Movie::create($input);

        if ($input['genres']) {
            $movie->genres()->attach($input['genres']);
        }
        if ($input['tags']) {
            $movie->tags()->attach($input['tags']);
        }

        return $this->sendResponse($movie, 'Movie created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $movie = Movie::find($id);

        if (is_null($movie)) {
            return $this->sendError('Movie not found.');
        }

        return $this->sendResponse(new MovieResource($movie), 'Movie retrieved successfully.');
    }

    /**
     * Update a movie
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Movie $movie)
    {
        $this->validate($request, [
          'title' => 'required',
          'summary' => 'required',
          'author_id' => 'required|exists:authors,id',
          'imdb_rating' => 'required|numeric|min:0|max:10',
       ]);

        if ($request->hasFile('cover_image')) {
            $movie->cover_image = $request->file('cover_image')->store('image', 'public');
        }

        if ($request->hasFile('pdf_file')) {
            $movie->pdf_file = $request->file('pdf_file')->store('pdf', 'public');

        }

        $movie->title = $request->title;
        $movie->summary = $request->summary;
        $movie->author_id = $request->author_id;
        $movie->imdb_rating = $request->imdb_rating;
        $movie->save();

        if($request->genres) {
            $movie->genres()->sync($request->genres);
        }
        if($request->tags) {
            $movie->tags()->sync($request->tags);
        }

        return $this->sendResponse($movie, 'Movie updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Movie $movie)
    {
        $movie->delete();
        return $this->sendResponse([], 'Movie deleted successfully.');
    }

    public function storeComment(Request $request, Movie $movie)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'text' => 'required',
        ]);

        if (is_null($movie)) {
            return $this->sendError('Movie not found.');
        }

        $comment = new Comment();
        $comment->email = $request->email;
        $comment->text = $request->text;

        $movie->comments()->save($comment);

        return $this->sendResponse($movie, 'Comment successfully.');

    }
}
