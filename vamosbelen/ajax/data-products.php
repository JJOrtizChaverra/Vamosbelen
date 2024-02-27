<?php

require_once "../controllers/curl.controller.php";
require_once "../controllers/template.controller.php";

class DataTableController
{

    // Funcion datatable
    public function data_products()
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


            // Traer el total de la data de productos
            $select = "id_product";
            $url = CurlController::api() . "products?linkTo=id_store_product&equalTo={$_GET['id-store']}&select=$select";
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


            // Traer la data de productos de acuerdo a la paginacion o al orden de busquedad
            $select = "id_product,approval_product,state_product,url_product,feedback_product,url_category,image_product,name_product,name_category,name_subcategory,price_product,shipping_product,stock_product,delivery_time_product,offer_product,summary_product,specifications_product,details_product,description_product,tags_product,gallery_product,top_banner_product,default_banner_product,horizontal_slider_product,vertical_slider_product,video_product,views_product,sales_product,reviews_product,date_created_product,title_list_product";

            // Cuando se usa el buscador de datatable
            if (!empty($_POST['search']['value'])) {

                $link_to = ["name_product", "title_list_product", "tags_product", "name_category", "name_subcategory", "price_product"];
                $search = str_replace(" ", "_", $_POST['search']['value']);

                foreach ($link_to as $key => $filter) {
                    $url = CurlController::api() . "relations?rel=products,categories,subcategories,stores&type=product,category,subcategory,store&linkTo=" . $filter . ",id_store_product&search=" . $search . "," . $_GET["id-store"] . "&orderBy=" . $order_by . "&orderMode=" . $order_type . "&startAt=" . $start . "&endAt=" . $length . "&select=" . $select;

                    $search_products = CurlController::request($url, $method, $fields, $header)->result;

                    if ($search_products == "Not found") {
                        $data_products = array();
                    } else {
                        $data_products = $search_products;
                        $records_filtered = count($data_products);

                        break;
                    }
                }
            } else {
                $url = CurlController::api() . "relations?rel=products,categories,subcategories,stores&type=product,category,subcategory,store&linkTo=id_store_product&equalTo={$_GET['id-store']}&orderBy=$order_by&orderMode=$order_type&startAt=$start&endAt=$length&select=$select";

                $data_products = CurlController::request($url, $method, $fields, $header)->result;

                $records_filtered = $total_data;
            }



            // Verificar que la tabla no venga vacia

            if (count($data_products) == 0) {
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

                foreach ($data_products as $key => $product) {

                    // Actions
                    $actions = "<div class='btn-group'><a class='btn btn-info rounded-circle mr-2' href='" . TemplateController::path() . $product->url_product . "' target='_blank'><i class='fas fa-eye'></i></a><a class='btn btn-warning rounded-circle mr-2' href='".TemplateController::path() . "account&my-store?product=".$product->id_product."#profile-user'><i class='fas fa-pencil-alt'></i></a><a class='btn btn-danger rounded-circle text-white' onclick='removeProduct(".$product->id_product.")'><i class='fas fa-trash'></i></a></div>";

                    // Feedback

                    if ($product->approval_product == "approved") {
                        $feedback = "<h4> <span class='badge badge-success'>Approved</span></h4>";
                    } else {
                        $feedback = "<h4> <span data-toggle='tooltip' title='$product->feedback_product' class='badge badge-warning'>Review</span></h4>";
                    }


                    // State

                    if ($product->state_product == "show") {
                        $state = "<div class='custom-control custom-switch'><input type='checkbox' class='custom-control-input' id='switch-" . $key . "' checked onchange='changeState(event, " . $product->id_product . ", " . $key . ")'><label class='custom-control-label' for='switch-" . $key . "'></label></div>";
                    } else {
                        $state = "<div class='custom-control custom-switch'><input type='checkbox' class='custom-control-input' id='switch-" . $key . "' onchange='changeState(event, " . $product->id_product . ", " . $key . ")'><label class='custom-control-label' for='switch-" . $key . "'></label></div>";
                    }


                    // Imagen del producto
                    $image_product = "<img src='img/products/" . $product->url_category . "/" . $product->image_product . "' alt='" . $product->name_product . "'>";

                    // Nombre del producto
                    $name_product = $product->name_product;

                    // Nombre de la categoria
                    $name_category = $product->name_category;

                    // Nombre de la subcategoria
                    $name_subcategory = $product->name_subcategory;

                    // Precio del producto
                    $price_product = $product->price_product;

                    // Precio de envio del producto
                    $shipping_product = $product->shipping_product;

                    // Stock del producto

                    if ($product->stock_product >= 50) {
                        $stock_product = "<span class='badge badge-success p-2'>$product->stock_product</span>";
                    } else if ($product->stock_product < 50 && $product->stock_product > 20) {
                        $stock_product = "<span class='badge badge-warning p-2'>$product->stock_product</span>";
                    } else {
                        $stock_product = "<span class='badge badge-danger p-2'>$product->stock_product</span>";
                    }

                    // Tiempo de entrega del producto
                    $delivery_time_product = $product->delivery_time_product;

                    // Oferta del producto
                    if ($product->offer_product != null) {
                        if (json_decode($product->offer_product, true)[0] == "Discount") {
                            $offer_product = "<span>" . json_decode($product->offer_product, true)[1] . "% | " . json_decode($product->offer_product, true)[2] . "</span>";
                        }

                        if (json_decode($product->offer_product, true)[0] == "Fixed") {
                            $offer_product = "<span>" . json_decode($product->offer_product, true)[1] . " | " . json_decode($product->offer_product, true)[2] . "</span>";
                        }
                    } else {
                        $offer_product = "No offer";
                    }

                    // Resumen del producto
                    $summary_product = "<div><ul class'list-group p-3'>";

                    foreach (json_decode($product->summary_product, true) as $key => $summary) {
                        $summary_product .= "<li>$summary</li>";
                    }

                    $summary_product .= "</ul></div>";


                    // Especificaciones del producto
                    if ($product->specifications_product != null) {


                        $specifications_product = "<div class='ps-product__variations'>";

                        foreach (json_decode($product->specifications_product, true) as $specification) {

                            if (!empty(array_keys($specification)[0])) {

                                $specifications_product .= "<figure><figcaption>" . array_keys($specification)[0] . "</figcaption></figure>";
                            }

                            foreach ($specification as $i) {

                                foreach ($i as $v) {

                                    if (array_keys($specification)[0] == "Color") {

                                        $specifications_product .= "<div class='ps-variant round-circle mr-3' style='background-color:" . $v . "; width:30px; height:30px; cursor:pointer; border:1px solid #bbb'><span class='ps-variant__tooltip'>" . $v . "</span></div>";
                                    } else {

                                        $specifications_product .= "<div class='ps-variant ps-variant--size'><span class='ps-variant__tooltip'>" . $v . "</span><span class='ps-variant__size'>" . substr($v, 0, 3) . "</span></div>";
                                    }
                                }
                            }
                        }
                        $specifications_product .= "</div>";
                    } else {
                        $specifications_product = "No Specifications";
                    }


                    // Detalles del producto
                    $details_product = "<table class='table table-bordered ps-table ps-table--specification'></tbody>";

                    foreach (json_decode($product->details_product, true) as $key => $detail) {
                        $details_product .= "<tr><td>{$detail['title']}</td><td>{$detail['value']}</td></tr>";
                    }

                    $details_product .= "</tbody></table>";


                    // Descripcion del producto
                    $description_product = TemplateController::html_clean($product->description_product);
                    $description_product = preg_replace("/\"/", "'", $description_product);


                    // Galeria del producto
                    $gallery_product = "<div class='row'>";

                    foreach (json_decode($product->gallery_product, true) as $image) {

                        $gallery_product .= "<figure class='col-3'><img src='img/products/" . $product->url_category . "/gallery/" . $image . "'></figure>";
                    }

                    $gallery_product .= "</div>";


                    if ($product->top_banner_product != null) {
                        // Tob banner product
                        $top_banner_product = "<div class='py-3'>
                            <p><strong>H3 tag: </strong>" . json_decode($product->top_banner_product, true)['H3 tag'] . "</p>
                            <p><strong>P1 tag: </strong>" . json_decode($product->top_banner_product, true)['P1 tag'] . "</p>
                            <p><strong>H4 tag: </strong>" . json_decode($product->top_banner_product, true)['H4 tag'] . "</p>
                            <p><strong>P2 tag: </strong>" . json_decode($product->top_banner_product, true)['P2 tag'] . "</p>
                            <p><strong>Span tag: </strong>" . json_decode($product->top_banner_product, true)['Span tag'] . "</p>
                            <p><strong>Button tag: </strong>" . json_decode($product->top_banner_product, true)['Button tag'] . "</p>
                            <p><strong>IMG tag: </strong></p>
                            <img src='img/products/" . $product->url_category . "/top/" . json_decode($product->top_banner_product, true)['IMG tag'] . "' class='img-fluid'>
                        </div>";

                        $top_banner_product = TemplateController::html_clean($top_banner_product);
                    } else {
                        $top_banner_product = "No Top Banner";
                    }


                    // Default banner product
                    if($product->default_banner_product != null) {
                        $default_banner_product = "<div><img src='img/products/" . $product->url_category . "/default/" . $product->default_banner_product . "' class='img-fluid py-3'></div>";
                    } else {
                        $default_banner_product = "No Default Banner";
                    }


                    // Horizontal slider product

                    if ($product->horizontal_slider_product != null) {
                        $horizontal_slider_product = "<div class='py-3'>
                            <p><strong>H4 tag: </strong>" . json_decode($product->horizontal_slider_product, true)['H4 tag'] . "</p>
                            <p><strong>H3-1 tag: </strong>" . json_decode($product->horizontal_slider_product, true)['H3-1 tag'] . "</p>
                            <p><strong>H3-2 tag: </strong>" . json_decode($product->horizontal_slider_product, true)['H3-2 tag'] . "</p>
                            <p><strong>H3-3 tag: </strong>" . json_decode($product->horizontal_slider_product, true)['H3-3 tag'] . "</p>
                            <p><strong>H3-4s tag: </strong>" . json_decode($product->horizontal_slider_product, true)['H3-4s tag'] . "</p>
                            <p><strong>Button tag: </strong>" . json_decode($product->horizontal_slider_product, true)['Button tag'] . "</p>
                            <p><strong>IMG tag: </strong></p>

                            <img src='img/products/" . $product->url_category . "/horizontal/" . json_decode($product->horizontal_slider_product, true)['IMG tag'] . "'  class='img-fluid'>

                        </div>";

                        $horizontal_slider_product = TemplateController::html_clean($horizontal_slider_product);
                    } else {
                        $horizontal_slider_product = "No Horizontal Slider";
                    }

                    // Vertical slider product
                    if($product->vertical_slider_product != null) {
                        $vertical_slider_product = "<div><img src='img/products/" . $product->url_category . "/vertical/" . $product->vertical_slider_product . "' class='img-fluid py-3'></div>";
                    } else {
                        $vertical_slider_product = "No Vertical Slider";
                    }


                    // Video product
                    if ($product->video_product != null) {

                        if (json_decode($product->video_product, true)[0] == "youtube") {

                            $video_product = "<iframe 
                            class='mb-3'
                            src='https://www.youtube.com/embed/" . json_decode($product->video_product, true)[1] . "?rel=0&autoplay=0'
                            height='360' 
                            frameborder='0'
                            allowfullscreen></iframe>";
                        } else {

                            $video_product = "<iframe 
                            class='mb-3'
                            src='https://player.vimeo.com/video/" . json_decode($product->video_product, true)[1] . "'
                            height='360' 
                            frameborder='0'
                            allowfullscreen></iframe>";
                        }

                        $video_product  =  TemplateController::html_clean($video_product);
                    } else {

                        $video_product = "No Video";
                    }


                    // Tags del producto
                    $tags_product = "";

                    foreach (json_decode($product->tags_product, true) as $item) {

                        $tags_product .= $item . ", ";
                    }

                    $tags_product = substr($tags_product, 0, -2);


                    // Views Product
                    $views_product = $product->views_product;


                    // Sales Product
                    $sales_product = $product->sales_product;


                    // Reviews del producto
                    $reviews = TemplateController::average_reviews(
                        json_decode($product->reviews_product, true)
                    );

                    $reviews_product = "<div class='br-wrapper br-theme-fontawesome-stars'>
    
                            <select class='ps-rating' data-read-only='true' style='display:none'>";

                    if ($reviews > 0) {

                        for ($i = 0; $i < 5; $i++) {

                            if ($reviews < ($i + 1)) {

                                $reviews_product .= "<option value='1'>" . ($i + 1) . "</option>";
                            } else {

                                $reviews_product .= "<option value='2'>" . ($i + 1) . "</option>";
                            }
                        }
                    } else {

                        $reviews_product .=  "<option value='0'>0</option>";

                        for ($i = 0; $i < 5; $i++) {

                            $reviews_product .= "<option value='1'>" . ($i + 1) . "</option>";
                        }
                    }

                    $reviews_product .= "</select>
    
                        <div>
    
                        <div class='br-widget br-readonly'>";

                    if ($reviews > 0) {

                        for ($i = 0; $i < 5; $i++) {

                            if ($reviews < ($i + 1)) {

                                $reviews_product .= "<a href='#' data-rating-value='1' data-rating-text='" . ($i + 1) . "'></a>";
                            } else {

                                $reviews_product .= "<a href='#' data-rating-value='2' data-rating-text='" . ($i + 1) . "'  class='br-selected br-current'></a>";
                            }
                        }
                    } else {

                        for ($i = 0; $i < 5; $i++) {

                            $reviews_product .= "<a href='#' data-rating-value='1' data-rating-text='" . ($i + 1) . "'></a>";
                        }
                    }

                    $reviews_product .= "<div class='br-current-rating'>" . $reviews . "</div>
    
                        </div>";

                    $reviews_product  =  TemplateController::html_clean($reviews_product);


                    // Fecha de creacion del producto
                    $date_created_product = $product->date_created_product;

                    // Creamos los campos a mostrar
                    $data_json .= '
                    {
                        "id_product": "' . ($start + $key + 1) . '",
                        "actions": "' . $actions . '",
                        "feedback": "' . $feedback . '",
                        "state": "' . $state . '",
                        "image_product": "' . $image_product . '",
                        "name_product": "' . $name_product . '",
                        "name_category": "' . $name_category . '",
                        "name_subcategory": "' . $name_subcategory . '",
                        "price_product": "' . $price_product . '",
                        "shipping_product": "' . $shipping_product . '",
                        "stock_product": "' . $stock_product . '",
                        "delivery_time_product": "' . $delivery_time_product . '",
                        "offer_product": "' . $offer_product . '",
                        "summary_product": "' . $summary_product . '",
                        "specifications_product": "' . $specifications_product . '",
                        "details_product": "' . $details_product . '",
                        "description_product": "' . $description_product . '",
                        "gallery_product": "' . $gallery_product . '",
                        "top_banner_product":"' . $top_banner_product . '",
                        "default_banner_product":"' . $default_banner_product . '",
                        "horizontal_slider_product":"' . $horizontal_slider_product . '",
                        "vertical_slider_product":"' . $vertical_slider_product . '",
                        "video_product":"' . $video_product . '",
                        "tags_product":"' . $tags_product . '",
                        "views_product":"' . $views_product . '",
                        "sales_product":"' . $sales_product . '",
                        "reviews_product":"' . $reviews_product . '",
                        "date_created_product":"' . $date_created_product . '"
                    },';
                }

                $data_json = substr($data_json, 0, -1);

                $data_json .= ']}';

                echo $data_json;
            }
        }
    }
}

// Activar funcion datatable

$data = new DataTableController();
$data->data_products();
