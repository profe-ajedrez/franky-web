<?php declare(strict_types = 1);

namespace app\controllers\home;

use jotaa\franky_web\controllers\HttpController;

final class HomeController extends HttpController
{
    /**
     * index
     *
     * Serves the home of the app
     */
    public function index() : void
    {
        $this->renderEngine->renderView(
            'tests' . self::DS . 'test_view.php',
            [
                'franky' => $this->franky()
            ]
        );
    }
}
