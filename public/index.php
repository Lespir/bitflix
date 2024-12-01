<?php

require_once __DIR__ . "/../boot.php";

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

$genre = $_GET['genre'] ?? null;
$genreFilter = $genre;

if (isset($_GET['titleInput']))
{
	$movies = getMoviesByTitle(mb_strtolower($_GET['titleInput']));
	$totalPages = null;
} else
{
	$movies = getMoviesByGenre($genre, $limit, $offset);
	$totalPages = getNumberOfMovies($genre, $limit);
}

echo view('layout', [
    'lang' => option('APP_LANG', 'ru'),
    'title' => option('APP_NAME', 'BITFLIX'),
    'leftMenu' => require_once __DIR__ . '/components/menu.php',
    'content' => view('pages/index' ,[
        'movies' => $movies,
		'totalPages' => $totalPages,
		'genreFilter' => $genreFilter,
    ]),
]);