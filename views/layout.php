<?php
/**
 * @var string $lang
 * @var string $title
 * @var array $leftMenu
 * @var array $content
 */
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="/css/reset.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<main class="container">

    <header class="nav">
        <a href="/" class="logo-link">
            <img src="/images/logo.svg" width="180" alt="logo">
        </a>

        <?= view('components/menu', ['items' => $leftMenu]) ?>

    </header>
    <div class="right">
        <div class="header">
            <div class="search-block">
                <input type="text" id="titleInput" placeholder="&#128269 Поиск по каталогу...">
                <button type="submit" onclick="addTitleToUrl()" class="button">Искать</button>
            </div>
            <a class="button add-film-button" href="/add-movie.php">Добавить фильм</a>
        </div>
        <div class="content">

            <?= $content ?>

        </div>
    </div>
</main>
<script src="/js/script.js"></script>
</body>
</html>