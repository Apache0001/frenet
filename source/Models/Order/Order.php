<?php

namespace Source\Models\Order;

use Source\Core\Model;


class Order extends Model
{
    public function __construct()
    {
        parent::__construct('order', [], []);
    }

  
    /**
     * getOrderProduct
     *
     * @param  mixed $id
     * @return void
     */
    public function getOrderProduct($id)
    {
        return (new OrderProduct())->getProductOrderId($id);
    }

      /**
       * getOrders
       *
       * @return 
       */
      public function getOrders()
      {
        $orders = $this->find()->limit(1)->offset(109)->fetch(true);

        foreach($orders as $order){
            //verifica se existe produto no pedido
                if($order->getOrderProduct($order->data()->order_id)){
                    //adiciona no campo order_product os produtos
                    $order->order_product = $order->getOrderProduct($order->data()->order_id);
                }
            }
            return $orders;
      }
}