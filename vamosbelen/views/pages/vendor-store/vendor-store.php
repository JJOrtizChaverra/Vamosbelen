<?php

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


// Traer la informacion de los productos y tiendas

$select = "url_product,url_category,image_product,name_product,stock_product,offer_product,url_store,name_store,reviews_product,price_product,views_category,name_category,id_category,summary_product";

$end_at = 8;

$url = CurlController::api() . "relations?rel=products,categories,stores&type=product,category,store&linkTo=url_store,approval_product,state_product&equalTo=$url_params[0],approved,show&orderBy=$order_by&orderMode=$order_mode&startAt=$start_at&endAt=$end_at&select=$select";
$method = "GET";
$fields = array();
$header = array();

$showcase_products = CurlController::request($url, $method, $fields, $header)->result;

// Traer los productos mas vendidos de la tienda

$select_best_seller = "url_product,url_category,image_product,name_product,stock_product,offer_product,url_store,name_store,reviews_product,price_product,views_category,name_category,id_category,summary_product";

$url = CurlController::api() . "relations?rel=products,categories,stores&type=product,category,store&linkTo=url_store,approval_product,state_product&equalTo=$url_params[0],approved,show&orderBy=sales_product&orderMode=DESC&startAt=0&endAt=6&select=$select_best_seller";
$method = "GET";
$fields = array();
$header = array();

$best_seller_items = CurlController::request($url, $method, $fields, $header)->result;

// Traer el total de productos de la tienda

$url = CurlController::api() . "relations?rel=products,stores&type=product,store&linkTo=url_store,approval_product,state_product&equalTo=$url_params[0],approved,show&select=id_store";
$method = "GET";
$fields = array();
$header = array();

$data_products = CurlController::request($url, $method, $fields, $header);

if ($data_products->status == 200) {
    $total_products = $data_products->total;
} else {
    $total_products = 0;
}

?>

<!--=====================================
Breadcrumb
======================================-->

<?php include "modules/breadcrumb.php"; ?>

<!--=====================================
Vendor Store
======================================-->
<div class="ps-vendor-store">

    <div class="container">

        <div class="ps-section__container">

            <!--=====================================
            Store info
            ======================================-->

            <?php include "modules/store-info.php"; ?>

            <?php if ($total_products > 0) : ?>

                <div class="ps-section__right">
                    <!-- <div class="ps-block--vendor-filter">
                        <div class="ps-block__right">
                            <form class="ps-form--search" action="index.html" method="get">
                                <input class="form-control" type="text" placeholder="Search in this shop">
                                <button><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                    </div> -->

                    <!--=====================================
                    Best seller items
                    ======================================-->

                    <?php include "modules/best-seller-items.php"; ?>


                    <!--=====================================
                    Show case
                    ======================================-->

                    <?php include "modules/show-case.php"; ?>

                <?php else : ?>

                    <h1 class="text-center">Not found products</h1>

                <?php endif ?>

                </div>
        </div>
    </div>
</div>