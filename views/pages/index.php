<?php
/**
 * @var array $movies
 */
?>

<div class="movie-grid">
    <?php if (empty($movies)): ?>
        <h2>No movies found</h2>
    <?php endif; ?>

    <?php foreach ($movies as $movie): ?>
        <?= view('components/movie', [
                'movie' => $movie,
        ]) ?>
    <?php endforeach; ?>
</div>
