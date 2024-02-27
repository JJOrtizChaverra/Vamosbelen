<?php

ob_start();
session_start();

// Traer el dominio principal

$path = TemplateController::path();


// Traer el total de productos de la base de datos

$url = CurlController::api() . "products?select=id_product";
$method = "GET";
$fields = array();
$header = array();

$data_products = CurlController::request($url, $method, $fields, $header);

if ($data_products->status == 200) {
    $total_products = $data_products->total;
} else {
    $total_products = 0;
}


// Capturar las rutas de la url

$routes_array = explode("/", $_SERVER['REQUEST_URI']);
$routes_array = array_filter($routes_array);

if (!empty($routes_array[1])) {
    $url_get = explode("?", array_filter($routes_array)[1]);
    $url_params = explode("&", $url_get[0]);
}

if (!empty($url_params[0])) {
    // Filtrar las categorias con el parametro de la url

    $url = CurlController::api() . "categories?linkTo=url_category&equalTo=$url_params[0]&select=url_category";
    $method = "GET";
    $fields = array();
    $header = array();

    $url_categories = CurlController::request($url, $method, $fields, $header);

    if ($url_categories->status == 404) {

        // Filtrar las subcategorias con el parametro de la url
        $url = CurlController::api() . "subcategories?linkTo=url_subcategory&equalTo=$url_params[0]&select=url_subcategory";
        $method = "GET";
        $fields = array();
        $header = array();

        $url_subcategories = CurlController::request($url, $method, $fields, $header);

        if ($url_subcategories->status == 404) {
            // Filtrar productos con el parametro de la url
            $url = CurlController::api() . "relations?rel=products,categories&type=product,category&linkTo=url_product&equalTo=$url_params[0]&select=url_product,name_product,url_category,summary_product,image_product,tags_product";
            $method = "GET";
            $fields = array();
            $header = array();

            $url_product = CurlController::request($url, $method, $fields, $header);


            if ($url_product->status == 404) {

                // Traer todos los productos de la tienda

                $select = "*";

                $url = CurlController::api() . "relations?rel=products,categories,subcategories,stores&type=product,category,subcategory,store&linkTo=url_store&equalTo=$url_params[0]&select=$select";
                $method = "GET";
                $fields = array();
                $header = array();

                $url_store = CurlController::request($url, $method, $fields, $header);

                if ($url_store->status == 404) {
                    // Validar si hay parametros de paginacion

                    if (isset($url_params[1])) {
                        if (is_numeric($url_params[1])) {
                            $start_at = ($url_params[1] * 6) - 6;
                        } else {
                            $start_at = null;
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
                                $order_by = "id_product";
                                $order_mode = "DESC";
                            }
                        } else {
                            $order_by = "id_product";
                            $order_mode = "DESC";
                        }
                    } else {
                        $order_by = "id_product";
                        $order_mode = "DESC";
                    }

                    $array_link_to = ["name_product", "title_list_product", "tags_product", "summary_product", "url_store", "name_store"];
                    $str_select = "url_product,url_category,image_product,name_product,stock_product,offer_product,url_store,name_store,reviews_product,price_product,views_category,name_category,id_category,views_subcategory,name_subcategory,id_subcategory,summary_product";

                    foreach ($array_link_to as $key => $value) {
                        // Filtrar tabla producto por el nombre con el parametro url de busquedad

                        $url = CurlController::api() . "relations?rel=products,categories,subcategories,stores&type=product,category,subcategory,store&linkTo=$value,approval_product,state_product&search=$url_params[0],approved,show&orderBy=$order_by&orderMode=$order_mode&startAt=$start_at&endAt=12&select=$str_select";
                        $method = "GET";
                        $fields = array();
                        $header = array();

                        $url_search = CurlController::request($url, $method, $fields, $header);

                        if ($url_search->status != 404) {

                            $url = CurlController::api() . "relations?rel=products,categories,subcategories,stores&type=product,category,subcategory,store&linkTo=$value,approval_product,state_product&search=$url_params[0],approved,show&select=id_product";

                            $total_search = CurlController::request($url, $method, $fields, $header)->total;
                            break;
                        }
                    }
                }
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta name="description" content="">

    <!-- Metadatos -->

    <?php

    if (!empty($url_params[0])) {

        if (isset($url_product->status) && $url_product->status == 200) {

            $name = $url_product->result[0]->name_product;
            $title = "Vamosbelén | " . $url_product->result[0]->name_product;
            $description = "";

            foreach (json_decode($url_product->result[0]->summary_product, true) as $key => $value) {

                $description .= $value . ", ";
            }

            $description = substr($description, 0, -2);

            $keywords = "";

            foreach (json_decode($url_product->result[0]->tags_product, true) as $key => $value) {

                $keywords .= $value . ", ";
            }

            $keywords = substr($keywords, 0, -2);

            $image = $path . "views/img/products/" . $url_product->result[0]->url_category . "/" . $url_product->result[0]->image_product;

            $url = $path . $url_product->result[0]->url_product;
        } else {

            $name = "Vamosbelén";
            $title = "Vamosbelén | Home";
            $description = "Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Cras mattis consectetur purus sit amet fermentum.";
            $keywords = "Vamosbelén, Consumer Electric, Clothing and Apparel, Home, Garden and Kitchen, Health and Beauty, Jewelry and Watches, Computer and Technology";
            $image = $path . "views/img/bg/about-us.jpg";
            $url = $path;
        }
    } else {

        $name = "Vamosbelén";
        $title = "Vamosbelén | Home";
        $description = "Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Cras mattis consectetur purus sit amet fermentum.";
        $keywords = "Vamosbelén, Consumer Electric, Clothing and Apparel, Home, Garden and Kitchen, Health and Beauty, Jewelry and Watches, Computer and Technology";
        $image = $path . "views/img/bg/about-us.jpg";
        $url = $path;
    }



    ?>

    <title><?php echo $title ?></title>

    <meta name="description" content="<?php echo $description ?>">
    <meta name="keywords" content="<?php echo $keywords ?>">

    <!-- Marcado OPEN GRAPH para facebook -->

    <meta property="og:site_name" content="<?php echo $name ?>">
    <meta property="og:title" content="<?php echo $title ?>">
    <meta property="og:description" content="<?php echo $description ?>">
    <meta property="og:type" content="Type">
    <meta property="og:image" content="<?php echo $image ?>">
    <meta property="og:url" content="<?php echo $url ?>">


    <!-- Marcado TWITTER -->

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@vamosbelen">
    <meta name="twitter:creator" content="@vamosbelen">
    <meta name="twitter:title" content="<?php echo $title ?>">
    <meta name="twitter:description" content="<?php echo $description ?>">
    <meta name="twitter:image" content="<?php echo $image ?>">
    <meta name="twitter:image:width" content="800">
    <meta name="twitter:image:height" content="418">
    <meta name="twitter:image:alt" content="<?php echo $description ?>">


    <!-- Marcado GOOGLE -->

    <meta itemprop="name" content="<?php echo $title ?>">
    <meta itemprop="url" content="<?php echo $url ?>">
    <meta itemprop="description" content="<?php echo $description ?>">
    <meta itemprop="image" content="<?php echo $image ?>">


    <base href="views/">

    <link rel="icon" href="img/template/icono.png">

    <!--=====================================
	CSS
	======================================-->

    <!-- google font -->
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,600,700&display=swap" rel="stylesheet">

    <!-- font awesome -->
    <link rel="stylesheet" href="css/plugins/fontawesome.min.css">

    <!-- linear icons -->
    <link rel="stylesheet" href="css/plugins/linearIcons.css">

    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Owl Carousel -->
    <link rel="stylesheet" href="css/plugins/owl.carousel.css">

    <!-- Slick -->
    <link rel="stylesheet" href="css/plugins/slick.css">

    <!-- Light Gallery -->
    <link rel="stylesheet" href="css/plugins/lightgallery.min.css">

    <!-- Font Awesome Start -->
    <link rel="stylesheet" href="css/plugins/fontawesome-stars.css">

    <!-- jquery Ui -->
    <link rel="stylesheet" href="css/plugins/jquery-ui.min.css">

    <!-- Select 2 -->
    <link rel="stylesheet" href="css/plugins/select2.min.css">

    <!-- Scroll Up -->
    <link rel="stylesheet" href="css/plugins/scrollUp.css">

    <!-- DataTable -->
    <link rel="stylesheet" href="css/plugins/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="css/plugins/responsive.bootstrap.datatable.min.css">

    <!-- Placeholder loading -->
    <!-- https://github.com/zalog/placeholder-loading?tab=readme-ov-file -->
    <link rel="stylesheet" href="https://unpkg.com/placeholder-loading@0.6.0/dist/css/placeholder-loading.min.css">

    <!-- Notie alert -->
    <link rel="stylesheet" type="text/css" href="css/plugins/notie.min.css">

    <!-- include summernote css -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

    <!-- Tagsinputs jquery -->
    <link rel="stylesheet" href="css/plugins/tagsinput.css">

    <!-- Dropzone -->
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

    <!-- estilo principal -->
    <link rel="stylesheet" href="css/style.css">

    <!-- Market Place 4 -->
    <link rel="stylesheet" href="css/market-place-4.css">

    <!--=====================================
	PLUGINS JS
	======================================-->

    <!-- jQuery library -->
    <script src="js/plugins/jquery-1.12.4.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

    <!-- Owl Carousel -->
    <script src="js/plugins/owl.carousel.min.js"></script>

    <!-- Images Loaded -->
    <script src="js/plugins/imagesloaded.pkgd.min.js"></script>

    <!-- Masonry -->
    <script src="js/plugins/masonry.pkgd.min.js"></script>

    <!-- Isotope -->
    <script src="js/plugins/isotope.pkgd.min.js"></script>

    <!-- jQuery Match Height -->
    <script src="js/plugins/jquery.matchHeight-min.js"></script>

    <!-- Slick -->
    <script src="js/plugins/slick.min.js"></script>

    <!-- jQuery Barrating -->
    <script src="js/plugins/jquery.barrating.min.js"></script>

    <!-- Slick Animation -->
    <script src="js/plugins/slick-animation.min.js"></script>

    <!-- Light Gallery -->
    <script src="js/plugins/lightgallery-all.min.js"></script>
    <script src="js/plugins/lg-thumbnail.min.js"></script>
    <script src="js/plugins/lg-fullscreen.min.js"></script>
    <script src="js/plugins/lg-pager.min.js"></script>

    <!-- jQuery UI -->
    <script src="js/plugins/jquery-ui.min.js"></script>

    <!-- Sticky Sidebar -->
    <script src="js/plugins/sticky-sidebar.min.js"></script>

    <!-- Slim Scroll -->
    <script src="js/plugins/jquery.slimscroll.min.js"></script>

    <!-- Select 2 -->
    <script src="js/plugins/select2.full.min.js"></script>

    <!-- Scroll Up -->
    <script src="js/plugins/scrollUP.js"></script>

    <!-- DataTable -->
    <script src="js/plugins/jquery.dataTables.min.js"></script>
    <script src="js/plugins/dataTables.bootstrap4.min.js"></script>
    <script src="js/plugins/dataTables.responsive.min.js"></script>

    <!-- Chart -->
    <script src="js/plugins/Chart.min.js"></script>

    <!-- Twbs Pagination -->
    <script src="js/plugins/twbs-pagination.min.js"></script>

    <!-- md5 -->
    <script src="js/plugins/md5.min.js"></script>

    <!-- Notie alert -->
    <script src="https://unpkg.com/notie"></script>

    <!-- Sweet alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Paypal -->
    <!-- https://developer.paypal.com/docs/checkout/ -->
    <script src="https://www.paypal.com/sdk/js?client-id=AUr_9kaW80KRXbS6-rCEhcTvosH-IhsLusTxAVv-4_TBlYdiBV2EuT_Y3UnrvAO1gnHEkZRNydPtoJwz"></script>

    <!-- Mercado pago -->
    <script src="https://sdk.mercadopago.com/js/v2"></script>

    <!-- <script src="https://www.paypal.com/sdk/js?client-id=AUr_9kaW80KRXbS6-rCEhcTvosH-IhsLusTxAVv-4_TBlYdiBV2EuT_Y3UnrvAO1gnHEkZRNydPtoJwz&currency=USD"></script> -->

    <!-- <script src="https://www.paypal.com/sdk/js?client-id=AUr_9kaW80KRXbS6-rCEhcTvosH-IhsLusTxAVv-4_TBlYdiBV2EuT_Y3UnrvAO1gnHEkZRNydPtoJwz&merchant-id={SELLER_PAYER_ID}&components=buttons" data-partner-attribution-id="{PARTNER_BN_CODE}"></script> -->

    <!-- include summernote js -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

    <!-- Tagsinput jquery -->
    <script src="js/plugins/tagsinput.js"></script>

    <!-- Dropzone -->
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

    <!-- Shape Share -->
    <script src="js/plugins/shape.share.js"></script>

    <script src="js/head.js"></script>

</head>

<body>

    <div id="ytWidget" class="d-none"></div>

    <script src="https://translate.yandex.net/website-widget/v1/widget.js?widgetId=ytWidget&pageLang=en&widgetTheme=light&autoMode=false" type="text/javascript"></script>

    <!--=====================================
	Header Promotion
	======================================-->

    <?php include "modules/top-banner.php"; ?>

    <!--=====================================
	Header
	======================================-->

    <?php include "modules/header.php"; ?>

    <!--=====================================
	Header Mobile
	======================================-->

    <?php include "modules/header-mobile.php"; ?>

    <!--=====================================
    Navigation Mobile
    ======================================-->

    <div class="navigation--list">
        <div class="navigation__content">



            <?php if (isset($_SESSION['user'])) : ?>

                <?php if ($_SESSION['user']->method_user == "direct") : ?>

                    <?php if ($_SESSION['user']->picture_user == null) : ?>

                        <a class="navigation__item" href="<?php echo $path; ?>account&wishlist">
                            <img class="rounded-circle" style="width: 50px;" src="img/users/default/default.png" alt="profile">
                        </a>

                    <?php else : ?>

                        <a class="navigation__item" href="<?php echo $path; ?>account&wishlist">
                            <img src="img/users/<?php echo $_SESSION['user']->id_user; ?>/<?php echo $_SESSION['user']->picture_user; ?>" class="rounded-circle" style="width: 50px;" alt="profile">
                        </a>

                    <?php endif ?>

                <?php else : ?>

                    <?php if (explode("/", $_SESSION['user']->picture_user)[0] == "https:") : ?>

                        <a class="navigation__item" href="<?php echo $path; ?>account&wishlist">
                            <img class="rounded-circle" style="width: 50px;" src="<?php echo $_SESSION['user']->picture_user; ?>" alt="profile">
                        </a>

                    <?php else : ?>

                        <a class="navigation__item" href="<?php echo $path; ?>account&wishlist">
                            <img src="img/users/<?php echo $_SESSION['user']->id_user; ?>/<?php echo $_SESSION['user']->picture_user; ?>" class="rounded-circle" style="width: 50px;" alt="profile">
                        </a>

                    <?php endif ?>

                <?php endif ?>

            <?php else : ?>

                <a class="navigation__item" href="<?php echo $path; ?>account&login#header">
                    <i class="icon-user"></i><span> Account</span>
                </a>

            <?php endif ?>

            </a>


            <a class="navigation__item ps-toggle--sidebar" href="#navigation-mobile">
                <i class="icon-list4"></i><span> Categories</span>
            </a>
            <a class="navigation__item ps-toggle--sidebar" href="#search-sidebar">
                <i class="icon-magnifier"></i><span> Search</span>
            </a>
            <a class="navigation__item ps-toggle--sidebar" href="#cart-mobile">
                <i class="icon-bag2"></i><span> Cart</span>
            </a>
        </div>
    </div>

    <!--=====================================
	Pages
	======================================-->

    <?php

    if (!empty($url_params[0])) {

        if ($url_params[0] == "account" || $url_params[0] == "shopping-cart" || $url_params[0] == "checkout" || $url_params[0] == "become-vendor" || $url_params[0] == "store-list") {
            include "pages/$url_params[0]/$url_params[0].php";
        } else if ($url_categories->status == 200 || $url_subcategories->status == 200) {
            include "pages/products/products.php";
        } else if ($url_product->status == 200) {
            include "pages/product/product.php";
        } else if ($url_store->status == 200) {
            include "pages/vendor-store/vendor-store.php";
        } else if ($url_search->status == 200) {
            include "pages/search/search.php";
        } else {
            include "pages/404/404.php";
        }
    } else {
        include "pages/home/home.php";
    }

    ?>

    <!--=====================================
	Newletter
	======================================-->

    <?php include "modules/newletter.php"; ?>

    <!--=====================================
	Footer
	======================================-->

    <?php include "modules/footer.php"; ?>

    <!--=====================================
    PopUp
    ======================================-->

    <div class="ps-site-overlay"></div>

    <div class="ps-popup" id="subscribe" data-time="500">
        <div class="ps-popup__content bg--cover" data-background="img/bg/subscribe.jpg" style="background: url(img/bg/subscribe.jpg);"><a class="ps-popup__close" href="#"><i class="icon-cross"></i></a>
            <form class="ps-form--subscribe-popup" action="index.html" method="get">
                <div class="ps-form__content">
                    <h4>Get <strong>25%</strong> Discount</h4>
                    <p>Subscribe to the Martfury mailing list <br> to receive updates on new arrivals, special offers <br> and our promotions.</p>
                    <div class="form-group">
                        <input class="form-control" type="text" placeholder="Email Address" required="">
                        <button class="ps-btn">Subscribe</button>
                    </div>
                    <div class="ps-checkbox">
                        <input class="form-control" type="checkbox" id="not-show" name="not-show">
                        <label for="not-show">Don't show this popup again</label>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!--=====================================
	JS PERSONALIZADO
	======================================-->

    <script src="js/main.js"></script>

</body>

</html>