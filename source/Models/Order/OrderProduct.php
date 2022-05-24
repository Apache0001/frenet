<?php

namespace Source\Models\Order;

use Source\Core\Model;
use Source\Models\Product\Product;

class OrderProduct extends Model
{
    public function __construct()
    {
        parent::__construct('order_product',[],[]);
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
                $Product = (new Product())->find("product_id =:id","id={$productOrder->product_id}")->fetch();
              
                
                $array[] = $Product;
            }
        }
        
        return $array;
    } 

  
    

   
}