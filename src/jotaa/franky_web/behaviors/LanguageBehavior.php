<?php declare(strict_types = 1);

namespace jotaa\franky_web\behaviors;

use jotaa\core\core_interfaces\CoreBehaviorInterface;
use jotaa\core\FrankyCore;
use jotaa\franky_web\helpers\LanguageHelper;
use WeakReference;

class LanguageBehavior implements CoreBehaviorInterface
{
    private const KEY = 0;

    private LanguageHelper $langEngine;
    private WeakReference $owner;

    public function __construct(LanguageHelper $engine, FrankyCore $franky)
    {
        $this->langEngine = $engine;
        $this->owner = WeakReference::create($franky);
    }

    public function run(array $parameters = ['whitespace'])
    {
        return $this->langEngine->l($parameters[self::KEY]);
    }

    public function getOwnerReference()
    {
        return $this->owner->get();
    }

    public function getBehaviorName() : string
    {
        return __CLASS__;
    }
}
