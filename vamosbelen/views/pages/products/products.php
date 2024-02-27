<?php

$select_category = "url_product,url_category,image_product,name_product,stock_product,offer_product,url_store,name_store,reviews_product,price_product,views_category,name_category,id_category,summary_product";

$select_subcategory = "url_product,url_category,image_product,name_product,stock_product,offer_product,url_store,name_store,reviews_product,price_product,views_subcategory,name_subcategory,id_subcategory,summary_product";

// Validar si hay parametros de paginacion

if (isset($url_params[1])) {
    if (is_numeric($url_params[1])) {
        $start_at = ($url_params[1] * 6) - 6;
    } else {
        echo "
        <script>
            window.location = '$path$url_params[0]';
        </script>
        ";
    }
} else {
    $start_at = 0;
}

// Validar si hay parametros de orden

if (isset($url_params[2])) {
    if (is_string($url_params[2])) {
        if ($url_params[2] == "new") {
            $order_by = "id_product";
            $order_mode = "DESC";
        } else if ($url_params[2] == "latest") {
            $order_by = "id_product";
            $order_mode = "ASC";
        } else if ($url_params[2] == "low") {
            $order_by = "price_product";
            $order_mode = "ASC";
        } else if ($url_params[2] == "high") {
            $order_by = "price_product";
            $order_mode = "DESC";
        } else {
            echo "
            <script>
                window.location = '$path$url_params[0]';
            </script>
            ";
        }
    } else {
        echo "
        <script>
            window.location = '$path$url_params[0]';
        </script>
        ";
    }
} else {
    $order_by = "id_product";
    $order_mode = "DESC";
}

// Traemos los productos recien agregados a la categoria

$url = CurlController::api() . "relations?rel=products,categories,stores&type=product,category,store&linkTo=url_category,approval_product,state_product&equalTo=$url_params[0],approved,show&orderBy=$order_by&orderMode=$order_mode&startAt=$start_at&endAt=6&select=$select_category";
$method = "GET";
$fields = array();
$header = array();

$showcase_products = CurlController::request($url, $method, $fields, $header)->result;

// Traemos los productos recien agregados a la subcategoria

if ($showcase_products == "Not found") {

    $url = CurlController::api() . "relations?rel=products,categories,subcategories,stores&type=product,category,subcategory,store&linkTo=url_subcategory,approval_product,state_product&equalTo=$url_params[0],approved,show&orderBy=$order_by&orderMode=$order_mode&startAt=$start_at&endAt=6&select=$select_subcategory";
    $method = "GET";
    $fields = array();
    $header = array();

    $showcase_products = CurlController::request($url, $method, $fields, $header)->result;

    // Traer el total de productos de subcategorias

    $url = CurlController::api() . "relations?rel=products,subcategories,stores&type=product,subcategory,store&linkTo=url_subcategory,approval_product,state_product&equalTo=$url_params[0],approved,show&select=id_subcategory";
    $method = "GET";
    $fields = array();
    $header = array();

    $data_products = CurlController::request($url, $method, $fields, $header);

    if ($data_products->status == 200) {
        $total_products = $data_products->total;
    } else {
        $total_products = 0;
        echo "<script>sweetAlert('error', 'There are no products for this category.', '$path')</script>";
        return;
    }


    // Actualizar las vistas de la subcategoria

    $id = $showcase_products[0]->id_subcategory;
    $views = $showcase_products[0]->views_subcategory + 1;

    $url = CurlController::api() . "subcategories?id=$id&nameId=id_subcategory&token=no&except=views_subcategory";
    $method = "PUT";
    $fields = "views_subcategory=$views";
    $header = array();

    $update_views_subcategory = CurlController::request($url, $method, $fields, $header);
} else {

    // Traer el total de productos de categorias

    $url = CurlController::api() . "relations?rel=products,categories,stores&type=product,category,store&linkTo=url_category,approval_product,state_product&equalTo=$url_params[0],approved,show&select=id_category";
    $method = "GET";
    $fields = array();
    $header = array();

    $data_products = CurlController::request($url, $method, $fields, $header);

    if ($data_products->status == 200) {
        $total_products = $data_products->total;
    } else {
        $total_products = 0;
        echo "<script>sweetAlert('error', 'There are no products for this category.', '$path')</script>";
        return;
    }

    // Actualizar las vistas de la categoria

    $id = $showcase_products[0]->id_category;
    $views = $showcase_products[0]->views_category + 1;

    $url = CurlController::api() . "categories?id=$id&nameId=id_category&token=no&except=views_category";
    $method = "PUT";
    $fields = "views_category=$views";
    $header = array();

    $update_views_category = CurlController::request($url, $method, $fields, $header);
}


// Traer los productos mas vendidos de la categoria

$url = CurlController::api() . "relations?rel=products,categories,stores&type=product,category,store&linkTo=url_category,approval_product,state_product&equalTo=$url_params[0],approved,show&orderBy=sales_product&orderMode=DESC&startAt=0&endAt=8&select=$select_category";
$method = "GET";
$fields = array();
$header = array();

$best_sales_items = CurlController::request($url, $method, $fields, $header)->result;

// Traemos los productos mas vendidos de la subcategoria

if ($best_sales_items == "Not found") {

    $url = CurlController::api() . "relations?rel=products,categories,subcategories,stores&type=product,category,subcategory,store&linkTo=url_subcategory,approval_product,state_product&equalTo=$url_params[0],approved,show&orderBy=sales_product&orderMode=DESC&startAt=0&endAt=8&select=$select_subcategory";
    $method = "GET";
    $fields = array();
    $header = array();

    $best_sales_items = CurlController::request($url, $method, $fields, $header)->result;
}


// Traer los productos mas vistos de la categoria

$url = CurlController::api() . "relations?rel=products,categories,stores&type=product,category,store&linkTo=url_category,approval_product,state_product&equalTo=$url_params[0],approved,show&orderBy=views_product&orderMode=DESC&startAt=0&endAt=8&select=$select_category";
$method = "GET";
$fields = array();
$header = array();

$recommended_items = CurlController::request($url, $method, $fields, $header)->result;

// Traemos los productos mas vistos de la subcategoria

if ($recommended_items == "Not found") {
    $url = CurlController::api() . "relations?rel=products,categories,subcategories,stores&type=product,category,subcategory,store&linkTo=url_subcategory,approval_product,state_product&equalTo=$url_params[0],approved,show&orderBy=views_product&orderMode=DESC&startAt=0&endAt=8&select=$select_subcategory";
    $method = "GET";
    $fields = array();
    $header = array();

    $recommended_items = CurlController::request($url, $method, $fields, $header)->result;
}

?>

<!--=====================================
    Breadcrumb
    ======================================-->

<?php include "modules/breadcrumb.php"; ?>

<!--=====================================
    Categories Content
    ======================================-->

<div class="container-fuid bg-white my-4">

    <div class="container">

        <!--=====================================
			Layout Categories
			======================================-->

        <div class="ps-layout--shop">

            <section>

                <!--=====================================
    				Best Sale Items
    				======================================-->

                <?php include "modules/best-sales-items.php"; ?>

                <!--=====================================
    				Recommended Items
    				======================================-->

                <?php include "modules/recommended-items.php"; ?>

                <!--=====================================
    				Products found
    				======================================-->

                <?php include "modules/show-case.php"; ?>

            </section>

        </div><!-- End Layout Categories -->

    </div><!-- End Container -->

</div><!-- End Container Fluid -->