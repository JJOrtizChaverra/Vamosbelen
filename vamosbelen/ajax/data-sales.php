<?php

require_once "../controllers/curl.controller.php";
require_once "../controllers/template.controller.php";

class DataTableController
{

    // Funcion datatable
    public function data_sales()
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
            $select = "id_sale";
            $url = CurlController::api() . "sales?linkTo=id_store_sale&equalTo={$_GET['id-store']}&select=$select&token={$_GET['token']}";
            $method = "GET";
            $fields = array();
            $header = array();

            $data = CurlController::request($url, $method, $fields, $header);

            if ($data->status == 200) {
                $total_data = $data->total;
            } else {
                echo  '{"data": []}';
                return;
            }


            // Traer la data de disputas de acuerdo a la paginacion o al orden de busquedad
            $select = "unit_price_sale,commission_sale,date_created_sale,quantity_order,name_product_sale";

            // Cuando se usa el buscador de datatable
            if (!empty($_POST['search']['value'])) {

                $link_to = ["date_created_sale", "name_product_sale"];
                $search = str_replace(" ", "_", $_POST['search']['value']);

                foreach ($link_to as $key => $value) {

                    $url = CurlController::api() . "relations?rel=sales,orders&type=sale,order&linkTo=" . $value . ",id_store_sale&search=" . $search . "," . $_GET["id-store"] . "&orderBy=" . $order_by . "&orderMode=" . $order_type . "&startAt=" . $start . "&endAt=" . $length . "&select=" . $select . "&token={$_GET['token']}";

                    $search_sales = CurlController::request($url, $method, $fields, $header)->result;

                    if ($search_sales == "Not found") {

                        $data_sales = array();
                    } else {

                        $data_sales = $search_sales;
                        $records_filtered = count($data_sales);

                        break;
                    }
                }
            } else {

                // Preguntar si viene el rango de fechas
                if (!empty($_GET['between1']) && !empty($_GET['between2'])) {

                    $between1 = date("Y-m-d", strtotime($_GET['between1']));
                    $between2 = date("Y-m-d", strtotime($_GET['between2']));

                    $url = CurlController::api() . "relations?rel=sales,orders&type=sale,order&linkTo=date_created_sale&between1=$between1&between2=$between2&filterTo=id_store_sale&inTo={$_GET["id-store"]}&orderBy=id_sale&orderMode=ASC&select=$select&token={$_GET['token']}";

                } else {

                    // Traer la data de ventas
                    $url = CurlController::api() . "relations?rel=sales,orders&type=sale,order&linkTo=id_store_sale&equalTo={$_GET["id-store"]}&orderBy=id_sale&orderMode=ASC&select=$select&token={$_GET['token']}";
                }

                $data_sales = CurlController::request($url, $method, $fields, $header)->result;

                $records_filtered = $total_data;
            }


            if (count($data_sales) == 0) {
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

                foreach ($data_sales as $key => $sale) {

                    // Fecha de creacion de la venta
                    $date_created_sale = $sale->date_created_sale;

                    // Nombre del producto
                    $name_product = $sale->name_product_sale;

                    // Cantidad que se vendio
                    $quantity = $sale->quantity_order;

                    // Precio del producto
                    $price = $sale->unit_price_sale;

                    // Comisiones
                    $commisions = $sale->commission_sale;

                    // Total
                    $total = ($price + $commisions);


                    $data_json .= '
                    {
                        "date_created_sale": "' . $date_created_sale . '",
                        "name_product_sale": "' . $name_product . '",
                        "quantity_order": "' . $quantity . '",
                        "unit_price_sale": "' . $price . '",
                        "commission_sale": "' . $commisions . '",
                        "total": "' . $total . '"
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
$data->data_sales();
