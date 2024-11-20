<?php

function minToHourMinutes(string $time): string
{
    return date('H:i', $time * 60);
}