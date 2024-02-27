<?php

use Firebase\JWT\JWT;

class PostController
{

    // Peticion para traer los nombres de columnas

    static public function get_columns_data($table, $database)
    {
        $response = PostModel::get_columns_data($table, $database);

        return $response;
    }

    // Peticion POST para insertar datos

    public function post_data($table, $data)
    {
        $response = PostModel::post_data($table, $data);

        $return = new PostController();
        $return->res_controller($response, "post_data", null);
    }

    // Metodo POST para el registro de usuarios

    public function post_register($table, $data)
    {
        if (isset($data['password_user']) && $data['password_user'] !== null) {
            $crypt = crypt($data['password_user'], '$2a$07$azybxcags23425sdg23sdfhsd$');

            $data['password_user'] = $crypt;

            $response = PostModel::post_data($table, $data);

            $return = new PostController();
            $return->res_controller($response, "post_data", null);
        }
    }

    // Metodo POST para el login de usuarios

    public function post_login($table, $data, $select)
    {
        // Obteniendo el usuario logueado
        $response = GetModel::get_filter_data($table, "email_user", $data['email_user'], null, null, null, null, "*");

        // Validando si se encontro o no el usuario en la base de datos
        if (!empty($response)) {

            // Encriptando la contraseña
            $crypt = crypt($data['password_user'], '$2a$07$azybxcags23425sdg23sdfhsd$');

            // Validando si la contraseña ingresada es la misma de la base de datos
            if ($response[0]->password_user == $crypt) {

                // Creacion del JWT
                $time = time();
                $key = "azscd252etnaia12nq28nzq828sn19ss9";

                $token = array(
                    "iat" => $time, // Tiempo en que inicio el token
                    "exp" => $time + (60 * 60 * 24), // Tiempo en que expira el token (24 horas)
                    "data" => [
                        "id" => $response[0]->id_user,
                        "email" => $response[0]->email_user
                    ]
                );

                $jwt = JWT::encode($token, $key, "HS256");

                // Actualizamos la base de datos con el token del usuario
                $data = array(
                    "token_user" => $jwt,
                    "token_exp_user" => $token['exp']
                );

                $update = PutModel::put_data($table, $data, $response[0]->id_user, "id_user");

                // Si el update se hizo correctamente
                if ($update == "OK") {
                    // Retornar la respuesta

                    $response[0]->token_user = $jwt;
                    $response[0]->token_exp_user = $token['exp'];

                    $return = new PostController();
                    $return->res_controller($response, "post_login", null);
                }
            } else {
                $response = null;

                $return = new PostController();
                $return->res_controller($response, "post_login", "Wrong password");
            }
        } else {
            $response = null;

            $return = new PostController();
            $return->res_controller($response, "post_login", "Wrong email");
        }
    }

    public function res_controller($response, $method, $error)
    {
        if (!empty($response)) {

            // Quitamos la contraseña de la respuesta

            if (isset($response[0]->password_user)) {
                unset($response[0]->password_user);
            }

            $json = array(
                "status" => 200,
                "result" => $response
            );
        } else {

            if ($error != null) {
                $json = array(
                    "status" => 400,
                    "result" => $error
                );
            } else {
                $json = array(
                    "status" => 404,
                    "result" => "Not found",
                    "method" => $method
                );
            }
        }

        echo json_encode($json, http_response_code($json['status']));

        return;
    }
}
