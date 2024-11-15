<?php

require_once __DIR__ . "/../boot.php";

$needFiltering = false;
$movieFilters = [
    'genre' => null,
    'title' => null,
];


if (isset($_GET['genre']))
{
    $genreValue = getGenreValue($_GET['genre']);
    $movieFilters['genre'] = !empty($genreValue) ? $genreValue : null;
    $needFiltering = true;
}

if (isset($_GET['title']))
{
    $movieFilters['title'] = $_GET['title'];
    $needFiltering = true;
}

$movies = getMovies();

if ($needFiltering)
{
    $movies = filterMovies($movies, $movieFilters);
}


echo view('layout', [
    'lang' => option('APP_LANG', 'ru'),
    'title' => option('APP_NAME', 'BITFLIX'),
    'leftMenu' => require_once ROOT . '/menu.php',
    'content' => view('pages/index' ,[
        'movies' => $movies,
    ]),
]);