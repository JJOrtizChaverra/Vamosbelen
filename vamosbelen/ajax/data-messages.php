<?php

require_once "../controllers/curl.controller.php";
require_once "../controllers/template.controller.php";

class DataTableController
{

    // Funcion datatable
    public function data_messages()
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

            // Traer el total de la data de mensajes
            $select = "id_message";
            $url = CurlController::api() . "messages?linkTo=id_store_message&equalTo={$_GET['id-store']}&select=$select&token={$_GET['token']}";
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

            // Traer la data de mensajes de acuerdo a la paginacion o al orden de busquedad
            $select = "id_message,content_message,answer_message,date_created_message,date_answer_message,name_product,url_product,displayname_user,email_user";

            // Cuando se usa el buscador de datatable
            if (!empty($_POST['search']['value'])) {

                $link_to = ["name_product", "displayname_user", "email_user", "date_created_message", "date_answer_message", "content_message"];
                $search = str_replace(" ", "_", $_POST['search']['value']);

                foreach ($link_to as $key => $value) {

                    $url = CurlController::api() . "relations?rel=messages,products,users&type=message,product,user&linkTo=" . $value . ",id_store_message&search=" . $search . "," . $_GET["id-store"] . "&orderBy=" . $order_by . "&orderMode=" . $order_type . "&startAt=" . $start . "&endAt=" . $length . "&select=" . $select . "&token={$_GET['token']}";

                    $search_messages = CurlController::request($url, $method, $fields, $header)->result;

                    if ($search_messages == "Not found") {

                        $data_messages = array();
                    } else {

                        $data_messages = $search_messages;
                        $records_filtered = count($data_messages);

                        break;
                    }
                }
            } else {
                $url = CurlController::api() . "relations?rel=messages,products,users&type=message,product,user&linkTo=id_store_message&equalTo={$_GET['id-store']}&orderBy=$order_by&orderMode=$order_type&startAt=$start&endAt=$length&select=$select&token={$_GET['token']}";

                $data_messages = CurlController::request($url, $method, $fields, $header)->result;

                $records_filtered = $total_data;
            }

            if (count($data_messages) == 0) {
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

                foreach ($data_messages as $key => $message) {

                    // Id de la orden
                    $name_product = $message->name_product;

                    // Cliente del mensaje
                    $client_user = $message->displayname_user;

                    // Email del cliente que abre el mensaje
                    $email_user = $message->email_user;

                    // Contenido del mensaje
                    $content_message = $message->content_message;

                    // Respuesta del mensaje
                    if ($message->answer_message != null) {
                        
                        $answer_message = $message->answer_message;
                    } else {
                        $answer_message = "<button class='btn btn-md btn-secondary answer-message' id-message='$message->id_message' client-message='$client_user' email-message='$email_user' url-product='$message->url_product'>Answer</button>";

                    }



                    // Fecha de respuesta dell mensaje
                    $date_answer_message = $message->date_answer_message;

                    // Fecha de creación dell mensaje
                    $date_created_message = $message->date_created_message;

                    $data_json .= '
                    {
                        "id_message": "' . ($start + $key + 1) . '",
                        "name_product": "' . $name_product . '",
                        "displayname_user": "' . $client_user . '",
                        "email_user": "' . $email_user . '",
                        "content_message": "' . $content_message . '",
                        "answer_message": "' . $answer_message . '",
                        "date_answer_message": "' . $date_answer_message . '",
                        "date_created_message": "' . $date_created_message . '"
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
$data->data_messages();
