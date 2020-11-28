<?php declare(strict_types = 1);

namespace jotaa\franky_web\controllers;

use jotaa\franky_web\renderers\CoreView;
use WeakReference;

abstract class HttpController
{
    public const DS = DIRECTORY_SEPARATOR;

    protected WeakReference $weakFranky;
    protected CoreView $renderEngine;

    public function __construct(\jotaa\core\FrankyCore $f)
    {
        $this->weakFranky   = WeakReference::create($f);
        $this->renderEngine = new CoreView($f);
    }

    protected function franky()
    {
        return $this->weakFranky->get();
    }
}
