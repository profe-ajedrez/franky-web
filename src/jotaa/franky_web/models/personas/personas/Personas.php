<?php declare(strict_types = 1);

namespace jotaa\franky_web\models\personas\personas;

use jotaa\franky_web\models\FrankyRecord;
use jotaa\franky_web\interfaces\model_interfaces\FrankyModelInterface;

/**
 * Personas
 *
 * @author Andrés Reyes
 * @version 1.0
 * @copyright 2020 Jota A Diseño y Desarrollo
 * @package solylago
 */
final class Personas extends FrankyRecord implements FrankyModelInterface
{
    public function users()
    {
        return $this->hasOne('app\models\personas\personas\Users', 'personas_id');
    }

    public function nationalIdentificacion()
    {
        return $this->hasOne('app\models\personas\nationalIdentificacion\NationalIdentificacion', 'personas_id');
    }

    public static function fromArray(array $dataArray) : array
    {
        $v = new \Valitron\Validator($dataArray);
        $v->rule('optional', 'id')
          ->rule('integer', 'id')
          ->rule('required', 'names')
          ->rule('required', 'pat_surname')
          ->rule('optional', 'mat_surname');

        if (!$v->validate()) {
            return [
                'success' => false,
                'errors'  => $v->errors(),
                'persona' => null
            ];
        }

        list($id, $names, $pat_surname, $mat_surname) = self::sanitizeArray($dataArray);

        $persona = Personas::findById($id);
        if (!(bool) $persona) {
            $persona = new Personas([
                'id' => $id,
                'names' => $names,
                'pat_surname' => $pat_surname,
                'mat_surname' => $mat_surname
            ]);
        } else {
            $persona->names = $names;
            $persona->pat_surname = $pat_surname;
            $persona->mat_surname = $mat_surname;
        }

        return [
            'success' => true,
            'errors'  => [],
            'persona' => $persona
        ];
    }

    public static function sanitizeArray(array $dataArray) : array
    {
        $raw = [];
        if (array_key_exists('id', $dataArray) && intval($dataArray['id']) !== 0) {
            $raw[] = intval($dataArray['id']);
        } else {
            $raw[] = null;
        }
        $raw[] = htmlentities($dataArray['names'], ENT_QUOTES, 'UTF-8', false);
        $raw[] = htmlentities($dataArray['pat_surname'], ENT_QUOTES, 'UTF-8', false);
        $raw[] = htmlentities($dataArray['mat_surname'], ENT_QUOTES, 'UTF-8', false);
        return $raw;
    }

    public static function getEmptRaw() : array
    {
        return [
            'names'       => '',
            'pat_surname' => '',
            'mat_surname' => '',
        ];
    }
}
