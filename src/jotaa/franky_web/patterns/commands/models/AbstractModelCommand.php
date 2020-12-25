<?php declare(strict_types = 1);

namespace jotaa\franky_web\patterns\command\models;

use jotaa\franky_web\models\FrankyRecord;
use jotaa\franky_web\interfaces\patterns\command\CommandInterface;
use \WeakReference;

/**
 * AbstractModelCommand
 *
 * Abstract class which encapsulates common code for the model commands
 * `CommandInterface` implementing classes which use a `WeakReference`  as
 * context should extend this class.
 *
 * The context is stored as a `WeakReference`
 */
abstract class AbstractModelCommand implements CommandInterface
{
    protected static int $lastId = 0;

    protected int $status;
    protected int $id;
    protected array $context;

    protected static function generateId() : int
    {
        self::$lastId += 1;
        return self::$lastId;
    }

    public function __construct(array $context)
    {
        $this->context = $context;
        $this->id = self::generateId();
        $this->status = self::INIT;
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getStatus() : int
    {
        return $this->status;
    }

    public function getContext() : array
    {
        return $this->context;
    }
}
