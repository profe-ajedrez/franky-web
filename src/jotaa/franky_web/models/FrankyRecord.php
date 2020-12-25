<?php declare(strict_types = 1);

namespace jotaa\franky_web\models;

use jotaa\franky_web\interfaces\model_interfaces\FrankyModelInterface;
use Pop\Db\Record;

abstract class FrankyRecord extends Record implements FrankyModelInterface
{

}
