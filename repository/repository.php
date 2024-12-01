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
			GROUP_CONCAT(DISTINCT g.NAME SEPARATOR ', ') AS genres
		FROM 
			movie m
		LEFT JOIN 
			movie_genre mg ON m.ID = mg.MOVIE_ID
		LEFT JOIN 
			genre g ON mg.GENRE_ID = g.ID
		WHERE 
			(? IS NULL OR m.ID IN (
				SELECT mg_sub.MOVIE_ID
				FROM movie_genre mg_sub
				JOIN genre g_sub ON mg_sub.GENRE_ID = g_sub.ID
				WHERE g_sub.CODE = ?
			))
		GROUP BY 
			m.ID
		LIMIT ? OFFSET ?;
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
		$movies[] = [
			'id' => $row['ID'],
			'title' => $row['TITLE'],
			'original-title' => $row['ORIGINAL_TITLE'],
			'description' => $row['DESCRIPTION'],
			'duration' => $row['DURATION'],
			'release-date' => $row['RELEASE_DATE'],
			'genres' => $row['genres'],
		];
	}

    return $movies;
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
            GROUP_CONCAT(DISTINCT g.NAME SEPARATOR ', ') AS genres
        FROM 
            movie m
        LEFT JOIN 
            movie_genre mg ON m.ID = mg.MOVIE_ID
        LEFT JOIN 
            genre g ON mg.GENRE_ID = g.ID
        WHERE 
            LOWER(m.TITLE) LIKE CONCAT(?, '%') 
            OR LOWER(m.ORIGINAL_TITLE) LIKE CONCAT(?, '%')
        GROUP BY 
            m.ID;
	";

	$statement = mysqli_prepare($connection, $query);
	mysqli_stmt_bind_param($statement, "ss", $title, $title);
	mysqli_stmt_execute($statement);
	$result = mysqli_stmt_get_result($statement);
	if (!$result)
	{
		throw new Exception(mysqli_error($connection));
	}

	$movies = [];
	while ($row = mysqli_fetch_assoc($result))
	{
		$movies[] = [
			'id' => $row['ID'],
			'title' => $row['TITLE'],
			'original-title' => $row['ORIGINAL_TITLE'],
			'description' => $row['DESCRIPTION'],
			'duration' => $row['DURATION'],
			'release-date' => $row['RELEASE_DATE'],
			'genres' => $row['genres'],
		];
	}

	return $movies;
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
			GROUP_CONCAT(DISTINCT a.NAME SEPARATOR ', ') AS actors
		FROM
			movie m
				LEFT JOIN
			director d ON m.DIRECTOR_ID = d.ID

				LEFT JOIN
			movie_actor ma ON m.ID = ma.MOVIE_ID
				LEFT JOIN
			actor a ON ma.ACTOR_ID = a.ID
		WHERE
			m.ID = ?
		GROUP BY 
		    m.ID;
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
	while ($row = mysqli_fetch_assoc($result))
	{
		$movie['id'] = $row['ID'];
		$movie['title'] = $row['TITLE'];
		$movie['original-title'] = $row['ORIGINAL_TITLE'];
		$movie['description'] = $row['DESCRIPTION'];
		$movie['duration'] = $row['DURATION'];
		$movie['age-restriction'] = $row['AGE_RESTRICTION'];
		$movie['release-date'] = $row['RELEASE_DATE'];
		$movie['rating'] = $row['RATING'];
		$movie['director'] = $row['director'];
		$movie['actors'] = $row['actors'];
	}

	return $movie;
}

function getNumberOfMovies(string $genre = null, int $limit = 20) : int
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