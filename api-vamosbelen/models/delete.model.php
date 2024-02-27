<?php

require_once "connection.php";

class DeleteModel {
    static public function delete_model($table, $id, $name_id) {
        try {
            $stmt = Connection::connect() -> prepare("DELETE FROM `$table` WHERE `$name_id` = :$name_id");

            $stmt -> bindParam(":$name_id", $id, PDO::PARAM_INT);

            $stmt -> execute();

            return "OK";
        } catch (PDOException $e) {
            return null;
        }
    }

}

?>