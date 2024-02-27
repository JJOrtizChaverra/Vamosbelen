<?php

class DeleteController {
    // Peticion Delete para borrar datos

    public function delete_data($table, $id, $name_id) {
        $response = DeleteModel::delete_model($table, $id, $name_id);

        $return = new DeleteController();
        $return -> res_controller($response, "delete_data");
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