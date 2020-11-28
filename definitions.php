<?php declare(strict_types = 1);

define('DS', DIRECTORY_SEPARATOR);
define('ENV_PATH', __DIR__ . DS . 'protected' . DS .  DS . 'env');

define('ROOT_PATH', __DIR__);

define('VIEWS_PATH', __DIR__ . DS . 'views');
define('ASSETS_PATH', __DIR__ . DS . 'public' .DS . 'assets');
define('CSS_PATH', ASSETS_PATH . DS . 'css');
define('IMG_PATH', ASSETS_PATH . DS . 'img');
define('JS_PATH', ASSETS_PATH . DS . 'js');
define('LOG_PATH', __DIR__ . DS . 'protected' . DS . 'runtime' . DS . 'log' . DS . 'application.log');
define(
    'UPLOADS_PATH',
    ASSETS_PATH . DS . 'protected' . DS . 'runtime' . DS . 'uploads'
);

define(
    'LANGUAGE_DEFINITIONS',
    __DIR__ . DS . 'protected' .DS . 'languages'
);
