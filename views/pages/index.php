<?php
/**
 * @var array $movies
 * @var int|null $totalPages
 * @var string|null $genreFilter
 */

$currentPage = $_GET['page'] ?? 1;
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
<div style="display: flex; justify-content: center; margin-top: 20px;">
    <?php if ($totalPages !== null): ?>
        <p style="margin-right: 20px;">Страницы:</p>
        <?php for ($page = 1; $page <= $totalPages; $page++): ?>
            <a style="margin: 0 10px; <?php if ((int)$currentPage === $page): ?>font-weight: bold; color: black;<?php endif;?>" href="
                ?page=<?= $page; ?><?php if ($genreFilter !== null): ?>&genre=<?= $genreFilter; ?><?php endif; ?>">
                <?= $page; ?>
            </a>
        <?php endfor; ?>
    <?php endif; ?>
</div>
