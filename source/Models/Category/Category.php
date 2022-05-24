<?php

namespace Source\Models\Category;

use Source\Core\Model;

class Category extends Model
{
    public function __construct()
    {
        parent::__construct("category",['category_id','date_added', 'date_modified'],['image','parent_id','tp', 'column', 'sort_order', 'status']);
    }

    /**
     * getCategoryDescription
     *
     * @param  int $id
     * @return mixeed
     */
    public function getCategoryDescription(int $id)
    {
        return (new CategoryDescription())->getCategoryDescription($id);
    }

   
}