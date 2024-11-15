<?php

function toLowerCase(string $string): string
{
    // Приводим строку к нижнему регистру для латиницы
    $string = strtolower($string);

    // Заменяем заглавные кириллические буквы на строчные
    $cyrillicUpper = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ';
    $cyrillicLower = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюя';
    $string = strtr($string, $cyrillicUpper, $cyrillicLower);

    return $string;
}