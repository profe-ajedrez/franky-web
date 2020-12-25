<?php declare(strict_types = 1);

namespace jotaa\franky_web\models\personas\roles;

use jotaa\franky_web\models\personas\personas\Users;
use jotaa\franky_web\models\FrankyRecord;
use jotaa\franky_web\interfaces\model_interfaces\FrankyModelInterface;

/**
 * Roles
 *
 * @author Javier Campos
 * @author Andres Reyes
 * @version 1.0
 * @copyright 2020 Jota A Diseño y Desarrollo
 * @package solylago
 */
final class Roles extends FrankyRecord implements FrankyModelInterface
{
    public const USUARIE = 1;
    public const FUNCIONARIE = 2;
    public const AGENCIA = 3;
    public const ADMIN = 4;
    public const JOTAA = 5;

    public function isAdmin()
    {
        return $this->id === self::ADMIN || $this->isJotaa();
    }

    public function isUser()
    {
        return $this->id === self::USUARIE;
    }

    public function isFuncionario()
    {
        return $this->id === self::FUNCIONARIE;
    }

    public function isAgencia()
    {
        return $this->id === self::AGENCIA;
    }

    public function isJotaa()
    {
        return $this->id === self::JOTAA;
    }


    public function privilegesInRole()
    {
        return $this->hasMany('app\models\personas\roles\RolesHasPrivileges', 'roles_id');
    }

    public static function fromArray(array $dataArray) : array
    {
        $v = new \Valitron\Validator($dataArray);
        $v->rule('optional', 'id')
          ->rule('integer', 'id')
          ->rule('required', 'name')
          ->rule('optional', 'description')
          ->rule('optional', 'settings');

        if (!$v->validate()) {
            return [
                'success' => false,
                'errors' => $v->errors(),
                'role' => null
            ];
        }

        list($id, $name, $description) = self::sanitizeArray($dataArray);

        $role = Roles::findById($id);
        if (!isset($role->id)) {
            $role = new Roles([
                'name' => $name,
                'description' => $description,
                //'settings' => $settings
            ]);
        } else {
            $role->name = $name;
            $role->description = $description;
            //$role->settings = $settings;
        }


        return [
            'success' => true,
            'errors' => [],
            'role' => $role
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
        $raw[] = htmlentities($dataArray['description'], ENT_QUOTES, 'UTF-8', false);
        //$raw[] = json_encode($dataArray['settings']);

        return $raw;
    }

    public static function getEmptRaw(): array
    {
        return [
            'name' => '',
            'description' => '',
            'settings' => ''
        ];
    }


    public function users()
    {
        return Users::findAll([
            'roles_id' => intval($this->id)
        ]);
    }
}
