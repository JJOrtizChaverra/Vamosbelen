<?php

// Recibir variable GET de cupon y convertirla en COOKIE

if (isset($_GET['coupon'])) {

    if (isset($_COOKIE['coupons-mp'])) {

        $array_coupon = json_decode($_COOKIE['coupons-mp'], true);

        foreach ($array_coupon as $key => $value) {

            if ($value != $_GET['coupon']) {
                array_push($array_coupon, $_GET['coupon']);
            }
        }

        setcookie("coupons-mp", json_encode($array_coupon), (time() + 3600 * 24 * 7));
    } else {
        $array_coupon = array($_GET['coupon']);
        setcookie('coupons-mp', json_encode($array_coupon), (time() + 3600 * 24 * 7));
    }
}

// Traer toda la informacion del producto

$select = "id_product,email_store,url_category,url_product,image_product,name_product,offer_product,price_product,name_category,url_subcategory,name_subcategory,gallery_product,reviews_product,url_store,name_store,stock_product,summary_product,video_product,specifications_product,title_list_product,views_product,id_store,tags_product,description_product,details_product,abstract_store,logo_store";

$url = CurlController::api() . "relations?rel=products,categories,subcategories,stores&type=product,category,subcategory,store&linkTo=url_product,approval_product,state_product&equalTo=$url_params[0],approved,show&select=$select";
$method = "GET";
$fields = array();
$header = array();

$product = CurlController::request($url, $method, $fields, $header)->result[0];

if ($product == "N") {
    echo "<script>sweetAlert('error', 'This product is not available', '$path')</script>";
    return;
}

// Actualizar las vistas del producto

$id = $product->id_product;
$views = $product->views_product + 1;

$url = CurlController::api() . "products?id=$id&nameId=id_product&token=no&except=views_product";
$method = "PUT";
$fields = "views_product=$views";
$header = array();

$update_views_products = CurlController::request($url, $method, $fields, $header);

?>

<!--=====================================
Preload
======================================-->

<div id="loader-wrapper">
    <img src="img/template/loader.jpg">
    <div class="loader-section section-left"></div>
    <div class="loader-section section-right"></div>
</div>

<!--=====================================
Call to action
======================================-->

<?php include "modules/call-to-action.php"; ?>

<!--=====================================
Breadcrumb
======================================-->

<?php include "modules/breadcrumb.php" ?>

<!--=====================================
Product Content
======================================-->

<div class="ps-page--product">

    <div class="ps-container">

        <!--=====================================
    		Product Container
    		======================================-->

        <div class="ps-page__container">

            <!--=====================================
    			Left Column
    			======================================-->

            <div class="ps-page__left">

                <div class="ps-product--detail ps-product--fullwidth">

                    <!--=====================================
    					Product Header
    					======================================-->

                    <div class="ps-product__header">

                        <!--=====================================
    					Gallery
    					======================================-->

                        <?php include "modules/gallery.php"; ?>

                        <!--=====================================
    					Product Info
    					======================================-->

                        <?php include "modules/product-info.php"; ?>

                    </div> <!-- End Product header -->

                    <!--=====================================
    				Product Content
    				======================================-->

                    <div class="ps-product__content ps-tab-root">

                        <!--=====================================
    				    Bought Together
    				    ======================================-->

                        <?php include "modules/bought-together.php" ?>

                        <!--=====================================
    				    Menu
    				    ======================================-->

                        <?php include "modules/menu.php"; ?>

                    </div><!--  End product content -->

                </div>

            </div><!-- End Left Column -->

            <!--=====================================
    	    Right Column
    	    ======================================-->

            <div class="ps-page__right d-block d-sm-none d-xl-block">


                <?php include "modules/right-column.php"; ?>

            </div><!-- End Right Column -->

        </div><!-- End Product Container -->



        <!--=====================================
		Related products
		======================================-->

        <?php include "modules/related-products.php"; ?>

    </div>

</div><!-- End Product Content -->