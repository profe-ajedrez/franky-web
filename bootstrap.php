<?php declare(strict_types = 1);

require 'vendor/autoload.php';

use jotaa\core\core_interfaces\CoreBehaviorInterface;
use jotaa\core\FrankyCore;
use jotaa\franky_web\behaviors\LanguageBehavior;
use jotaa\franky_web\helpers\LanguageHelper;

require_once './definitions.php';

$dotenv = Dotenv\Dotenv::createImmutable(ENV_PATH, '.env');
$dotenv->load();

/* Here, the magic begins! */
$franky = new FrankyCore(
    [
        'database' => $_ENV['database'],
        'username' => $_ENV['username'],
        'password' => $_ENV['password'],
        'host'     => $_ENV['host'],
        'type'     => $_ENV['type']
    ],
    [
        'rootPath'  => ROOT_PATH,
        'viewPath'  => VIEWS_PATH,
        'assetPath' => ASSETS_PATH,
        'cssPath'   => CSS_PATH,
        'logPath'   => LOG_PATH,
        'languagePath' => LANGUAGE_DEFINITIONS,
        'spanish' => 'spanish.json',
        'english' => 'english.json',
        'portuguese' => 'portuguese.json',
        'japanese' => 'japanese.json',
        'currentLanguage' => 'spanish'
    ]
);

$langEngine = new LanguageHelper($franky->config('currentLanguage'), LANGUAGE_DEFINITIONS . DS .
$franky->config($franky->config('currentLanguage')));

$franky->attachBehavior(
    'validator',
    (new class($franky) implements CoreBehaviorInterface {
        private WeakReference $owner;

        public function __construct($franky)
        {
             $this->owner = WeakReference::create($franky);
        }

        public function run(array $parameters = [])
        {
            return new Valitron\Validator($parameters);
        }

        public function getOwnerReference()
        {
            return $this->owner;
        }

        public function getBehaviorName() : string
        {
            return 'saludar';
        }
    })
);

$franky->attachBehavior(
    'langEngine',
    new LanguageBehavior($langEngine, $franky)
);

$franky->router()->setBasePath('/franky-web');

require_once __DIR__ . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'route' .
    DIRECTORY_SEPARATOR . 'router_register.php';
