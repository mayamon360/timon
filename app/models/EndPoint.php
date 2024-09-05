<?php

namespace app\models;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Models\Models;
use Ocrend\Kernel\Models\IModels;
use Ocrend\Kernel\Models\ModelsException;
use Ocrend\Kernel\Models\Traits\DBModel;
use Ocrend\Kernel\Router\IRouter;

/**
 * Modelo EndPoint
 */
class EndPoint extends Models implements IModels {
    use DBModel;
    
    /**
     * Un confirmo un vale de OXXO
     *
     */
    public function requiresAction($paymentIntent) {
        
        $this->db->update('compras_stripe',array(
            'estatus' => $paymentIntent->status,
            'comprobante' => $paymentIntent->next_action["oxxo_display_details"]["hosted_voucher_url"],
            'expira' => $paymentIntent->next_action["oxxo_display_details"]["expires_after"]
        ),"id_stripe='$paymentIntent->id'",1);
        
    }
    
    public function succeeded($paymentIntent) {
        $this->db->update('compras_stripe',array(
            'estatus' => $paymentIntent->status
        ),"id_stripe='$paymentIntent->id'",1);
    }
    
    public function refunded($charge) {
        $this->db->update('compras_stripe',array(
            'monto_reembolso' => ($charge->amount_refunded / 100),
            'estatus' => 'refunded'
        ),"id_stripe='$charge->id'",1);
    }

    /**
     * __construct()
    */
    public function __construct(IRouter $router = null) {
        parent::__construct($router);
		$this->startDBConexion();
    }
}