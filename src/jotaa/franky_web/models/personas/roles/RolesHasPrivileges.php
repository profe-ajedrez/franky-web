<?php declare(strict_types = 1);

namespace jotaa\franky_web\models\personas\roles;

use jotaa\franky_web\models\FrankyRecord;
use jotaa\franky_web\interfaces\model_interfaces\FrankyModelInterface;

/**
 * RolesHasPrivileges
 *
 * Sirve para indicar que privilegios tendrán ciertos roles.
 *
 * @author Javier Campos
 * @version 1.0
 * @copyright 2020 Jota A Diseño y Desarrollo
 * @package solylago
 */
final class RolesHasPrivileges extends FrankyRecord implements FrankyModelInterface
{
    public static function fromArray(array $dataArray): array
    {
        $v = new \Valitron\Validator($dataArray);
        $v->rule('optional', 'id')
          ->rule('integer', 'id')
          ->rule('required', 'roles_id')
          ->rule('integer', 'roles_id')
          ->rule('required', 'prvileges_id')
          ->rule('integer', 'prvileges_id');

        if (!$v->validate()) {
            return [
                'success' => false,
                'errors' => $v->errors(),
                'rolHasPrivilege' => null
            ];
        }

        list($id, $roles_id, $prvileges_id) = self::sanitizeArray($dataArray);

        $rolHasPrivilege = new RolesHasPrivileges([
            'id' => $id,
            'roles_id' => $roles_id,
            'prvileges_id' => $prvileges_id
        ]);

        return [
            'success' => true,
            'errors' => [],
            'rolHasPrivilege' => $rolHasPrivilege
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

        $raw[] = intval($dataArray['roles_id']);
        $raw[] = intval($dataArray['prvileges_id']);

        return $raw;
    }

    public static function getEmptRaw(): array
    {
        return [
            'roles_id' => 0,
            'prvileges_id' => 0
        ];
    }
}
