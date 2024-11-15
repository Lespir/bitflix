<?php

require_once __DIR__ . '/data/movies.php';
require_once __DIR__ . '/services/text-modifier.php';

function getGenres(): array
{
    global $genres;
    if (isset($genres) && is_array($genres))
    {
        return $genres;
    }

    return [];
}

function getGenreValue(string $genreKey): string
{
    try
    {
        return getGenres()[$genreKey];
    }
    catch (Exception $e)
    {
        return '';
    }
}

function getMovies(?int $movieId = null): array
{
    global $movies;
    if (!isset($movies) || !is_array($movies))
    {
        return [];
    }

    if ($movieId !== null)
    {
        foreach ($movies as $movie)
        {
            if ($movie['id'] === $movieId)
            {
                return $movie;
            }
        }
        return [];
    }
    return $movies;
}

function filterMovies(array $movies, array $movieFilters): array
{
    return array_filter($movies, function($movie) use ($movieFilters) {
        // Проверка фильтра по жанру
        if ($movieFilters['genre'] !== null)
        {
            // Приводим жанры к нижнему регистру и ищем в массиве жанров фильма
            $filteredGenre = strtolower($movieFilters['genre']);
            $movieGenres = array_map('strtolower', $movie['genres']);

            if (!in_array($filteredGenre, $movieGenres))
            {
                return false;
            }
        }

        // Проверка фильтра по заголовку
        if ($movieFilters['title'] !== null)
        {
            // Приводим оба заголовка к нижнему регистру и ищем совпадение в названии фильма
            $filteredTitle = strtolower($movieFilters['title']);
            $movieTitle = strtolower($movie['title']);
            $movieOriginalTitle = strtolower($movie['original-title']);

            if ((!str_starts_with(toLowerCase($movieTitle), toLowerCase($filteredTitle))) &&
                (!str_starts_with(toLowerCase($movieOriginalTitle), toLowerCase($filteredTitle))))
            {
                return false;
            }
        }

        return true;
    });
}
