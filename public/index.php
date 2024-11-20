<?php

require_once __DIR__ . "/../boot.php";

$needFiltering = false;
$movieFilters = [
    'genre' => null,
    'title' => null,
];

$movies = getMovies();

if (isset($_GET['genre']))
{
    $genreValue = getGenreValue($_GET['genre']);
    if ($genreValue !== null)
    {
        $movieFilters['genre'] = $genreValue;
        $needFiltering = true;
    }
    else
    {
        $movies = [];
    }
}

if (isset($_GET['title']))
{
    $movieFilters['title'] = $_GET['title'];
    $needFiltering = true;
}

if ($needFiltering)
{
    $movies = filterMovies($movies, $movieFilters);
}


echo view('layout', [
    'lang' => option('APP_LANG', 'ru'),
    'title' => option('APP_NAME', 'BITFLIX'),
    'leftMenu' => require_once __DIR__ . '/components/menu.php',
    'content' => view('pages/index' ,[
        'movies' => $movies,
    ]),
]);