<?php

require_once "connection.php";

class PutModel
{

    // Peticion PUT para editar datos

    static public function put_data($table, $data, $id, $name_id)
    {
        $set = "";

        foreach ($data as $key => $value) {
            $set .= "`$key` = :$key,";
        }

        $set = substr($set, 0, -1);

        try {

            $stmt = Connection::connect()->prepare("UPDATE `$table` SET $set WHERE `$name_id`= :$name_id");

            foreach ($data as $key => $value) {
                $stmt->bindParam(":$key", $data[$key], PDO::PARAM_STR);
            }
            
            $stmt->bindParam(":$name_id", $id, PDO::PARAM_INT);

            $stmt->execute();

            return "OK";
        } catch (PDOException $e) {
            return null;
        }
    }
}
