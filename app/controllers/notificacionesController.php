<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

use MercadoPago;

/**
 * Controlador compra/
*/
class notificacionesController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {
        parent::__construct($router);
        
        global $http;
        
        $topic = $http->query->get('topic');
        $id = $http->query->get('id');
        
        MercadoPago\SDK::setAccessToken("TEST-5930145768018628-081120-71e776ebdf25eef84c125b019606eeaa-624492549");

        $merchant_order = null;
    
        switch($topic) {
            case "payment":
                $payment = MercadoPago\Payment::find_by_id($id);
                // Get the payment and the corresponding merchant_order reported by the IPN.
                $merchant_order = MercadoPago\MerchantOrder::find_by_id($payment->order->id);
                break;
            case "merchant_order":
                $merchant_order = MercadoPago\MerchantOrder::find_by_id($id);
                break;
        }
    
        $paid_amount = 0;
        foreach ($merchant_order->payments as $payment) {
            if ($payment['status'] == 'approved'){
                $paid_amount += $payment['transaction_amount'];
            }
        }
    
        // If the payment's transaction amount is equal (or bigger) than the merchant_order's amount you can release your items
        if($paid_amount >= $merchant_order->total_amount){
            if (count($merchant_order->shipments)>0) { // The merchant_order has shipments
                if($merchant_order->shipments[0]->status == "ready_to_ship") {
                    print_r("Totally paid. Print the label and release your item.");
                }
            } else { // The merchant_order don't has any shipments
                print_r("Totally paid. Release your item.");
            }
        } else {
            print_r("Not paid yet. Do not release your item.");
        }
        
        
    }
}