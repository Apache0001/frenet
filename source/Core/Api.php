<?php

namespace Source\Core;

use Source\Models\Auth;

class Api
{
    /** @var  */
    protected $user;

    /** @var */
    protected $headers;

    /** @var */
    protected $response;

    /**
     * construct
     */
    public function __construct()
    {
        header('Content-type: application/json; charset=UTF-8');
        $this->headers = getallheaders();

        if(!$this->auth()){
            exit;
        }
    }

    /**
     * call
     *
     * @param integer $code
     * @param string|null $type
     * @param string|null $message
     * @param string $rule
     * @return null|object
     */
    protected function call(int $code, string $type = null, string $message = null, string $rule = 'errors'): ?object
    {
        http_response_code($code);
        if(!empty($type)){
            $this->response = [
                $rule => [
                    "type" => $type,
                    "message" => (!empty($message) ? $message : null)
                ]
            ];
        }

        return $this;
    }

    /**
     * back
     *
     * @param array|null $response
     * @return object
     */
    protected function back(array $response = null): ?object
    {
        if(!empty($response)){
            $this->response = (!empty($this->response) ? array_merge($this->response, $response) : $response);
        }

        echo json_encode($this->response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return $this;
    }

    /**
     * auth
     *
     * @return boolean
     */
    protected function auth(): bool
    {
        if(empty($this->headers["email"]) || empty($this->headers["password"])){
            $this->call(
                400,
                "auth_empty",
                "Favor, informe seu e-mail e senha"
            )->back();

            return false;
        }

        $auth = new Auth();
        $user = $auth->attempt($this->headers["email"], $this->headers["password"]);

        if(!$user){
            $this->call(
                401,
                "invalid_auth",
                $auth->message()->getText()
            )->back();

            return false;
        }

        $this->user = $user;
        return true;

    }


}