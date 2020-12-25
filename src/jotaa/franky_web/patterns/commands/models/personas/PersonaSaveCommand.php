<?php declare(strict_types = 1);

namespace jotaa\franky_web\patterns\command\models;

use jotaa\franky_web\patterns\command\personas\AbstractModelCommand;
use jotaa\franky_web\interfaces\patterns\command\CommandInterface;

final class PersonaSaveCommand extends AbstractModelCommand implements CommandInterface
{

    public function do() : void
    {
        $this->status = self::DOING;
    }

    public function undo() : void
    {
        $this->status = self::DOING;
        /** @todo */
    }
}
