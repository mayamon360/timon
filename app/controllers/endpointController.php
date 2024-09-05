<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

class endpointController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {
        parent::__construct($router);

        \Stripe\Stripe::setApiKey('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');
        
        $payload = @file_get_contents('php://input');
        $event = null;
        
        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch(\UnexpectedValueException $e) {
            http_response_code(400);
            exit();
        }
        
        switch ($event->type) {
            case 'payment_intent.requires_action':
                $paymentIntent = $event->data->object;
                (new Model\EndPoint)->requiresAction($paymentIntent);
                break;
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                (new Model\EndPoint)->succeeded($paymentIntent);
                break;
            case 'charge.refunded':
                $charge = $event->data->object;
                (new Model\EndPoint)->refunded($charge);
                break;
            default:
                echo 'Received unknown event type ' . $event->type;
        }
        
        http_response_code(200);
    }
}