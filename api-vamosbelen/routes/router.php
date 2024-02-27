<?php

// Creando un array que almacena las peticiones de la url hacia la API
$routes_array = array_filter(explode("/", $_SERVER["REQUEST_URI"]));

function call_json($status, $result)
{
    $json = array(
        "status" => $status,
        "result" => $result
    );

    echo json_encode($json, http_response_code($json['status']));

    return;
}

// Validando cuando no se hace ninguna peticion a la API
if (count($routes_array) == 0) {
    call_json(404, "Not found");
} else {
    // Cuando si se hace una peticion a la API

    // PETICIONES GET
    if ((count($routes_array) == 1) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER["REQUEST_METHOD"] == "GET")) {

        // Peticiones GET con autorizacion de token

        foreach (RoutesController::table_private() as $key_table => $table) {

            if ($table != "users") {

                if ((explode("?", $routes_array[1])[0] == $table) || (isset($_GET['rel'])) && (explode(",", $_GET['rel'])[0] == $table)) {

                    if (isset($_GET['token'])) {

                        // Traemos el usuario de acuerdo al token
                        $user = GetModel::get_filter_data("users", "token_user", $_GET['token'], null, null, null, null, "token_exp_user");

                        // Validamos que el token no haya expirado
                        if (!empty($user)) {

                            $time = time();

                            if ($user[0]->token_exp_user < $time) {

                                call_json(303, "Error: The token has expired");
                            }
                        } else {
                            call_json(400, "Error: Authorized required");
                            return;
                        }
                    } else {
                        call_json(400, "Error: Authorized required");
                        return;
                    }
                }
            }
        }

        if (isset($_GET['linkTo']) && isset($_GET['equalTo']) && !isset($_GET['rel'])) {

            // Peticiones GET con filtro

            // Validar si viene o no variables de orden

            if (isset($_GET['orderBy']) && isset($_GET['orderMode'])) {
                $order_by = $_GET['orderBy'];
                $order_mode = $_GET['orderMode'];
            } else {
                $order_by = null;
                $order_mode = null;
            }

            // Validar si viene o no variable de limite

            if (isset($_GET['startAt']) && isset($_GET['endAt'])) {
                $start_at = $_GET['startAt'];
                $end_at = $_GET['endAt'];
            } else {
                $start_at = null;
                $end_at = null;
            }

            if (isset($_GET['select'])) {
                $select = $_GET['select'];
            } else {
                $select = "*";
            }

            $table_name = explode("?", $routes_array[1])[0];

            $response = new GetController();
            $response->get_filter_data($table_name, $_GET['linkTo'], $_GET['equalTo'], $order_by, $order_mode, $start_at, $end_at, $select);
        } else if (isset($_GET['rel']) && isset($_GET['type']) && explode("?", $routes_array[1])[0] == "relations" && !isset($_GET['linkTo'])) {

            // Peticiones GET entre tablas relacionadas sin filtro

            // Validar si viene o no variables de orden

            if (isset($_GET['orderBy']) && isset($_GET['orderMode'])) {
                $order_by = $_GET['orderBy'];
                $order_mode = $_GET['orderMode'];
            } else {
                $order_by = null;
                $order_mode = null;
            }

            // Validar si viene o no variable de limite

            if (isset($_GET['startAt']) && isset($_GET['endAt'])) {
                $start_at = $_GET['startAt'];
                $end_at = $_GET['endAt'];
            } else {
                $start_at = null;
                $end_at = null;
            }

            if (isset($_GET['select'])) {
                $select = $_GET['select'];
            } else {
                $select = "*";
            }

            $response = new GetController();
            $response->get_rel_data($_GET['rel'], $_GET['type'], $order_by, $order_mode, $start_at, $end_at, $select);
        } else if (isset($_GET['rel']) && isset($_GET['type']) && explode("?", $routes_array[1])[0] == "relations" && isset($_GET['linkTo']) && isset($_GET['equalTo'])) {

            // Peticiones GET entre tablas relacionadas con filtro

            // Validar si viene o no variables de orden

            if (isset($_GET['orderBy']) && isset($_GET['orderMode'])) {
                $order_by = $_GET['orderBy'];
                $order_mode = $_GET['orderMode'];
            } else {
                $order_by = null;
                $order_mode = null;
            }

            // Validar si viene o no variable de limite

            if (isset($_GET['startAt']) && isset($_GET['endAt'])) {
                $start_at = $_GET['startAt'];
                $end_at = $_GET['endAt'];
            } else {
                $start_at = null;
                $end_at = null;
            }

            if (isset($_GET['select'])) {
                $select = $_GET['select'];
            } else {
                $select = "*";
            }

            $response = new GetController();
            $response->get_rel_filter_data($_GET['rel'], $_GET['type'], $_GET['linkTo'], $_GET['equalTo'], $order_by, $order_mode, $start_at, $end_at, $select);
        } else if (isset($_GET['linkTo']) && isset($_GET['search'])) {

            $table_name = explode("?", $routes_array[1])[0];

            // Peticiones GET para el buscador

            // Validar si viene o no variables de orden

            if (isset($_GET['orderBy']) && isset($_GET['orderMode'])) {
                $order_by = $_GET['orderBy'];
                $order_mode = $_GET['orderMode'];
            } else {
                $order_by = null;
                $order_mode = null;
            }

            // Validar si viene o no variable de limite

            if (isset($_GET['startAt']) && isset($_GET['endAt'])) {
                $start_at = $_GET['startAt'];
                $end_at = $_GET['endAt'];
            } else {
                $start_at = null;
                $end_at = null;
            }

            if (isset($_GET['select'])) {
                $select = $_GET['select'];
            } else {
                $select = "*";
            }

            if (explode("?", $routes_array[1])[0] == "relations" && isset($_GET['rel']) && isset($_GET['type'])) {
                $response = new GetController();
                $response->get_search_rel_data($_GET['rel'], $_GET['type'], $_GET['linkTo'], $_GET['search'], $order_by, $order_mode, $start_at, $end_at, $select);
            } else {
                $response = new GetController();
                $response->get_search_data($table_name, $_GET['linkTo'], $_GET['search'], $order_by, $order_mode, $start_at, $end_at, $select);
            }
        } else if (isset($_GET['linkTo']) && isset($_GET['between1']) && isset($_GET['between2']) && isset($_GET['filterTo']) && isset($_GET['inTo'])) {

            $table_name = explode("?", $routes_array[1])[0];

            // Peticiones GET para rangos

            // Validar si viene o no variables de orden

            if (isset($_GET['orderBy']) && isset($_GET['orderMode'])) {
                $order_by = $_GET['orderBy'];
                $order_mode = $_GET['orderMode'];
            } else {
                $order_by = null;
                $order_mode = null;
            }

            // Validar si viene o no variable de limite

            if (isset($_GET['startAt']) && isset($_GET['endAt'])) {
                $start_at = $_GET['startAt'];
                $end_at = $_GET['endAt'];
            } else {
                $start_at = null;
                $end_at = null;
            }

            if (isset($_GET['select'])) {
                $select = $_GET['select'];
            } else {
                $select = "*";
            }

            if (explode("?", $routes_array[1])[0] == "relations" && isset($_GET['rel']) && isset($_GET['type'])) {
                $response = new GetController();
                $response->get_between_rel_data($_GET['rel'], $_GET['type'], $_GET['linkTo'], $_GET['between1'], $_GET['between2'], $_GET['filterTo'], $_GET['inTo'], $order_by, $order_mode, $start_at, $end_at, $select);
            } else {
                $response = new GetController();
                $response->get_between_data($table_name, $_GET['linkTo'], $_GET['between1'], $_GET['between2'], $_GET['filterTo'], $_GET['inTo'], $order_by, $order_mode, $start_at, $end_at, $select);
            }
        } else {
            // Peticiones GET sin filtro

            $table_name = explode("?", $routes_array[1])[0];

            // Peticiones GET con autorizacion administrativas

            foreach (RoutesController::table_private() as $key_table => $table) {

                if ($table_name == $table) {

                    if (isset($_GET['rol'])) {

                        $link_to = "username_user";
                        $equal_to = $_GET['rol'];

                        $response = GetModel::get_filter_data("users", $link_to, $equal_to, null, null, null, null, "rol_user");

                        if (count($response) > 0) {

                            if ($response[0]->rol_user != "admin") {

                                call_json(400, "You are not authorized to make this request");

                                return;
                            }
                        } else {
                            call_json(400, "You are not authorized to make this request");

                            return;
                        }
                    } else {
                        call_json(400, "You are not authorized to make this request");

                        return;
                    }
                }
            }

            // Validar si viene o no variables de orden

            if (isset($_GET['orderBy']) && isset($_GET['orderMode'])) {
                $order_by = $_GET['orderBy'];
                $order_mode = $_GET['orderMode'];
            } else {
                $order_by = null;
                $order_mode = null;
            }

            // Validar si viene o no variable de limite

            if (isset($_GET['startAt']) && isset($_GET['endAt'])) {
                $start_at = $_GET['startAt'];
                $end_at = $_GET['endAt'];
            } else {
                $start_at = null;
                $end_at = null;
            }

            if (isset($_GET['select'])) {
                $select = $_GET['select'];
            } else {
                $select = "*";
            }

            $response = new GetController();
            $response->get_data($table_name, $order_by, $order_mode, $start_at, $end_at, $select);
        }
    }

    // PETICIONES POST
    if ((count($routes_array) == 1) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER["REQUEST_METHOD"] == "POST")) {

        $columns = array();

        // Obteniendo el nombre de la db y la tabla que se esta llamando
        $database = RoutesController::db();
        $table_name = explode("?", $routes_array[1])[0];

        // Traemos el listado de columnas de la tabla
        $response = PostController::get_columns_data($table_name, $database);

        // Iterando sobre cada nombre de la tabla e insertandolo en un arreglo vacio
        foreach ($response as $key => $value) {
            array_push($columns, $value->item);
        }

        // Eliminando el primer y ultimo elemento del array de nombres (id, date_created)
        array_shift($columns);
        array_pop($columns);

        if (isset($_POST)) {

            // Validamos que las variables POST coincidan con los nombres de columnas en de la base de datos
            $count = 0;

            foreach (array_keys($_POST) as $key => $value) {
                $count = array_search($value, $columns);
            }

            if ($count > 0) {
                if (isset($_GET['register']) && $_GET['register'] == true) {
                    // Solicitar respuesta del controlador para registrar al usuario
                    $response = new PostController();
                    $response->post_register($table_name, $_POST);
                } else if (isset($_GET['login']) && $_GET['login'] == true) {

                    if (isset($_GET['select'])) {
                        $select = $_GET['select'];
                    } else {
                        $select = "*";
                    }

                    // Solicitar respuesta del controlador para loguear al usuario      
                    $response = new PostController();
                    $response->post_login($table_name, $_POST, $select);
                } else if (isset($_GET['token'])) {

                    // Agregamos excepcion para actualizar sin autorizacion

                    if ($_GET['token'] == "no") {
                        if (isset($_GET['except'])) {

                            $num = 0;

                            foreach ($columns as $key => $value) {
                                // Buscamos coincidencias con esa excepcion

                                $num++;

                                if ($value == $_GET['except']) {
                                    // Solicitar respuesta del controlador para insertar datos en cualquier tabla        
                                    $response = new PostController();
                                    $response->post_data($table_name, $_POST);

                                    return;
                                }
                            }

                            // Cuando no encuentra coincidencia

                            if ($num == count($columns)) {
                                call_json(400, "The exception does not match the database");
                            }
                        } else {
                            call_json(400, "There is not exception");
                        }
                    } else {
                        // Validar si el token no ha expirado                                  

                        if (isset($_GET['select'])) {
                            $select = $_GET['select'];
                        } else {
                            $select = "*";
                        }

                        // Traemos al usuario de acuerdo al token
                        $user = GetModel::get_filter_data("users", "token_user", $_GET['token'], null, null, null, null, $select);

                        if (!empty($user)) {

                            // Validamos que el token no haya expirado

                            $time = time();

                            if ($user[0]->token_exp_user > $time) {
                                // Solicitar respuesta del controlador para insertar datos en cualquier tabla        
                                $response = new PostController();
                                $response->post_data($table_name, $_POST);
                            } else {
                                call_json(303, "Error: The token has expired");
                            }
                        } else {
                            call_json(400, "Error: The user is not authorized");
                        }
                    }
                } else {
                    call_json(400, "Error: Authorization required");
                }
            } else {
                call_json(400, "Error: Fields in the form do not match the database");
            }
        }
    }

    // PETICIONES PUT
    if ((count($routes_array) == 1) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER["REQUEST_METHOD"] == "PUT")) {

        $table_name = explode("?", $routes_array[1])[0];

        // Validamos si viene el ID

        if (isset($_GET['id']) && isset($_GET['nameId'])) {

            $link_to = $_GET['nameId'];
            $equal_to = $_GET['id'];
            $order_by = null;
            $order_mode = null;
            $start_at = null;
            $end_at = null;

            if (isset($_GET['select'])) {
                $select = $_GET['select'];
            } else {
                $select = "*";
            }

            $response = PutController::get_filter_data($table_name, $link_to, $equal_to, $order_by, $order_mode, $start_at, $end_at, $select);

            if ($response) {

                // Capturamos datos del formularios
                $data = array();
                parse_str(file_get_contents("php://input"), $data);

                // Traemos listado de columnas

                $columns = array();

                // Obteniendo el nombre de la db y la tabla que se esta llamando
                $database = RoutesController::db();
                $table_name = explode("?", $routes_array[1])[0];

                // Traemos el listado de columnas de la tabla
                $response = PostController::get_columns_data($table_name, $database);

                // Iterando sobre cada nombre de la tabla e insertandolo en un arreglo vacio
                foreach ($response as $key => $value) {
                    array_push($columns, $value->item);
                }

                // Eliminando el primer y ultimo elemento del array de nombres (id, date_created)
                array_shift($columns);
                array_pop($columns);
                array_pop($columns);

                $count = 0;

                foreach (array_keys($data) as $key => $value) {
                    $count = array_search($value, $columns);
                }

                if ($count > 0) {

                    if (isset($_GET['token'])) {

                        // Agregamos excepcion para actualizar sin autorizacion

                        if ($_GET['token'] == "no") {
                            if (isset($_GET['except'])) {

                                $num = 0;

                                foreach ($columns as $key => $value) {
                                    // Buscamos coincidencias con esa excepcion

                                    $num++;

                                    if ($value == $_GET['except']) {
                                        // Solicitamos respues del controlador para editar cualquier tabla
                                        $response = new PutController();
                                        $response->put_data($table_name, $data, $_GET['id'], $_GET['nameId']);

                                        return;
                                    }
                                }

                                // Cuando no encuentra coincidencia

                                if ($num == count($columns)) {
                                    call_json(400, "The exception does not match the database");
                                }
                            } else {
                                call_json(400, "There is not exception");
                            }
                        } else {

                            if (isset($_GET['select'])) {
                                $select = $_GET['select'];
                            } else {
                                $select = "*";
                            }

                            // Traemos al usuario de acuerdo al token
                            $user = GetModel::get_filter_data("users", "token_user", $_GET['token'], null, null, null, null, $select);

                            if (!empty($user)) {

                                // Validamos que el token no haya expirado

                                $time = time();

                                if ($user[0]->token_exp_user > $time) {
                                    // Solicitamos respues del controlador para editar cualquier tabla
                                    $response = new PutController();
                                    $response->put_data($table_name, $data, $_GET['id'], $_GET['nameId']);
                                } else {
                                    call_json(303, "Error: The token has expired");
                                }
                            } else {
                                call_json(400, "Error: The user is not authorized");
                            }
                        }
                    } else {
                        call_json(400, "Error: Authorization required");
                    }
                } else {
                    call_json(400, "Error: Fields in the form do not match the database");
                }
            } else {
                call_json(400, "Error: The id is not found in the database");
            }
        }
    }

    // PETICIONES DELETE
    if ((count($routes_array) == 1) && (isset($_SERVER["REQUEST_METHOD"])) && ($_SERVER["REQUEST_METHOD"] == "DELETE")) {
        $table_name = explode("?", $routes_array[1])[0];

        // Validamos si viene el ID

        if (isset($_GET['id']) && isset($_GET['nameId'])) {

            // Validamos si existe el id

            $link_to = $_GET['nameId'];
            $equal_to = $_GET['id'];
            $order_by = null;
            $order_mode = null;
            $start_at = null;
            $end_at = null;

            if (isset($_GET['select'])) {
                $select = $_GET['select'];
            } else {
                $select = "*";
            }

            $response = PutController::get_filter_data($table_name, $link_to, $equal_to, $order_by, $order_mode, $start_at, $end_at, $select);

            if ($response) {

                if (isset($_GET['token'])) {

                    if (isset($_GET['select'])) {
                        $select = $_GET['select'];
                    } else {
                        $select = "*";
                    }

                    // Traemos al usuario de acuerdo al token
                    $user = GetModel::get_filter_data("users", "token_user", $_GET['token'], null, null, null, null, $select);

                    if (!empty($user)) {

                        // Validamos que el token no haya expirado

                        $time = time();

                        if ($user[0]->token_exp_user > $time) {
                            // Solicitamos respuesta del controlador
                            $response = new DeleteController();
                            $response->delete_data($table_name, $_GET['id'], $_GET['nameId']);
                        } else {
                            call_json(303, "Error: The token has expired");
                        }
                    } else {
                        call_json(400, "Error: The user is not authorized");
                    }
                } else {
                    call_json(400, "Error: Authorization required");
                }
            } else {
                call_json(400, "Error: The id is not found in the database");
            }
        }
    }
}
