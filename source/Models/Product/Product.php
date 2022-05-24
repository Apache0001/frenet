<?php

namespace Source\Models\Product;

use Source\Core\Model;
use Source\Core\Connect;
use Source\Models\Product\ProductDescription;
use Source\Models\Seo\Seo;

class Product extends Model
{
    public function __construct()
    {
        parent::__construct('product', [], []);
    }


    /**
     * getProductId
     *
     * @param [type] $id
     * @return void
     */
    public function getProductId($id)
    {
        $find = $this->find("order_id = :id", "id={$id}");
        $array = [];
        $productOrders = $find->fetch(true);
        
        if(!empty($productOrders)){

            foreach($productOrders as $productOrder){
                $array[] = $productOrder->data();
            }
        }
        
        return $array;
    }

       /**
     * getHistoryOrderId
     *
     * @param  mixed $id
     * 
     */
    public function getProductOrderId($id)
    {
        $find = $this->find("order_id = :id", "id={$id}");
        $array = [];
        $productOrders = $find->fetch(true);
        
        if(!empty($productOrders)){

            foreach($productOrders as $productOrder){
                //$Product = new Product();
               /*  echo json_encode((object)$descriptionProduct->getDescriptionProduct($productOrder->data()->product_id)->data());
                exit; */
                //$productOrder->description = $Product->getDescriptionProduct($productOrder->data()->product_id)->data() ?? null;
                
                $array[] = $productOrder->data();
            }
        }
        
        return $array;
    }
    

    /**
     * getProductToCategory
    *
    * @param  mixed $id
    */
    public function getProductToCategory($id)
    {
        return (new ProductToCategory())->getProductToCategory($id);
    }
    
   
    
     




}