<?php

namespace Ocrend\Kernel\Models;

/**
 * Excepción producida en un modelo, para controlar la salida del error desde la api/controller.
 *
 */

class ModelsException extends \Exception {

    /**
     * __construct()
     */
    public function __construct($message = null, $code = 1, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Muestra el error con un formato u otro dependiendo desde donde se hace la petición.
     */
    public function errorResponse() {
        throw new \RuntimeException($this->getMessage());
    }

}