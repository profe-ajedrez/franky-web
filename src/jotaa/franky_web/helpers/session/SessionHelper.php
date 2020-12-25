<?php

namespace jotaa\franky_web\helpers\session;

use app\interfaces\session\SigninAbleInterface;
use app\models\personas\personas\Users;

class SessionHelper implements SigninAbleInterface
{
    const MAX_SESSION_TIME = 1200;
    const USER_LOGIN = '__JOTAA___USER_LOGIN_';
    const SESSION_ID = '__JOTAA__SESSION_ID_';
    const LOGGED     = '__JOTAA__LOGGED_';
    const TIME_START = '__JOTAA__TIME_START_';

    const MAX_SECONDS_TO_COMPLETE_BOOKIN = 660;

    public static function isSessionStarted()
    {
        if (php_sapi_name() !== 'cli') {
            if (version_compare(phpversion(), '5.4.0', '>=')) {
                return session_status() === PHP_SESSION_ACTIVE && isset($_SESSION);
            } else {
                return session_id() !== '' && isset($_SESSION) &&
                    is_array($_SESSION);
            }
        }
        return false;
    }

    public static function closeSession(string $urlOnCloseSession = '') : void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            if (!empty(session_id())) {
                setcookie(session_id(), "", time() - 3600);
            }
            if (isset($_SESSION)) {
                unset($_SESSION[self::USER_LOGIN]);
                unset($_SESSION[self::SESSION_ID]);
            }
            session_destroy();
            session_write_close();
        }

        if (!empty($urlOnCloseSession)) {
            header("Location: {$urlOnCloseSession}");
            exit();
        }
    }

    public static function validateSession()
    {
        if (!self::isSessionStarted()) {
            session_start();
        }

        if (array_key_exists(self::LOGGED, $_SESSION) && $_SESSION[self::LOGGED]) {
            $ellapsedLogginTime = intval(time()) - intval($_SESSION[self::TIME_START]);
            if ($ellapsedLogginTime > self::MAX_SESSION_TIME) {
                self::closeSession('/user/login');
                return;
            }
            session_regenerate_id();
            $_SESSION[self::LOGGED] = true;
            $_SESSION[self::SESSION_ID] = session_id();
            $_SESSION[self::TIME_START] = time();
        }
    }

    public static function startSession(int $idUser) : void
    {
        $_SESSION[self::LOGGED] = true;
        $_SESSION[self::USER_LOGIN] = $idUser;
        $_SESSION[self::SESSION_ID] = session_id();
        $_SESSION[self::TIME_START] = time();
    }


    public static function isSignedInUser()
    {
        return (self::isSessionStarted() &&
            array_key_exists(self::LOGGED, $_SESSION) &&
            $_SESSION[self::LOGGED] &&
            array_key_exists(self::USER_LOGIN, $_SESSION) &&
            $_SESSION[self::SESSION_ID] == session_id());
    }


    public static function getUser()
    {
        $id = intval($_SESSION[self::USER_LOGIN]);
        $user = Users::findById($id);
        if (isset($user->id)) {
            return $user;
        }
        return false;
    }


    public static function isSignedAdmin()
    {
        if (self::isSignedInUser()) {
            $user = self::getUser();
            if ((bool) $user) {
                return $user->roles()->isAdmin();
            }
            self::closeSession();
        }
        return false;
    }
}
