<?php

require_once __DIR__ . "/../boot.php";

try
{
	$movieId = $_GET['movie-id'] ?? null;
	if (trim($movieId) === '' || (int)$movieId < 0)
	{
		$movieId = null;
	}

	$movie = getMovieById($movieId);

	echo view('layout', [
		'lang' => option('APP_LANG', 'ru'),
		'title' => option('APP_NAME', 'BITFLIX'),
		'leftMenu' => require_once __DIR__ . '/components/menu.php',
		'content' => view('pages/detail' ,[
			'movie' => $movie,
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