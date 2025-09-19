<?php

// 完了ページの表示

declare(strict_types=1);

require_once __DIR__ . "/../app/initialize.php";

// 表示
echo $twig->render('fin_print.twig', []);
