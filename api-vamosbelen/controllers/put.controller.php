<?php

class PutController {

    // Peticiones GET con filtro

    static public function get_filter_data($table, $link_to, $equal_to, $order_by, $order_mode, $start_at, $end_at, $select)
    {
        $response = GetModel::get_filter_data($table, $link_to, $equal_to, $order_by, $order_mode, $start_at, $end_at, $select);

        return $response;
    }

    // Peticion PUT para editar datos

    public function put_data($table, $data, $id, $name_id) {
        $response = PutModel::put_data($table, $data, $id, $name_id);

        $return = new PutController();
        $return -> res_controller($response, "put_data");
    }

    public function res_controller($response, $method)
    {
        if (!empty($response)) {
            $json = array(
                "status" => 200,
                "result" => $response
            );
        } else {
            $json = array(
                "status" => 404,
                "result" => "Not found",
                "method" => $method
            );
        }

        echo json_encode($json, http_response_code($json['status']));

        return;
    }
}

?>