<?php
/**
 * @var array $movie
 */
$rating = $movie['rating'];
$activeSquares = floor($rating);
$totalSquares = 10;

?>
<div class="movie-detail">
    <?php if (empty($movie)): ?>
        <h2>No movies found</h2>
    <?php else: ?>
        <div class="detail-header">
            <div class="detail-titles">
                <h1 class="movie-detail-title"><?= sprintf("%s (%s)", $movie['title'], $movie['release-date'] )?></h1>
                <p class="movie-detail-translate"><?= $movie['original-title'] ?> <span class="age-rating"><?= $movie['age-restriction'] ?>+</span> </p>
            </div>
            <div class="heart-box">
                <input type="checkbox" id="heart-toggle" hidden>
                <label for="heart-toggle" class="heart-icon"></label>
                <!--                        <img src="./img/heart-empty.png" alt="" class="detail-heart">-->
            </div>
        </div>
        <hr>
        <div class="detail-main">
            <img src="<?= sprintf("/images/movie-posters/%s.jpg", $movie['id']) ?>" alt="<?= $movie['original-title'] ?> (image)">
            <div class="detail-content">
                <div class="rating">
                    <div class="squares">
                        <?php for ($i = 0; $i < $totalSquares; $i++): ?>
                            <?php if ($i < $activeSquares): ?>
                                <div class="square active-rating"></div>
                            <?php else: ?>
                                <div class="square"></div>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                    <div class="score"><?= $rating ?></div>
                </div>
                <div class="meta">
                    <h2 class="meta-title">О фильме</h2>
                    <div class="meta-p"><span>Год производства:</span> <div class="meta-inf"><?= $movie['release-date'] ?></div></div>
                    <div class="meta-p"><span>Режиссеры:</span>
                        <div class="meta-inf">
                            <?= $movie['director'] ?>
                        </div>
                    </div>
                    <div class="meta-p"><span>В главных ролях:</span>
                        <div class="meta-inf">
                            <?= implode(', ', $movie['cast']) ?>
                        </div>
                    </div>
                </div>
                <div class="description">
                    <h2 class="meta-title">Описание</h2>
                    <span class="meta-description"><?= $movie['description'] ?></span>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
