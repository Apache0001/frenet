<?php

namespace Source\App\Api\User;

use Source\Core\Api;
use Source\Models\User;

class Users extends Api
{

    /**
     * 
     *
     * @return void
     */
    public function getUsers(): void
    {
         
        if($this->user->level < 5 ){
            $this->call(
                401,
                "permission_danied",
                "Você não tem permissão para acessar essa rota"
            )->back();
            return;
        }

        $users = (new User())->find()->fetch(true);

        if(!$users){
            $this->call(
                400,
                "data_not_found",
                "Nenhum registro foi encontrado"
            )->back();
            return;
        }

        $arrayUsers = [];

        foreach($users as $user){
            $arrayUsers[] = $user->data();
        }
 
        $this->back($arrayUsers);
    
        return;
    }

    


}