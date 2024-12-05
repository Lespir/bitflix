<?php

require_once __DIR__ . "/../boot.php";

try
{
	$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
	$limit = 20;
	$offset = ($page - 1) * $limit;

	$genre = $_GET['genre'] ?? null;
	$genreFilter = $genre;

	if (isset($_GET['titleInput']) && trim($_GET['titleInput']) !== '')
	{
		$movies = getMoviesByTitle($_GET['titleInput']);
		$totalPages = null;
		var_dump($_GET['titleInput']);
	}
	else
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
}
catch (Exception $e)
{
	echo view('layout', [
		'lang' => option('APP_LANG', 'ru'),
		'title' => option('APP_NAME', 'BITFLIX'),
		'content' => "Something went wrong",
	]);
}
