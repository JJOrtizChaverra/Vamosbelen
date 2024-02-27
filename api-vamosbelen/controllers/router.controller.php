<?php

class RoutesController {

    // Ruta principal
    public function index() {
        include "routes/router.php";
    }

    // Nombre de la base de datos
    static public function db() { 
        return "vamosbelen";
    }

    // Tablas protegidas
    static public function table_private() { 
     
        $tables = ["users", "disputes", "orders", "sales", "messages"];

        return $tables;

    }
}

?>