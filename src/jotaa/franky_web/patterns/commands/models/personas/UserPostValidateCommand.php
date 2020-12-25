<?php declare(strict_types = 1);

namespace jotaa\franky_web\patterns\command\models;

use jotaa\franky_web\patterns\command\personas\AbstractModelCommand;
use jotaa\franky_web\interfaces\patterns\command\CommandInterface;
use app\helpers\personas\SignUpHelper;
use \WeakReference;

final class UserPostValidateCommand extends AbstractModelCommand implements CommandInterface
{
    public function do() : void
    {
        $this->status = self::DOING;
        $body = $this->context['body'];
        $signHelper = new SignUpHelper();
        $result = $signHelper->validate($body);
        if ($result['success']) {
            $this->status = self::SUCCESS;
            return;
        }

        $result = [
            'result' => $result,
            'body'   => $body,
        ];
        $this->context = null;
        $this->context = $result;
        $this->status = self::ERROR;
    }

    public function undo() : void
    {
        $this->status = self::DOING;
        $this->status = self::SUCCESS;
        /** @todo */
    }
}
