<?php
/**
 * @var array $movie
 */
?>

<div class="movie-item">
    <img src="<?= sprintf("/images/movie-posters/%s.jpg", $movie['id']) ?>" alt="<?= $movie['original-title'] ?> (image)">
    <div class="movie-item-content">
        <h2 class="movie-item-title"><?= sprintf("%s (%s)", $movie['title'], $movie['release-date'] )?></h2>
        <p class="movie-item-translate"><?= $movie['original-title'] ?></p>
        <hr>
        <span class="movie-item-description"><?= $movie['description'] ?></span>
        <div class="movie-item-inf">
            <span>🕔 <?= $movie['duration'] ?> мин. / <?= minToHourMinutes((string)$movie['duration']) ?></span>
            <span class="genre"><?= implode(', ', $movie['genres']) ?></span>
        </div>
    </div>
    <div class="movie-item-hover">
        <a class="button" href="/detail.php/?movie-id=<?= $movie['id'] ?>">Подробнее</a>
    </div>
</div>
