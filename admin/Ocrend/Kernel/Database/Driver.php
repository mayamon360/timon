<?php

namespace Ocrend\Kernel\Database;

/**
 * Interfaz para los drivers
 * 
 */
interface Driver {
    public function __construct();
    public function scape($param) : string;
    public function select(string $fields, string $table, $inners = null, $where = null, $limit = null, string $extra = '');
    public function update(string $table, array $e, $where = null, $limit = null) : int;
    public function insert(string $table, array $e) : int;
    public function delete(string $table, $where = null, $limit = null) : int;
    public function __destruct();
}