<?php declare(strict_types = 1);

namespace jotaa\franky_web\interfaces\model_interfaces;

use jotaa\core\FrankyCore;
use Pop\Db\Record;

interface FrankyModelInterface
{
    /**
     * fromArray
     *
     * Returns a new Model object from the data contained in an array
     *
     * @param array $dataArray
     * @return array
     * @throws \jotaa\franky_web\exceptions\models\InsufficientDataToCreateModelException;
     */
    public static function fromArray(array $dataArray) : array;

    /**
     * getEmptRaw
     *
     * Returns an array with keys named after the model properties with empty values
     *
     * @return array
     */
    public static function getEmptRaw() : array;

    public static function sanitizeArray(array $dataArray) : array;
}
