<?php declare(strict_types = 1);

use app\controllers\home\HomeController;

/**
 * You can put your custom routes here
 */


$franky->router()->map(
    'GET',
    '/',
    function () use ($franky) {
        (new HomeController($franky))->index();
    }
);
