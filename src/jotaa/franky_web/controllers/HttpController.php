<?php declare(strict_types = 1);

namespace jotaa\franky_web\controllers;

use jotaa\franky_web\helpers\http\Response;
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

    public function renderEngine() : CoreView
    {
        return $this->renderEngine;
    }

    public function sendJson(int $httpResponseCode, array $response, int $options = JSON_HEX_QUOT|JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR, bool $halt = true)
    {
        $response = new Response($httpResponseCode, $response, $this->franky()->log());
        $response->answerJson($options);
        if ($halt) {
            exit(0);
        }
    }
}
