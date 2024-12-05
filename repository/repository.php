<?php

function getGenres(): array
{

	$connection = getDbConnect();

	$result = mysqli_query($connection, "
		SELECT CODE, NAME FROM genre
	");
	if (!$result)
	{
		throw new Exception(mysqli_error($connection));
	}

    $genres = [];
	while ($row = mysqli_fetch_assoc($result))
	{
		$genres[$row['CODE']] = $row['NAME'];
	}

    return $genres;
}

function getMoviesByGenre(?string $genre = null, int $limit = 20, int $offset = 0): array
{
	$connection = getDbConnect();

	$query = "
        SELECT
			m.ID,
			m.TITLE,
			m.ORIGINAL_TITLE,
			m.DESCRIPTION,
			m.DURATION,
			m.RELEASE_DATE,
			g.NAME AS genre
		FROM
			(SELECT m_sub.ID
			 FROM movie m_sub
			 WHERE (? IS NULL OR m_sub.ID IN (
				 SELECT mg_sub.MOVIE_ID
				 FROM movie_genre mg_sub
						  JOIN genre g_sub ON mg_sub.GENRE_ID = g_sub.ID
				 WHERE g_sub.CODE = ?
			 ))
			 ORDER BY m_sub.ID
			 LIMIT ? OFFSET ?) AS limited_movies
				JOIN movie m ON limited_movies.ID = m.ID
				LEFT JOIN movie_genre mg ON m.ID = mg.MOVIE_ID
				LEFT JOIN genre g ON mg.GENRE_ID = g.ID
		ORDER BY m.ID, g.NAME;
    ";

	$statement = mysqli_prepare($connection, $query);
	mysqli_stmt_bind_param($statement, "ssii", $genre, $genre, $limit, $offset);
	mysqli_stmt_execute($statement);
	$result = mysqli_stmt_get_result($statement);
	if (!$result)
	{
		throw new Exception(mysqli_error($connection));
	}

    $movies = [];
	while ($row = mysqli_fetch_assoc($result))
	{
		$movieId = $row['ID'];

		if (!isset($movies[$movieId])) {
			$movies[$movieId] = [
				'id' => $row['ID'],
				'title' => $row['TITLE'],
				'original-title' => $row['ORIGINAL_TITLE'],
				'description' => $row['DESCRIPTION'],
				'duration' => $row['DURATION'],
				'release-date' => $row['RELEASE_DATE'],
				'genres' => [],
			];
		}

		if (!empty($row['genre']) && !in_array($row['genre'], $movies[$movieId]['genres'])) {
			$movies[$movieId]['genres'][] = $row['genre'];
		}
	}

	return array_values($movies);
}

function getMoviesByTitle(string $title): array
{
	$connection = getDbConnect();

	$query = "
        SELECT 
            m.ID,
            m.TITLE,
            m.ORIGINAL_TITLE,
            m.DESCRIPTION,
            m.DURATION,
            m.RELEASE_DATE,
            g.NAME AS genre
        FROM 
            movie m
        LEFT JOIN 
            movie_genre mg ON m.ID = mg.MOVIE_ID
        LEFT JOIN 
            genre g ON mg.GENRE_ID = g.ID
        WHERE 
            LOWER(m.TITLE) LIKE ?
            OR LOWER(m.ORIGINAL_TITLE) LIKE ?
        ORDER BY 
            m.ID, g.NAME
	";

	$titleFilter = strtolower($title) . '%';
	$statement = mysqli_prepare($connection, $query);
	mysqli_stmt_bind_param($statement, "ss", $titleFilter, $titleFilter);
	mysqli_stmt_execute($statement);
	$result = mysqli_stmt_get_result($statement);
	if (!$result)
	{
		throw new Exception(mysqli_error($connection));
	}

	$movies = [];
	while ($row = mysqli_fetch_assoc($result))
	{
		$movieId = $row['ID'];

		if (!isset($movies[$movieId])) {
			$movies[$movieId] = [
				'id' => $row['ID'],
				'title' => $row['TITLE'],
				'original-title' => $row['ORIGINAL_TITLE'],
				'description' => $row['DESCRIPTION'],
				'duration' => $row['DURATION'],
				'release-date' => $row['RELEASE_DATE'],
				'genres' => [],
			];
		}

		if ($row['genre']) {
			$movies[$movieId]['genres'][] = $row['genre'];
		}
	}

	return array_values($movies);
}

function getMovieById(?int $movieId = null) : array
{
    if ($movieId === null)
	{
		return [];
	}

	$connection = getDbConnect();

	$query = "
		SELECT
            m.ID,
            m.TITLE,
            m.ORIGINAL_TITLE,
            m.DESCRIPTION,
            m.DURATION,
            m.AGE_RESTRICTION,
            m.RELEASE_DATE,
            m.RATING,
            d.NAME AS director,
            a.NAME AS actor
        FROM
            movie m
        LEFT JOIN
            director d ON m.DIRECTOR_ID = d.ID
        LEFT JOIN
            movie_actor ma ON m.ID = ma.MOVIE_ID
        LEFT JOIN
            actor a ON ma.ACTOR_ID = a.ID
        WHERE
            m.ID = ?;
    ";

	$statement = mysqli_prepare($connection, $query);
	mysqli_stmt_bind_param($statement, "i", $movieId);
	mysqli_stmt_execute($statement);
	$result = mysqli_stmt_get_result($statement);
	if (!$result)
	{
		throw new Exception(mysqli_error($connection));
	}


	$movie = [];
	$actors = [];
	while ($row = mysqli_fetch_assoc($result))
	{
		if (empty($movie))
		{
			$movie = [
				'id' => $row['ID'],
				'title' => $row['TITLE'],
				'original-title' => $row['ORIGINAL_TITLE'],
				'description' => $row['DESCRIPTION'],
				'duration' => $row['DURATION'],
				'age-restriction' => $row['AGE_RESTRICTION'],
				'release-date' => $row['RELEASE_DATE'],
				'rating' => $row['RATING'],
				'director' => $row['director'],
				'actors' => [],
			];
		}

		if (!empty($row['actor']) && !in_array($row['actor'], $actors)) {
			$actors[] = $row['actor'];
		}
	}

	if (!empty($movie)) {
		$movie['actors'] = $actors;
	}

	return $movie;
}

function getNumberOfMovies(?string $genre = null, int $limit = 20) : int
{
	$connection = getDbConnect();

	$query = "
		SELECT COUNT(DISTINCT m.ID) AS total
		FROM movie m
		LEFT JOIN movie_genre mg ON m.ID = mg.MOVIE_ID
		LEFT JOIN genre g ON mg.GENRE_ID = g.ID
		WHERE (? IS NULL OR g.CODE = ?);
	";

	$statement = mysqli_prepare($connection, $query);
	mysqli_stmt_bind_param($statement, "ss", $genre, $genre);
	mysqli_stmt_execute($statement);
	$result = mysqli_stmt_get_result($statement);
	$totalMovies = mysqli_fetch_assoc($result)['total'];

	return ceil($totalMovies / $limit);
}