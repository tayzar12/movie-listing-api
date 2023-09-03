<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MovieCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'summary' => $this->summary,
            'imdb_rating' => $this->imdb_rating,
            'pdf_file' => $this->pdf_file,
            'cover_image' => $this->cover_image,
            'author' => new AuthorResource($this->author),
            'genres' => GenreResource::collection($this->genres)
        ];
    }
}
