<?php

namespace Source\Models;

use Source\Core\Model;
use Source\Models\Post;

use Source\Core\Connect;

class User extends Model
{

    public function __construct()
    {
        parent::__construct("users", ["id"], ["first_name", "last_name", "email", "password"]);
    }

    /**
     * findByEmail
     *
     * @param string $email
     */
    public function findByEmail(string $email)
    {
        $find = $this->read("SELECT * FROM users WHERE email=:email", "email={$email}");
        return $find->fetchObject(__CLASS__);
    }

    /**
     * userPost
     *
     * @param integer $id
     * @return 
     */
    public function userPost(int $id)
    {
      $findUser = $this->findById($id);

      $posts = (new Post())->read("SELECT * FROM posts WHERE author = :author", "author={$findUser->id}")->fetchAll(\PDO::FETCH_CLASS, 'Source\Models\Post');

      $arrayPosts = [];

      foreach($posts as $post){
         $arrayPosts[] = $post;
      }

      $findUser->posts = $arrayPosts;

     return $findUser;
    }
    
}
