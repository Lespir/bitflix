<?php

require_once __DIR__ . '/services/text-modifier.php';



function getGenres(): array
{
    static $genres = null;

    if ($genres !== null)
    {
        return $genres;
    }

    require __DIR__ . '/data/movies.php';

    if (isset($genres) && is_array($genres))
    {
        return $genres;
    }
    $genres = [];
    return $genres;
}

function getGenreValue(string $genreKey): ?string
{
    return getGenres()[$genreKey];
}

function getMovies(): array
{
    static $movies = null;

    if ($movies !== null)
    {
        return $movies;
    }

    require __DIR__ . '/data/movies.php';

    if (isset($movies) && is_array($movies))
    {
        return $movies;
    }

    $movies = [];
    return $movies;
}

function getMovieById(?int $movieId = null) : array
{
    if ($movieId !== null)
    {
        foreach (getMovies() as $movie)
        {
            if ($movie['id'] === $movieId)
            {
                return $movie;
            }
        }
        return [];
    }
    return [];
}

function genreInMovie(array $movie, ?string $genre = null): bool
{
    if ($genre !== null)
    {
        // Приводим жанры к нижнему регистру и ищем в массиве жанров фильма
        $filteredGenre = strtolower($genre);
        $movieGenres = array_map('strtolower', $movie['genres']);

        if (!in_array($filteredGenre, $movieGenres))
        {
            return false;
        }
    }
    return true;
}

function titleInMovie(array $movie, ?string $title = null): bool
{
    if ($title !== null)
    {
        // Приводим оба заголовка к нижнему регистру и ищем совпадение в названии фильма
        $filteredTitle = strtolower($title);
        $movieTitle = strtolower($movie['title']);
        $movieOriginalTitle = strtolower($movie['original-title']);

        if ((!str_starts_with(toLowerCase($movieTitle), toLowerCase($filteredTitle))) &&
            (!str_starts_with(toLowerCase($movieOriginalTitle), toLowerCase($filteredTitle))))
        {
            return false;
        }
    }
    return true;
}


function filterMovies(array $movies, array $movieFilters): array
{
    return array_filter($movies, function($movie) use ($movieFilters) {

        return genreInMovie($movie, $movieFilters['genre']) && titleInMovie($movie, $movieFilters['title']);
    });
}
