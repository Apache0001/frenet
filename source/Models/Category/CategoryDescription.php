<?php

namespace Source\Models\Category;

use Source\Core\Model;

class CategoryDescription extends Model
{
    public function __construct()
    {
        parent::__construct("category_description",['category_id','date_added', 'date_modified'],[]);
    }
    
    /**
     * getCategoryDescription
     *
     * @param  mixed $id
     * @return mixed
     */
    public function getCategoryDescription(int $id)
    {
        $find = $this->find("category_id=:id","id={$id}");

        return $find->fetch();
    }

}