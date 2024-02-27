<?php

class GetController
{

    // Peticiones GET sin filtro
    public function get_data($table, $order_by, $order_mode, $start_at, $end_at, $select)
    {
        $response = GetModel::get_data($table, $order_by, $order_mode, $start_at, $end_at, $select);

        $return = new GetController();
        $return->res_controller($response, "get_data");
    }

    // Peticiones GEt con filtro
    public function get_filter_data($table, $link_to, $equal_to, $order_by, $order_mode, $start_at, $end_at, $select)
    {
        $response = GetModel::get_filter_data($table, $link_to, $equal_to, $order_by, $order_mode, $start_at, $end_at, $select);

        $return = new GetController();
        $return->res_controller($response, "get_filter_data");
    }

    // Peticiones GET entre tablas relacionadas sin filtro
    public function get_rel_data($rel, $type, $order_by, $order_mode, $start_at, $end_at, $select)
    {
        $response = GetModel::get_rel_data($rel, $type, $order_by, $order_mode, $start_at, $end_at, $select);

        $return = new GetController();
        $return->res_controller($response, "get_rel_data");
    }


    // Peticiones GET entre tablas relacionadas con filtro
    public function get_rel_filter_data($rel, $type, $link_to, $equal_to, $order_by, $order_mode, $start_at, $end_at, $select)
    {
        $response = GetModel::get_rel_filter_data($rel, $type, $link_to, $equal_to, $order_by, $order_mode, $start_at, $end_at, $select);

        $return = new GetController();
        $return->res_controller($response, "get_rel_filter_data");
    }


    // Peticiones GET para el buscador
    public function get_search_data($table, $link_to, $search, $order_by, $order_mode, $start_at, $end_at, $select)
    {
        $response = GetModel::get_search_data($table, $link_to, $search, $order_by, $order_mode, $start_at, $end_at, $select);

        $return = new GetController();
        $return->res_controller($response, "get_search_data");
    }

    // Peticiones GET para el buscador entre tablas relacionadas
    public function get_search_rel_data($rel, $type, $link_to, $search, $order_by, $order_mode, $start_at, $end_at, $select)
    {
        $response = GetModel::get_search_rel_data($rel, $type, $link_to, $search, $order_by, $order_mode, $start_at, $end_at, $select);

        $return = new GetController();
        $return->res_controller($response, "get_search_rel_data");
    }


    // Peticiones GET para rangos
    public function get_between_data($table, $link_to, $between1, $between2, $filter_to, $inTo, $order_by, $order_mode, $start_at, $end_at, $select)
    {
        $response = GetModel::get_between_data($table, $link_to, $between1, $between2, $filter_to, $inTo, $order_by, $order_mode, $start_at, $end_at, $select);

        $return = new GetController();
        $return->res_controller($response, "get_between_data");
    }

    // Peticiones GET para rangos entre tablas relacionadas
    public function get_between_rel_data($rel, $type, $link_to, $between1, $between2, $filter_to, $inTo, $order_by, $order_mode, $start_at, $end_at, $select)
    {
        $response = GetModel::get_between_rel_data($rel, $type, $link_to, $between1, $between2, $filter_to, $inTo, $order_by, $order_mode, $start_at, $end_at, $select);

        $return = new GetController();
        $return->res_controller($response, "get_between_rel_data");
    }

    // Respuestas del controlador

    public function res_controller($response, $method)
    {
        if (!empty($response)) {
            $json = array(
                "status" => 200,
                "total" => count($response),
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
