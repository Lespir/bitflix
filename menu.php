<?php


require_once __DIR__ . "/repository.php";


$mainUrls = [];
$genreUrls = [];

$genres = getGenres();

$mainPages = option('APP_MAIN_PAGES', [
    ['url' => '/', 'text' => 'Главная'],
]);

foreach ($mainPages as $page)
{
    $mainUrls[] = $page;
}

if (!empty($genres))
{
    foreach ($genres as $genreKey => $genre)
    {
        $genreUrls[] = ['url' => '/?genre=' . $genreKey, 'text' => $genre];
    }
}


return array_merge($mainUrls, $genreUrls);