<?php declare(strict_types=1);

namespace jotaa\franky_web\helpers\models\personas;

class Validate
{
    public function validatePersona(array $rawPersona) : array
    {
        $v = new \Valitron\Validator($rawPersona);
        $v->rule('required', 'names')
            ->rule('required', 'pat_surname')
            ->rule('optional', 'mat_surname')
            ->rule('optional', 'id')
            ->rule('integer', 'id');

        return $this->getResult($v);
    }

    public function validatePost(array $postData) : array
    {
        $v = new \Valitron\Validator($postData);
        $v->rule('required', 'persona')
            ->rule('required', 'user')
            ->rule('required', 'confirm_password');
        return $this->getResult($v);
    }


    public function validateUser(array $rawUser, bool $validatePassword = false) : array
    {
        $v = new \Valitron\Validator($rawUser);
        $v->rule('required', ['mail', 'password'])
            ->rule('email', 'mail')
            ->rule('optional', 'roles_id')
            ->rule('integer', 'roles_id')
            ->rule('optional', 'status')
            ->rule('integer', 'status')
            ->rule('optional', 'id')
            ->rule('integer', 'id');

        $shouldValidatePassword = (array_key_exists('password', $rawUser) && !empty($rawUser['password'])) ||
            $validatePassword;
        if ($shouldValidatePassword) {
            $v->rule('required', 'password')
            ->rule('required', 'confirm_password')
            ->rule('lengthMin', 'password', Users::MINIMAL_PASSWORD_LENGTH)
            ->rule('equals', 'password', 'confirm_password');
        }
        return $this->getResult($v);
    }


    private function getResult(\Valitron\Validator $v) : array
    {
        $success = $v->validate();
        $errors  = $v->errors();
        $errors  = (is_array($errors) ? implode(", \n <br>", $errors) : '');
        return [
            'success' => $success,
            'errors'  => $errors
        ];
    }
}
