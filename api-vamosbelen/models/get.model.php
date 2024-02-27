<?php

require_once "connection.php";

class GetModel
{

    // Peticiones GET sin filtro

    static public function get_data($table, $order_by, $order_mode, $start_at, $end_at, $select)
    {
        if ($order_by != null && $order_mode != null && $start_at == null && $end_at == null) {
            try {
                $stmt = Connection::connect()->prepare("SELECT $select FROM `$table` ORDER BY `$order_by` $order_mode;");
                $stmt->execute();
            } catch (PDOException $e) {
                return null;
            }
        } else if ($order_by != null && $order_mode != null && $start_at != null && $end_at != null) {
            try {
                $stmt = Connection::connect()->prepare("SELECT $select FROM `$table` ORDER BY `$order_by` $order_mode LIMIT $start_at, $end_at;");
                $stmt->execute();
            } catch (PDOException $e) {
                return null;
            }
        } else {
            try {
                $stmt = Connection::connect()->prepare("SELECT $select FROM `$table`;");
                $stmt->execute();
            } catch (PDOException $e) {
                return null;
            }
        }

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    // Peticiones GET con filtro

    static public function get_filter_data($table, $link_to, $equal_to, $order_by, $order_mode, $start_at, $end_at, $select)
    {

        /*=============================================
		Seleccionar varios filtros
		=============================================*/

        $link_to_array = explode(",", $link_to);
        $equal_to_array = explode(",", $equal_to);

        $link_to_text = "";

        if(count($link_to_array) > 1) {
            foreach ($link_to_array as $key => $value) {
                if($key > 0) {
                    $link_to_text .= "AND $value = :$value ";
                }
            }
        }

        if ($order_by !== null && $order_mode !== null && $start_at === null && $end_at === null) {
            try {
                $stmt = Connection::connect()->prepare("SELECT $select FROM `$table` WHERE `$link_to_array[0]` = :$link_to_array[0] $link_to_text ORDER BY `$order_by` $order_mode;");

                foreach ($link_to_array as $key => $value) {
                    $stmt->bindParam(":$value", $equal_to_array[$key], PDO::PARAM_STR);
                }
                
                $stmt->execute();
            } catch (PDOException $e) {
                return null;
            }
        } else if ($order_by !== null && $order_mode !== null && $start_at !== null && $end_at !== null) {
            try {
                $stmt = Connection::connect()->prepare("SELECT $select FROM `$table` WHERE `$link_to_array[0]` = :$link_to_array[0] $link_to_text ORDER BY `$order_by` $order_mode LIMIT $start_at, $end_at;");

                foreach ($link_to_array as $key => $value) {
                    $stmt->bindParam(":$value", $equal_to_array[$key], PDO::PARAM_STR);
                }

                $stmt->execute();
            } catch (PDOException $e) {
                return null;
            }
        } else {
            try {
                $stmt = Connection::connect()->prepare("SELECT $select FROM `$table` WHERE `$link_to_array[0]` = :$link_to_array[0] $link_to_text;");

                foreach ($link_to_array as $key => $value) {
                    $stmt->bindParam(":$value", $equal_to_array[$key], PDO::PARAM_STR);
                }

                $stmt->execute();
            } catch (PDOException $e) {
                return null;
            }
        }

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    // Peticiones GET entre tablas relacionadas sin filtro

    static public function get_rel_data($rel, $type, $order_by, $order_mode, $start_at, $end_at, $select)
    {
        // Obteniendo las tablas que se estan intentando relacionar
        $rel_array = explode(",", $rel);

        // Obteniendo el tipo de relaciones de las tablas
        $type_array = explode(",", $type);


        // Cuando se relacionan 2 tablas
        if (count($rel_array) === 2 && count($type_array) === 2) {

            // Relacionando los datos que van despues del INNER JOIN
            $on1 = "`$rel_array[0]`.`id_$type_array[1]_$type_array[0]`";
            $on2 = "`$rel_array[1]`.`id_$type_array[1]`";

            if ($order_by !== null && $order_mode !== null && $start_at === null && $end_at === null) {
                try {
                    $stmt = Connection::connect()->prepare("SELECT $select FROM `$rel_array[0]` INNER JOIN `$rel_array[1]` ON $on1 = $on2 ORDER BY `$order_by` $order_mode;");
                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            } else if ($order_by !== null && $order_mode !== null && $start_at !== null && $end_at !== null) {
                try {
                    $stmt = Connection::connect()->prepare("SELECT $select FROM `$rel_array[0]` INNER JOIN `$rel_array[1]` ON $on1 = $on2 ORDER BY `$order_by` $order_mode LIMIT $start_at, $end_at;");
                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            } else {
                try {
                    $stmt = Connection::connect()->prepare("SELECT $select FROM `$rel_array[0]` INNER JOIN `$rel_array[1]` ON $on1 = $on2;");
                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            }
        }

        // Cuando se relacionan 3 tablas
        if (count($rel_array) === 3 && count($type_array) === 3) {
            // Relacionando los datos que van despues del INNER JOIN
            $on1a = "`$rel_array[0]`.`id_$type_array[1]_$type_array[0]`";
            $on1b = "`$rel_array[1]`.`id_$type_array[1]`";

            $on2a = "`$rel_array[0]`.`id_$type_array[2]_$type_array[0]`";
            $on2b = "`$rel_array[2]`.`id_$type_array[2]`";

            if ($order_by !== null && $order_mode !== null && $start_at === null && $end_at === null) {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT * FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1a = $on1b
                        INNER JOIN $rel_array[2] ON
                        $on2a = $on2b
                        ORDER BY `$order_by` $order_mode;"
                    );
                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            } else if ($order_by !== null && $order_mode !== null && $start_at !== null && $end_at !== null) {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT * FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1a = $on1b
                        INNER JOIN $rel_array[2] ON
                        $on2a = $on2b
                        ORDER BY `$order_by` $order_mode
                        LIMIT $start_at, $end_at;"
                    );
                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            } else {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT * FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1a = $on1b
                        INNER JOIN $rel_array[2] ON
                        $on2a = $on2b;"
                    );
                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            }
        }

        // Cuando se relacionan 4 tablas
        if (count($rel_array) === 4 && count($type_array) === 4) {
            // Relacionando los datos que van despues del INNER JOIN
            $on1a = "`$rel_array[0]`.`id_$type_array[1]_$type_array[0]`";
            $on1b = "`$rel_array[1]`.`id_$type_array[1]`";

            $on2a = "`$rel_array[0]`.`id_$type_array[2]_$type_array[0]`";
            $on2b = "`$rel_array[2]`.`id_$type_array[2]`";

            $on3a = "`$rel_array[0]`.`id_$type_array[3]_$type_array[0]`";
            $on3b = "`$rel_array[3]`.`id_$type_array[3]`";

            if ($order_by !== null && $order_mode !== null && $start_at === null && $end_at === null) {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT * FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1a = $on1b
                        INNER JOIN $rel_array[2] ON
                        $on2a = $on2b
                        INNER JOIN $rel_array[3] ON
                        $on3a = $on3b
                        ORDER BY `$order_by` $order_mode;"
                    );
                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            } else if ($order_by !== null && $order_mode !== null && $start_at !== null && $end_at !== null) {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT * FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1a = $on1b
                        INNER JOIN $rel_array[2] ON
                        $on2a = $on2b
                        INNER JOIN $rel_array[3] ON
                        $on3a = $on3b
                        ORDER BY `$order_by` $order_mode
                        LIMIT $start_at, $end_at;"
                    );
                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            } else {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT * FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1a = $on1b
                        INNER JOIN $rel_array[2] ON
                        $on2a = $on2b
                        INNER JOIN $rel_array[3] ON
                        $on3a = $on3b;"
                    );
                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            }
        }

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }


    // Peticiones GET entre tablas relacionadas con filtro
    static public function get_rel_filter_data($rel, $type, $link_to, $equal_to, $order_by, $order_mode, $start_at, $end_at, $select)
    {
         /*=============================================
		Seleccionar varios filtros
		=============================================*/
		
        $link_to_array = explode(",", $link_to);
        $equal_to_array = explode(",", $equal_to);

        $link_to_text = "";

        if(count($link_to_array) > 1) {
            foreach ($link_to_array as $key => $value) {
                if($key > 0) {
                    $link_to_text .= "AND $value = :$value ";
                }
            }
        }

        // Obteniendo las tablas que se estan intentando relacionar
        $rel_array = explode(",", $rel);

        // Obteniendo el tipo de relaciones de las tablas
        $type_array = explode(",", $type);


        // Cuando se relacionan 2 tablas
        if (count($rel_array) === 2 && count($type_array) === 2) {

            // Relacionando los datos que van despues del INNER JOIN
            $on1 = "`$rel_array[0]`.`id_$type_array[1]_$type_array[0]`";
            $on2 = "`$rel_array[1]`.`id_$type_array[1]`";

            if ($order_by !== null && $order_mode !== null && $start_at === null && $end_at === null) {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT $select FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1 = $on2
                        WHERE `$link_to_array[0]` = :$link_to_array[0] $link_to_text
                        ORDER BY `$order_by` $order_mode;"
                    );

                    foreach ($link_to_array as $key => $value) {
                        $stmt->bindParam(":$value", $equal_to_array[$key], PDO::PARAM_STR);
                    }

                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            } else if ($order_by !== null && $order_mode !== null && $start_at !== null && $end_at !== null) {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT $select FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1 = $on2
                        WHERE `$link_to_array[0]` = :$link_to_array[0] $link_to_text
                        ORDER BY `$order_by` $order_mode
                        LIMIT $start_at, $end_at;"
                    );

                    foreach ($link_to_array as $key => $value) {
                        $stmt->bindParam(":$value", $equal_to_array[$key], PDO::PARAM_STR);
                    }

                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            } else {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT $select FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1 = $on2
                        WHERE `$link_to_array[0]` = :$link_to_array[0] $link_to_text;"
                    );

                    foreach ($link_to_array as $key => $value) {
                        $stmt->bindParam(":$value", $equal_to_array[$key], PDO::PARAM_STR);
                    }

                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            }
        }

        // Cuando se relacionan 3 tablas
        if (count($rel_array) === 3 && count($type_array) === 3) {
            // Relacionando los datos que van despues del INNER JOIN
            $on1a = "`$rel_array[0]`.`id_$type_array[1]_$type_array[0]`";
            $on1b = "`$rel_array[1]`.`id_$type_array[1]`";

            $on2a = "`$rel_array[0]`.`id_$type_array[2]_$type_array[0]`";
            $on2b = "`$rel_array[2]`.`id_$type_array[2]`";

            if ($order_by !== null && $order_mode !== null && $start_at === null && $end_at === null) {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT $select FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1a = $on1b
                        INNER JOIN $rel_array[2] ON
                        $on2a = $on2b
                        WHERE `$link_to_array[0]` = :$link_to_array[0] $link_to_text
                        ORDER BY `$order_by` $order_mode;"
                    );

                    foreach ($link_to_array as $key => $value) {
                        $stmt->bindParam(":$value", $equal_to_array[$key], PDO::PARAM_STR);
                    }

                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            } else if ($order_by !== null && $order_mode !== null && $start_at !== null && $end_at !== null) {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT $select FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1a = $on1b
                        INNER JOIN $rel_array[2] ON
                        $on2a = $on2b
                        WHERE `$link_to_array[0]` = :$link_to_array[0] $link_to_text
                        ORDER BY `$order_by` $order_mode
                        LIMIT $start_at, $end_at;"
                    );

                    foreach ($link_to_array as $key => $value) {
                        $stmt->bindParam(":$value", $equal_to_array[$key], PDO::PARAM_STR);
                    }

                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            } else {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT $select FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1a = $on1b
                        INNER JOIN $rel_array[2] ON
                        $on2a = $on2b
                        WHERE `$link_to_array[0]` = :$link_to_array[0] $link_to_text;"
                    );

                    foreach ($link_to_array as $key => $value) {
                        $stmt->bindParam(":$value", $equal_to_array[$key], PDO::PARAM_STR);
                    }

                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            }
        }

        // Cuando se relacionan 4 tablas
        if (count($rel_array) === 4 && count($type_array) === 4) {
            // Relacionando los datos que van despues del INNER JOIN
            $on1a = "`$rel_array[0]`.`id_$type_array[1]_$type_array[0]`";
            $on1b = "`$rel_array[1]`.`id_$type_array[1]`";

            $on2a = "`$rel_array[0]`.`id_$type_array[2]_$type_array[0]`";
            $on2b = "`$rel_array[2]`.`id_$type_array[2]`";

            $on3a = "`$rel_array[0]`.`id_$type_array[3]_$type_array[0]`";
            $on3b = "`$rel_array[3]`.`id_$type_array[3]`";

            if ($order_by !== null && $order_mode !== null && $start_at === null && $end_at === null) {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT $select FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1a = $on1b
                        INNER JOIN $rel_array[2] ON
                        $on2a = $on2b
                        INNER JOIN $rel_array[3] ON
                        $on3a = $on3b
                        WHERE `$link_to_array[0]` = :$link_to_array[0] $link_to_text
                        ORDER BY `$order_by` $order_mode;"
                    );

                    foreach ($link_to_array as $key => $value) {
                        $stmt->bindParam(":$value", $equal_to_array[$key], PDO::PARAM_STR);
                    }

                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            } else if ($order_by !== null && $order_mode !== null && $start_at !== null && $end_at !== null) {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT $select FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1a = $on1b
                        INNER JOIN $rel_array[2] ON
                        $on2a = $on2b
                        INNER JOIN $rel_array[3] ON
                        $on3a = $on3b
                        WHERE `$link_to_array[0]` = :$link_to_array[0] $link_to_text
                        ORDER BY `$order_by` $order_mode
                        LIMIT $start_at, $end_at;"
                    );

                    foreach ($link_to_array as $key => $value) {
                        $stmt->bindParam(":$value", $equal_to_array[$key], PDO::PARAM_STR);
                    }

                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            } else {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT $select FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1a = $on1b
                        INNER JOIN $rel_array[2] ON
                        $on2a = $on2b
                        INNER JOIN $rel_array[3] ON
                        $on3a = $on3b
                        WHERE `$link_to_array[0]` = :$link_to_array[0] $link_to_text;"
                    );

                    foreach ($link_to_array as $key => $value) {
                        $stmt->bindParam(":$value", $equal_to_array[$key], PDO::PARAM_STR);
                    }

                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            }
        }

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }


    // Peticiones GET para el buscador

    static public function get_search_data($table, $link_to, $search, $order_by, $order_mode, $start_at, $end_at, $select)
    {

        // Seleccionar varios filtros
        $link_to_array = explode(",", $link_to);
        $search_array = explode(",", $search);

        $link_to_text = "";

        if(count($link_to_array) > 1) {
            foreach ($link_to_array as $key => $value) {
                if($key > 0) {
                    $link_to_text .= "AND $value = :$value ";
                }
            }
        }

        if ($order_by !== null && $order_mode !== null && $start_at === null && $end_at === null) {
            try {
                $stmt = Connection::connect()->prepare("SELECT $select FROM `$table` WHERE `$link_to_array[0]` LIKE '%$search_array[0]%' $link_to_text ORDER BY `$order_by` $order_mode");

                if(count($link_to_array) > 1) {

                    unset($link_to_array[0]);
        
                    // sort($link_to_array);
        
                    foreach ($link_to_array as $key => $value) {
                        $stmt->bindParam(":$value", $search_array[$key], PDO::PARAM_STR);
                    }
                }

                $stmt->execute();
            } catch (PDOException $e) {
                return null;
            }
        } else if ($order_by !== null && $order_mode !== null && $start_at !== null && $end_at !== null) {
            try {
                $stmt = Connection::connect()->prepare("SELECT $select FROM `$table` WHERE `$link_to_array[0]` LIKE '%$search_array[0]%' $link_to_text ORDER BY `$order_by` $order_mode LIMIT $start_at, $end_at");

                if(count($link_to_array) > 1) {

                    unset($link_to_array[0]);
        
                    // sort($link_to_array);
        
                    foreach ($link_to_array as $key => $value) {
                        $stmt->bindParam(":$value", $search_array[$key], PDO::PARAM_STR);
                    }
                }

                $stmt->execute();
            } catch (PDOException $e) {
                return null;
            }
        } else {
            try {
                $stmt = Connection::connect()->prepare("SELECT $select FROM `$table` WHERE `$link_to_array[0]` LIKE '%$search_array[0]%' $link_to_text");

                if(count($link_to_array) > 1) {

                    unset($link_to_array[0]);
        
                    // sort($link_to_array);
        
                    foreach ($link_to_array as $key => $value) {
                        $stmt->bindParam(":$value", $search_array[$key], PDO::PARAM_STR);
                    }
                }

                $stmt->execute();
            } catch (PDOException $e) {
                return null;
            }
        }

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }


    // Peticiones GET para el buscador entre tablas relacionadas
    static public function get_search_rel_data($rel, $type, $link_to, $search, $order_by, $order_mode, $start_at, $end_at, $select)
    {
        
        /*=============================================
		Seleccionar varios filtros
		=============================================*/
		
        $link_to_array = explode(",", $link_to);
        $search_array = explode(",", $search);

        $link_to_text = "";

        if(count($link_to_array) > 1) {
            foreach ($link_to_array as $key => $value) {
                if($key > 0) {
                    $link_to_text .= "AND $value = :$value ";
                }
            }
        }

        // Obteniendo las tablas que se estan intentando relacionar
        $rel_array = explode(",", $rel);

        // Obteniendo el tipo de relaciones de las tablas
        $type_array = explode(",", $type);


        // Cuando se relacionan 2 tablas
        if (count($rel_array) === 2 && count($type_array) === 2) {

            // Relacionando los datos que van despues del INNER JOIN
            $on1 = "`$rel_array[0]`.`id_$type_array[1]_$type_array[0]`";
            $on2 = "`$rel_array[1]`.`id_$type_array[1]`";

            if ($order_by !== null && $order_mode !== null && $start_at === null && $end_at === null) {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT $select FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1 = $on2
                        WHERE `$link_to_array[0]` LIKE '%$search_array[0]%' $link_to_text
                        ORDER BY `$order_by` $order_mode;"
                    );

                    if(count($link_to_array) > 1) {

                        unset($link_to_array[0]);
            
                        foreach ($link_to_array as $key => $value) {
                            $stmt->bindParam(":$value", $search_array[$key], PDO::PARAM_STR);
                        }
                    }

                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            } else if ($order_by !== null && $order_mode !== null && $start_at !== null && $end_at !== null) {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT $select FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1 = $on2
                        WHERE `$link_to_array[0]` LIKE '%$search_array[0]%' $link_to_text
                        ORDER BY `$order_by` $order_mode
                        LIMIT $start_at, $end_at;"
                    );

                    if(count($link_to_array) > 1) {

                        unset($link_to_array[0]);
            
                        foreach ($link_to_array as $key => $value) {
                            $stmt->bindParam(":$value", $search_array[$key], PDO::PARAM_STR);
                        }
                    }

                    $stmt->execute();
                } catch (PDOException $e) {
                    echo $e -> getMessage();
                    return null;
                }
            } else {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT $select FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1 = $on2
                        WHERE `$link_to_array[0]` LIKE '%$search_array[0]%' $link_to_text;"
                    );

                    if(count($link_to_array) > 1) {

                        unset($link_to_array[0]);
            
                        foreach ($link_to_array as $key => $value) {
                            $stmt->bindParam(":$value", $search_array[$key], PDO::PARAM_STR);
                        }
                    }

                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            }
        }

        // Cuando se relacionan 3 tablas
        if (count($rel_array) === 3 && count($type_array) === 3) {
            // Relacionando los datos que van despues del INNER JOIN
            $on1a = "`$rel_array[0]`.`id_$type_array[1]_$type_array[0]`";
            $on1b = "`$rel_array[1]`.`id_$type_array[1]`";

            $on2a = "`$rel_array[0]`.`id_$type_array[2]_$type_array[0]`";
            $on2b = "`$rel_array[2]`.`id_$type_array[2]`";

            if ($order_by !== null && $order_mode !== null && $start_at === null && $end_at === null) {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT $select FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1a = $on1b
                        INNER JOIN $rel_array[2] ON
                        $on2a = $on2b
                        WHERE `$link_to_array[0]` LIKE '%$search_array[0]%' $link_to_text
                        ORDER BY `$order_by` $order_mode;"
                    );

                    if(count($link_to_array) > 1) {

                        unset($link_to_array[0]);
            
                        foreach ($link_to_array as $key => $value) {
                            $stmt->bindParam(":$value", $search_array[$key], PDO::PARAM_STR);
                        }
                    }

                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            } else if ($order_by !== null && $order_mode !== null && $start_at !== null && $end_at !== null) {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT $select FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1a = $on1b
                        INNER JOIN $rel_array[2] ON
                        $on2a = $on2b
                        WHERE `$link_to_array[0]` LIKE '%$search_array[0]%' $link_to_text
                        ORDER BY `$order_by` $order_mode
                        LIMIT $start_at, $end_at;"
                    );

                    if(count($link_to_array) > 1) {

                        unset($link_to_array[0]);
            
                        foreach ($link_to_array as $key => $value) {
                            $stmt->bindParam(":$value", $search_array[$key], PDO::PARAM_STR);
                        }
                    }

                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            } else {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT $select FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1a = $on1b
                        INNER JOIN $rel_array[2] ON
                        $on2a = $on2b
                        WHERE `$link_to_array[0]` LIKE '%$search_array[0]%' $link_to_text;"
                    );

                    if(count($link_to_array) > 1) {

                        unset($link_to_array[0]);
            
                        foreach ($link_to_array as $key => $value) {
                            $stmt->bindParam(":$value", $search_array[$key], PDO::PARAM_STR);
                        }
                    }

                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            }
        }

        // Cuando se relacionan 4 tablas
        if (count($rel_array) === 4 && count($type_array) === 4) {
            // Relacionando los datos que van despues del INNER JOIN
            $on1a = "`$rel_array[0]`.`id_$type_array[1]_$type_array[0]`";
            $on1b = "`$rel_array[1]`.`id_$type_array[1]`";

            $on2a = "`$rel_array[0]`.`id_$type_array[2]_$type_array[0]`";
            $on2b = "`$rel_array[2]`.`id_$type_array[2]`";

            $on3a = "`$rel_array[0]`.`id_$type_array[3]_$type_array[0]`";
            $on3b = "`$rel_array[3]`.`id_$type_array[3]`";

            if ($order_by !== null && $order_mode !== null && $start_at === null && $end_at === null) {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT $select FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1a = $on1b
                        INNER JOIN $rel_array[2] ON
                        $on2a = $on2b
                        INNER JOIN $rel_array[3] ON
                        $on3a = $on3b
                        WHERE `$link_to_array[0]` LIKE '%$search_array[0]%' $link_to_text
                        ORDER BY `$order_by` $order_mode;"
                    );

                    if(count($link_to_array) > 1) {

                        unset($link_to_array[0]);
            
                        foreach ($link_to_array as $key => $value) {
                            $stmt->bindParam(":$value", $search_array[$key], PDO::PARAM_STR);
                        }
                    }

                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            } else if ($order_by !== null && $order_mode !== null && $start_at !== null && $end_at !== null) {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT $select FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1a = $on1b
                        INNER JOIN $rel_array[2] ON
                        $on2a = $on2b
                        INNER JOIN $rel_array[3] ON
                        $on3a = $on3b
                        WHERE `$link_to_array[0]` LIKE '%$search_array[0]%' $link_to_text
                        ORDER BY `$order_by` $order_mode
                        LIMIT $start_at, $end_at;"
                    );

                    if(count($link_to_array) > 1) {

                        unset($link_to_array[0]);
            
                        foreach ($link_to_array as $key => $value) {
                            $stmt->bindParam(":$value", $search_array[$key], PDO::PARAM_STR);
                        }
                    }

                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            } else {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT $select FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1a = $on1b
                        INNER JOIN $rel_array[2] ON
                        $on2a = $on2b
                        INNER JOIN $rel_array[3] ON
                        $on3a = $on3b
                        WHERE `$link_to_array[0]` LIKE '%$search_array[0]%' $link_to_text;"
                    );

                    if(count($link_to_array) > 1) {

                        unset($link_to_array[0]);
            
                        foreach ($link_to_array as $key => $value) {
                            $stmt->bindParam(":$value", $search_array[$key], PDO::PARAM_STR);
                        }
                    }

                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            }
        }
        
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }


    // Peticiones GET para rangos
    static public function get_between_data($table, $link_to, $between1, $between2, $filter_to, $inTo, $order_by, $order_mode, $start_at, $end_at, $select)
    {

        if($inTo != 0) {
            $inTo = "IN ($inTo)";
        } else {
            $inTo = "NOT IN ($inTo)";
        }

        // Seleccionar varios filtro

        if ($order_by !== null && $order_mode !== null && $start_at === null && $end_at === null) {
            try {
                $stmt = Connection::connect()->prepare("SELECT $select FROM `$table` WHERE `$link_to` BETWEEN '$between1' AND '$between2' AND `$filter_to` $inTo ORDER BY `$order_by` $order_mode");

                $stmt->execute();
            } catch (PDOException $e) {
                return null;
            }
        } else if ($order_by !== null && $order_mode !== null && $start_at !== null && $end_at !== null) {
            try {
                $stmt = Connection::connect()->prepare("SELECT $select FROM `$table` WHERE `$link_to` BETWEEN '$between1' AND '$between2' AND `$filter_to` $inTo ORDER BY `$order_by` $order_mode LIMIT $start_at, $end_at");

                $stmt->execute();
            } catch (PDOException $e) {
                return null;
            }
        } else {
            try {
                $stmt = Connection::connect()->prepare("SELECT $select FROM `$table` WHERE `$link_to` BETWEEN '$between1' AND '$between2' AND `$filter_to` $inTo");

                $stmt->execute();
            } catch (PDOException $e) {
                return null;
            }
        }

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }


    // Peticiones GET para rangos entre tablas relacionadas
    static public function get_between_rel_data($rel, $type, $link_to, $between1, $between2, $filter_to, $inTo, $order_by, $order_mode, $start_at, $end_at, $select)
    {

        if($inTo != 0) {
            $inTo = "IN ($inTo)";
        } else {
            $inTo = "NOT IN ($inTo)";
        }
        
        // Obteniendo las tablas que se estan intentando relacionar
        $rel_array = explode(",", $rel);

        // Obteniendo el tipo de relaciones de las tablas
        $type_array = explode(",", $type);


        // Cuando se relacionan 2 tablas
        if (count($rel_array) === 2 && count($type_array) === 2) {

            // Relacionando los datos que van despues del INNER JOIN
            $on1 = "`$rel_array[0]`.`id_$type_array[1]_$type_array[0]`";
            $on2 = "`$rel_array[1]`.`id_$type_array[1]`";

            if ($order_by !== null && $order_mode !== null && $start_at === null && $end_at === null) {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT $select FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1 = $on2
                        WHERE `$link_to` BETWEEN '$between1' AND '$between2'
                        AND `$filter_to` $inTo
                        ORDER BY `$order_by` $order_mode;"
                    );

                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            } else if ($order_by !== null && $order_mode !== null && $start_at !== null && $end_at !== null) {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT $select FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1 = $on2
                        WHERE `$link_to` BETWEEN '$between1' AND '$between2'
                        AND `$filter_to` $inTo
                        ORDER BY `$order_by` $order_mode
                        LIMIT $start_at, $end_at;"
                    );

                    $stmt->execute();
                } catch (PDOException $e) {
                    echo $e -> getMessage();
                    return null;
                }
            } else {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT $select FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1 = $on2
                        WHERE `$link_to` BETWEEN '$between1' AND '$between2'
                        AND `$filter_to` $inTo;"
                    );

                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            }
        }

        // Cuando se relacionan 3 tablas
        if (count($rel_array) === 3 && count($type_array) === 3) {
            // Relacionando los datos que van despues del INNER JOIN
            $on1a = "`$rel_array[0]`.`id_$type_array[1]_$type_array[0]`";
            $on1b = "`$rel_array[1]`.`id_$type_array[1]`";

            $on2a = "`$rel_array[0]`.`id_$type_array[2]_$type_array[0]`";
            $on2b = "`$rel_array[2]`.`id_$type_array[2]`";

            if ($order_by !== null && $order_mode !== null && $start_at === null && $end_at === null) {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT $select FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1a = $on1b
                        INNER JOIN $rel_array[2] ON
                        $on2a = $on2b
                        WHERE `$link_to` BETWEEN '$between1' AND '$between2'
                        AND `$filter_to` $inTo
                        ORDER BY `$order_by` $order_mode;"
                    );

                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            } else if ($order_by !== null && $order_mode !== null && $start_at !== null && $end_at !== null) {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT $select FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1a = $on1b
                        INNER JOIN $rel_array[2] ON
                        $on2a = $on2b
                        WHERE `$link_to` BETWEEN '$between1' AND '$between2'
                        AND `$filter_to` $inTo
                        ORDER BY `$order_by` $order_mode
                        LIMIT $start_at, $end_at;"
                    );

                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            } else {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT $select FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1a = $on1b
                        INNER JOIN $rel_array[2] ON
                        $on2a = $on2b
                        WHERE `$link_to` BETWEEN '$between1' AND '$between2'
                        AND `$filter_to` $inTo;"
                    );

                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            }
        }

        // Cuando se relacionan 4 tablas
        if (count($rel_array) === 4 && count($type_array) === 4) {
            // Relacionando los datos que van despues del INNER JOIN
            $on1a = "`$rel_array[0]`.`id_$type_array[1]_$type_array[0]`";
            $on1b = "`$rel_array[1]`.`id_$type_array[1]`";

            $on2a = "`$rel_array[0]`.`id_$type_array[2]_$type_array[0]`";
            $on2b = "`$rel_array[2]`.`id_$type_array[2]`";

            $on3a = "`$rel_array[0]`.`id_$type_array[3]_$type_array[0]`";
            $on3b = "`$rel_array[3]`.`id_$type_array[3]`";

            if ($order_by !== null && $order_mode !== null && $start_at === null && $end_at === null) {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT $select FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1a = $on1b
                        INNER JOIN $rel_array[2] ON
                        $on2a = $on2b
                        INNER JOIN $rel_array[3] ON
                        $on3a = $on3b
                        WHERE `$link_to` BETWEEN '$between1' AND '$between2'
                        AND `$filter_to` $inTo
                        ORDER BY `$order_by` $order_mode;"
                    );

                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            } else if ($order_by !== null && $order_mode !== null && $start_at !== null && $end_at !== null) {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT $select FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1a = $on1b
                        INNER JOIN $rel_array[2] ON
                        $on2a = $on2b
                        INNER JOIN $rel_array[3] ON
                        $on3a = $on3b
                        WHERE `$link_to` BETWEEN '$between1' AND '$between2'
                        AND `$filter_to` $inTo
                        ORDER BY `$order_by` $order_mode
                        LIMIT $start_at, $end_at;"
                    );

                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            } else {
                try {
                    $stmt = Connection::connect()->prepare(
                        "SELECT $select FROM `$rel_array[0]` 
                        INNER JOIN `$rel_array[1]` ON 
                        $on1a = $on1b
                        INNER JOIN $rel_array[2] ON
                        $on2a = $on2b
                        INNER JOIN $rel_array[3] ON
                        $on3a = $on3b
                        WHERE `$link_to` BETWEEN '$between1' AND '$between2'
                        AND `$filter_to` $inTo;"
                    );

                    $stmt->execute();
                } catch (PDOException $e) {
                    return null;
                }
            }
        }
        
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }
}
