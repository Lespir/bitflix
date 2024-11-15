<?php

require_once __DIR__ . "/../boot.php";

echo view('layout', [
    'lang' => option('APP_LANG', 'ru'),
    'title' => option('APP_NAME', 'BITFLIX'),
    'leftMenu' => require_once ROOT . '/menu.php',
    'content' => view('pages/add-movie' ,[
        'text' => '!!!Add Movie Page!!!',
    ]),
]);