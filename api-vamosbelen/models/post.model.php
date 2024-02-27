<?php

// use FTP\Connection;

require_once "connection.php";

class PostModel
{
    // Peticion para traer los nombres de columnas

    static public function get_columns_data($table, $database)
    {
        return Connection::connect()->query("SELECT COLUMN_NAME AS item FROM information_schema.columns 
        WHERE table_schema = '$database' AND table_name = '$table'")->fetchAll(PDO::FETCH_OBJ);
    }


    // Peticion POST para insertar datos

    static public function post_data($table, $data)
    {

        $columns = "(";
        $params = "(";

        foreach ($data as $key => $value) {
            $columns .= "`$key`,";
            $params .= ":$key,";
        }

        $columns = substr($columns, 0, -1);
        $params = substr($params, 0, -1);

        $columns .= ")";
        $params .=  ")";

        try {

            $link = Connection::connect();

            $stmt = $link->prepare("INSERT INTO `$table` $columns VALUES $params;");

            foreach ($data as $key => $value) {                
                $stmt->bindParam(":$key", $data[$key], PDO::PARAM_STR);
            }

            $stmt->execute();

            return [
                "lastId" => $link->lastInsertId(),
                "comment" => "Ok"
            ];

        } catch (PDOException $e) {
            return null;
        }
    }
}
