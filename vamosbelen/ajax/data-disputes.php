<?php

require_once "../controllers/curl.controller.php";
require_once "../controllers/template.controller.php";

class DataTableController
{

    // Funcion datatable
    public function data_disputes()
    {

        if (!empty($_POST)) {

            // Container utilizado por DataTable para garantizar que los retornos de AJAX
            // solicitudes de procesamiento del lado del servidor sean dibujados en secuencia
            // por datatable
            $draw = $_POST['draw'];

            // Indice de la columna de clasificacion (0 basado en el indice, es decir 0 es el primer registro)
            $order_by_column_index = $_POST['order'][0]['column'];

            // Obtener nombre de la columna de clasificacion de su indice
            $order_by = $_POST['columns'][$order_by_column_index]['data'];

            // Obtener el orden asc o desc
            $order_type = $_POST['order'][0]['dir'];

            // Indicador de primer registro de paginacion
            $start = $_POST['start'];

            // Longuitud de la paginacion
            $length = $_POST['length'];

            // Traer el total de la data de disputas
            $select = "id_dispute";
            $url = CurlController::api() . "disputes?linkTo=id_store_dispute&equalTo={$_GET['id-store']}&select=$select&token={$_GET['token']}";
            $method = "GET";
            $fields = array();
            $header = array();

            $data = CurlController::request($url, $method, $fields, $header);

            if($data->status == 200) {
                $total_data = $data->total;
            } else {
                echo  '{"data": []}';
                return;
            }


            // Traer la data de disputas de acuerdo a la paginacion o al orden de busquedad
            $select = "id_dispute,id_order_dispute,displayname_user,email_user,content_dispute,answer_dispute,date_answer_dispute,date_created_dispute";

            // Cuando se usa el buscador de datatable
            if (!empty($_POST['search']['value'])) {

                $link_to = ["displayname_user", "email_user", "date_created_dispute", "date_answer_dispute", "content_dispute"];
                $search = str_replace(" ", "_", $_POST['search']['value']);

                foreach ($link_to as $key => $value) {

                    $url = CurlController::api() . "relations?rel=disputes,users&type=dispute,user&linkTo=" . $value . ",id_store_dispute&search=" . $search . "," . $_GET["id-store"] . "&orderBy=" . $order_by . "&orderMode=" . $order_type . "&startAt=" . $start . "&endAt=" . $length . "&select=" . $select . "&token={$_GET['token']}";

                    $search_disputes = CurlController::request($url, $method, $fields, $header)->result;

                    if ($search_disputes == "Not found") {

                        $data_disputes = array();
                    } else {

                        $data_disputes = $search_disputes;
                        $records_filtered = count($data_disputes);

                        break;
                    }
                }
            } else {
                $url = CurlController::api() . "relations?rel=disputes,users&type=dispute,user&linkTo=id_store_dispute&equalTo={$_GET['id-store']}&orderBy=$order_by&orderMode=$order_type&startAt=$start&endAt=$length&select=$select&token={$_GET['token']}";

                $data_disputes = CurlController::request($url, $method, $fields, $header)->result;

                $records_filtered = $total_data;
            }

            if (count($data_disputes) == 0) {
                echo  '{"data": []}';
                return;
            } else {

                // Construimos el dato JSON que debemos regresar
                $data_json = '
                {
                    "draw": ' . intval($draw) . ',
                    "recordsTotal": ' . $total_data . ',
                    "recordsFiltered": ' . $records_filtered . ',
                    "data": [
                ';

                // Recorremos la data de productos

                foreach ($data_disputes as $key => $dispute) {

                    // Id de la orden
                    $id_order = $dispute->id_order_dispute;

                    // Cliente de la disputa
                    $client_user = $dispute->displayname_user;

                    // Email del cliente que abre la disputa
                    $email_user = $dispute->email_user;

                    // Contenido de la disputa
                    $content_dispute = $dispute->content_dispute;

                    // Respuesta de la disputa
                    if ($dispute->answer_dispute != null) {
                        
                        $answer_dispute = $dispute->answer_dispute;
                    } else {
                        $answer_dispute = "<button class='btn btn-md btn-secondary answer-dispute' id-dispute='$dispute->id_dispute' client-dispute='$client_user' email-dispute='$email_user'>Answer</button>";

                        // $answer_dispute = TemplateController::html_clean($answer_dispute);
                    }



                    // Fecha de respuesta del la disputa
                    $date_answer_dispute = $dispute->date_answer_dispute;

                    // Fecha de creación del la disputa
                    $date_created_dispute = $dispute->date_created_dispute;

                    $data_json .= '
                    {
                        "id_dispute": "' . ($start + $key + 1) . '",
                        "id_order_dispute": "' . $id_order . '",
                        "displayname_user": "' . $client_user . '",
                        "email_user": "' . $email_user . '",
                        "content_dispute": "' . $content_dispute . '",
                        "answer_dispute": "' . $answer_dispute . '",
                        "date_answer_dispute": "' . $date_answer_dispute . '",
                        "date_created_dispute": "' . $date_created_dispute . '"
                    },';
                }

                $data_json = substr($data_json, 0, -1);

                $data_json .= ']}';

                echo $data_json;
            }
        }
    }
}

$data = new DataTableController();
$data->data_disputes();
