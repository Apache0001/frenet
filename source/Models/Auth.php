<?php

namespace Source\Models;

use Source\Core\Model;

class Auth extends Model
{
    public function __construct()
    {
        parent::__construct('users', [''], ['']);
    }

    /**
     * attempt
     *
     * @param string $email
     * @param string $password
     * @return null|User
     */
    public function attempt(string $email, string $password): ?User
    {
        $user = (new User())->findByEmail($email);

        if(!$user){
            $this->message->error('O e-mail informado não é válido');
            return null;
        }

        if(!passwd_verify($password, $user->password)){
            $this->message->error('A senha informada não confere');
            return null;
        }

        return $user;
    }
}