<?php

namespace Source\Models;

use Source\Core\Model;

class Post extends Model
{

    public function __construct()
    {
        parent::__construct('posts',[''],['']);
    }

      /**
     * findById
     *
     * @param integer $id
     * @return 
     */
    public function findById(int $id)
    {
        $find = $this->read("SELECT * FROM posts WHERE id=:id", "id={$id}");

        return $find->fetchObject(__CLASS__);
    }

}