<?php

require __DIR__."/vendor/autoload.php";

use Source\Core\Http\Request;
use Source\Models\Order\Order;

$request = new Request();

$headers = [
    "x-partner-token" => "",
    "token" => "",
    "content-type" => "application/json",
    "accept" => "application/json"
];

$url =  "https://whitelabel-hml.frenet.dev/v1/orders";

//busca pedidos na frenet
$request->headers($headers)->get($url);

$ordersFrenet = $request->response->shipments;

$ordersFrenetId = [];


foreach($ordersFrenet as $key){
    //guarda o id dos pedidos da frenet em um array
    $ordersFrenetId[] = $key->orderId;
}

//busca no modelo ORDER os pedidos no BD
$orders = (new Order())->getOrders();

/* var_dump($orders);
exit; */

foreach($orders as $order){
    $arrayOrder = [];
    $arrayProduct = [];
    //compara o id dos pedidos do BD com os ids dos pedidos da frenet
    if(!in_array($order->order_id, $ordersFrenetId)){

        //array de produtos que estão no pedido
        foreach($order->order_product as $product){
            $arrayProduct[] = [
                "OrderId" => $order->order_id,
                "ItemId" => $product->product_id,
                "ProductId" => $product->product_id,
                "ProductType" => "",
                "Weight" => $product->weight ,
                "Length" => $product->length ?? null,
                "Width" => $product->width ?? null,
                "Quantity" => $product->quantity ?? null,
                "Price" => $product->price ?? null,
                "ProductName" => $product->model ?? null,
                "SKU" => $product->sku ?? null,
                
            ];
        }

        //montar array para envio
        $arrayOrder = [
            "ShipmentId" => "0",

            "Order" => [
                "Id" => $order->order_id,
                "Value" => 0,
                "To" => [
                    "Email" => $order->email,
                    "Name" => $order->firstname." ".$order->lastname,
                    "Phone" => $order->telephone
                ],
                "Items" => $arrayProduct
            ]
        ];


        //testar array
      /*   echo (json_encode($arrayOrder));
        exit; */
        
        //envia os pedidos cujo ids não estão na frenet

       /*  var_dump($arrayOrder);
        exit; */

        $Requestpost = new Request();
        $Requestpost->headers($headers)
                    ->post($url, $arrayOrder);

        var_dump($Requestpost->response());
 
    }
}










    