<?php

require_once "../controllers/curl.controller.php";
require_once "../controllers/template.controller.php";

class DataTableController
{

    // Funcion datatable
    public function data_orders()
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

            // Traer el total de la data de ordenes
            $select = "id_order";
            $url = CurlController::api() . "orders?linkTo=id_store_order&equalTo={$_GET['id-store']}&select=$select&token={$_GET['token']}";
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


            // Traer la data de ordenes de acuerdo a la paginacion o al orden de busquedad
            $select = "id_order,id_store_order,id_user_order,id_product_order,details_order,quantity_order,price_order,email_order,country_order,city_order,phone_order,address_order,notes_order,process_order,status_order,date_created_order,name_product,displayname_user,email_user";

            // Cuando se usa el buscador de datatable
            if (!empty($_POST['search']['value'])) {

                $link_to = ["id_order", "name_product", "displayname_user", "status_order", "date_created_order", "email_user"];
                $search = str_replace(" ", "_", $_POST['search']['value']);

                foreach ($link_to as $key => $value) {

                    $url = CurlController::api() . "relations?rel=orders,stores,users,products&type=order,store,user,product&linkTo=" . $value . ",id_store_order&search=" . $search . "," . $_GET["id-store"] . "&orderBy=" . $order_by . "&orderMode=" . $order_type . "&startAt=" . $start . "&endAt=" . $length . "&select=" . $select . "&token={$_GET['token']}";

                    $search_orders = CurlController::request($url, $method, $fields, $header)->result;

                    if ($search_orders == "Not found") {

                        $data_orders = array();
                    } else {

                        $data_orders = $search_orders;
                        $records_filtered = count($data_orders);

                        break;
                    }
                }
            } else {
                $url = CurlController::api() . "relations?rel=orders,stores,users,products&type=order,store,user,product&linkTo=id_store_order&equalTo={$_GET['id-store']}&orderBy=$order_by&orderMode=$order_type&startAt=$start&endAt=$length&select=$select&token={$_GET['token']}";

                $data_orders_petition = CurlController::request($url, $method, $fields, $header)->result;

                $data_orders = array();

                foreach ($data_orders_petition as $key => $order) {
                    
                    if($order->status_order != "test") {
                        array_push($data_orders, $order);
                    }
                }

                $records_filtered = $total_data;
            }

            if (count($data_orders) == 0) {
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

                foreach ($data_orders as $key => $order) {

                    // Status de la orden
                    if ($order->status_order == 'pending') {

                        $status_order = "<span class='badge badge-danger p-3'>" . $order->status_order . "</span>";
                    } else {

                        $status_order = "<span class='badge badge-success p-3'>" . $order->status_order . "</span>";
                    }

                    // Cliente de la orden
                    $client_order = $order->displayname_user;

                    // Email del cliente de la orden
                    $email_order = $order->email_order;

                    // Pais de la orden
                    $country_order = $order->country_order;

                    // Ciudad de la orden
                    $city_order = $order->city_order;

                    // Dirección de la orden
                    $address_order = $order->address_order;

                    // Teléfono de la orden
                    $phone_order = $order->phone_order;

                    // Producto de la orden
                    $product_order = $order->name_product;

                    // Cantidad de la orden
                    $quantity_order = $order->quantity_order;

                    // Detalles de la orden
                    // $details_order  =  "Hola";
                    $details_order  = TemplateController::html_clean($order->details_order);

                    // Precio de la orden
                    $price_order = $order->price_order;

                    // Proceso de la orden
                    $process_order = "<ul class='timeline'>";

                    foreach (json_decode($order->process_order, true) as $index => $process) {


                        if ($process["status"] == "ok") {

                            $process_order .= "<li class='success pl-5 ml-5'>
                                                <h5>" . $process["date"] . "</h5>
                                                <p class='text-success'>" . $process["stage"] . "<i class='fas fa-check pl-3'></i></p>
                                                <p>Comment: " . $process["comment"] . "</p>
                                            </li>";
                        } else {

                            $process_order .= "<li class='process pl-5 ml-5'>
                                                <h5>" . $process["date"] . "</h5>
                                                <p>" . $process["stage"] . "</p> 
                                                <button class='btn btn-primary' disabled>
                                                  <span class='spinner-border spinner-border-sm'></span>
                                                  In process
                                                </button>
                                            </li>";
                        }
                    }

                    $process_order .= "</ul>";

                    if ($order->status_order == 'pending') {

                        $process_order .= "<a class='btn btn-warning btn-lg next-process' id-order='" . $order->id_order . "' process-order='" . base64_encode($order->process_order) . "' client-order='" . $client_order . "' email-order='" . $email_order . "' product-order='" . $product_order . "' id-store='" . $order->id_store_order . "'>Next Process</a>";
                    }

                    $process_order  =  TemplateController::html_clean($process_order);

                    // Fecha de creación del la orden
                    $date_created_order = $order->date_created_order;

                    $data_json .= '
                    {
                        "id_order": "' . ($start + $key + 1) . '",
                        "status_order": "' . $status_order . '",
                        "displayname_user": "' . $client_order . '",
                        "email_order": "' . $email_order . '",
                        "country_order": "' . $country_order . '",
                        "city_order": "' . $city_order . '",
                        "address_order": "' . $address_order . '",
                        "phone_order": "' . $phone_order . '",
                        "name_product": "' . $product_order . '",
                        "quantity_order": "' . $quantity_order . '",
                        "details_order": "' . $details_order . '",
                        "price_order": "$' . $price_order . '",
                        "process_order": "' . $process_order . '",
                        "date_created_order": "' . $date_created_order . '"
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
$data->data_orders();
