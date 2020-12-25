<?php

declare(strict_types=1);

namespace jotaa\franky_web\models\personas\personas;

use jotaa\franky_web\models\personas\roles\Roles;
use jotaa\franky_web\models\FrankyRecord;
use jotaa\franky_web\interfaces\model_interfaces\FrankyModelInterface;

/**
 * Users
 *
 * @author Andrés Reyes
 * @version 1.0
 * @copyright 2020 Jota A Diseño y Desarrollo
 * @package jotaa\franky_web
 */
final class Users extends FrankyRecord implements FrankyModelInterface
{
    /** STATUS_ACTIVE Indica que  usuarie puede usar los servicios de la app */
    public const STATUS_ACTIVE = 0;
    /** STATUS_BANNED Indica que  usuarie puede hacer login en la app, pero no usar sus otros servicios */
    public const STATUS_BANNED = 1;
    /** STATUS_DISABLED Indica que  usuarie No puede inbgresar a la aplicación */
    public const STATUS_DISABLED = 2;

    public const MINIMAL_PASSWORD_LENGTH = 8;

    public const DEFAULT_TIMEZOME = 'UTC';

    public const DUMMY_USER_ID = -666;

    /**
     * personas
     *
     * returns the Persona which this user model belongs
     *
     * @return Personas
     */
    public function personas(): Personas
    {
        return Personas::findById($this->persona_id);
    }

    public function roles(): Roles
    {
        $rol = Roles::findOne(['id' => $this->roles_id]);
        return $rol;
    }

    public static function fromArray(array $dataArray): array
    {

        $v = new \Valitron\Validator($dataArray);
        $v->rule('optional', 'id')
            ->rule('integer', 'id')
            ->rule('required', 'username')
            ->rule('lengthMin', 'username', 8)
            ->rule('slug', 'username')
            ->rule('required', 'password')
            ->rule('lengthMin', 'password', 8)
            ->rule('required', 'personas_id')
            ->rule('required', 'user_statuses_id')
            ->rule('required', 'roles_id')
            ->rule('optional', 'time_zone')
            ->rule('optional', 'phone');

        if (!$v->validate()) {
            return [
                'success' => false,
                'errors'  => $v->errors(),
                'user'    => null,
            ];
        }

        $dataArray = array_merge(self::getEmptRaw(), $dataArray);

        list($id, $username, $password, $personas_id, $user_statuses_id, $roles_id, $time_zone, $phone) =
            self::sanitizeArray($dataArray);

        $user = Users::findById($id);
        if (!(bool) $user) {
            $user = new Users(
                [
                    'id' => $id,
                    'username' => $username,
                    'password' => $password,
                    'personas_id' => $personas_id,
                    'user_statuses_id' => $user_statuses_id,
                    'roles_id' => $roles_id,
                    'time_zone' => $time_zone,
                    'phone' => $phone
                ]
            );
        } else {
            $user->username = $username;
            $user->password = $password;
            $user->personas_id = $personas_id;
            $user->user_statuses_id = $user_statuses_id;
            $user->roles_id = $roles_id;
            $user->time_zone = $time_zone;
            $user->phone = $phone;
        }

        return [
            'success' => true,
            'errors'  => [],
            'user' => $user
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
        $raw[] = htmlentities($dataArray['username'], ENT_QUOTES, 'UTF-8', false);
        $raw[] = $dataArray['password'];
        $raw[] = intval($dataArray['personas_id']);
        $raw[] = intval($dataArray['user_statuses_id']);
        $raw[] = intval($dataArray['roles_id']);
        $raw[] = $dataArray['time_zone'];
        $raw[] = htmlentities($dataArray['phone'], ENT_QUOTES, 'UTF-8', false);
        return $raw;
    }

    public static function getEmptRaw(): array
    {
        return [
            'username' => '',
            'password' => '',
            'personas_id' => 0,
            'user_statuses_id' => 0,
            'roles_id' => 0,
            'time_zone' => 'UTC',
            'phone' => '',
        ];
    }

    /**
     * userByMailOrPhone
     *
     * Busca $mailOrPhone como mail o phone en la tabla de usuarios
     *
     * @param string $mailOrPhone
     * @return Users
     */
    public static function userByMailOrPhone(string $mailOrPhone)
    {
        $userByMail  = Users::findOne(['mail'  => $mailOrPhone]);
        if (isset($userByMail->id)) {
            return $userByMail;
        }
        return Users::findOne(['phone' => $mailOrPhone]);
    }
}
