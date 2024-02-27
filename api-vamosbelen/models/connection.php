<?php

class Connection
{
    static public function connect()
    {
        try {
            $con = new PDO("mysql:host=localhost;dbname=vamosbelen;", "root", "");
            $con->exec("set names utf8");

            return $con;
        } catch (PDOException $e) {
            die("Error: {$e -> getMessage()}");
        }
    }
}
