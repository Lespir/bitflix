<?php

require_once __DIR__ . "/../boot.php";

$movieId = null;

if (isset($_GET['movie-id']))
{
    $movieId = (int)$_GET['movie-id'];
}

$movie = getMovies($movieId);

echo view('layout', [
    'lang' => option('APP_LANG', 'ru'),
    'title' => option('APP_NAME', 'BITFLIX'),
    'leftMenu' => require_once ROOT . '/menu.php',
    'content' => view('pages/detail' ,[
        'movie' => $movie,
    ]),
]);