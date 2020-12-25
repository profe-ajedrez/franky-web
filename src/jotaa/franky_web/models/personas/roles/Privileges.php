<?php declare(strict_types = 1);

namespace jotaa\franky_web\models\personas\roles;

use jotaa\franky_web\models\FrankyRecord;
use jotaa\franky_web\interfaces\model_interfaces\FrankyModelInterface;

/**
 * Privileges
 *
 * @author Javier Campos
 * @version 1.0
 * @copyright 2020 Jota A Diseño y Desarrollo
 * @package solylago
 */
final class Privileges extends FrankyRecord implements FrankyModelInterface
{

    public function rolesInPriviege()
    {
        return $this->hasMany('app\models\personas\roles\RolesHasPrivileges', 'privileges_id');
    }

    public static function fromArray(array $dataArray): array
    {
        $v = new \Valitron\Validator($dataArray);
        $v->rule('optional', 'id')
          ->rule('integer', 'id')
          ->rule('required', 'name')
          ->rule('optional', 'settings');

        if (!$v->validate()) {
            return [
                'success' => false,
                'errors' => $v->errors(),
                'privilege' => null
            ];
        }

        list($id, $name, $settings) = self::sanitizeArray($dataArray);

        $privilege = Privileges::findById($id);
        if (!(bool) $privilege) {
            $privilege = new Privileges([
                'id' => $id,
                'name' => $name,
                'settings' => $settings
            ]);
        } else {
            $privilege->name = $name;
            $privilege->settings = $settings;
        }


        return [
            'success' => true,
            'errors' => [],
            'privilege' => $privilege
        ];
    }

    public static function sanitizeArray(array $dataArray): array
    {
        $raw = [];
        if (array_key_exists('id', $dataArray) && intval($dataArray['id']) !== 0) {
            $raw[] = intval($dataArray['id']);
        } else {
            $raw[] = null;
        }
        $raw[] = htmlentities($dataArray['name'], ENT_QUOTES, 'UTF-8', false);
        $raw[] = json_encode($dataArray['settings']);

        return $raw;
    }

    public static function getEmptRaw(): array
    {
        return [
            'name' => '',
            'settings' => ''
        ];
    }
}
