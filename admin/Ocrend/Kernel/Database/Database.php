<?php

namespace Ocrend\Kernel\Database;
use  Ocrend\Kernel\Database\Drivers\Mysql\Mysql;
use  Ocrend\Kernel\Database\Drivers\Sqlite\Sqlite;

/**
 * Clase para conectar todos los modelos del sistema y compartir la configuración.
 * Inicializa elementos escenciales como la conexión con la base de datos.
 *
 */
class Database {
    
    /**
     * Resuelve el controlador de base de datos solicitado
     * 
     * @param string $motor: Motor a conectar
     * 
     * @return Driver
     */
    public static function resolveDriver(string $motor) : Driver {
        global $config;

        if($motor == 'mysql') {
            return new Mysql;
        } 

        return new Sqlite;
    }
}