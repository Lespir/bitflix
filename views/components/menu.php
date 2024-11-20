<?php
/**
 * @var array $items
 */

$currentPage = $_SERVER['REQUEST_URI'];
?>


<nav class="menu">
    <ul>
        <?php foreach ($items as $item): ?>
            <li class="menu-item">
                <a href="<?= $item['url'] ?>" class="menu-link <?= ($item['url'] === $currentPage) ? "active" : '' ?>">
                    <span><?= $item['text'] ?> </span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>