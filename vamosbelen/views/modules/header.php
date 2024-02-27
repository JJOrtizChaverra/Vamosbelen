<?php

// Traer el listado de categorias

$url = CurlController::api() . "categories?select=id_category,url_category,name_category,icon_category,title_list_category";
$method = "GET";
$fields = array();
$header = array();

$menu_categories = CurlController::request($url, $method, $fields, $header)->result;

// Traer la lista de deseos

$wishlist = array();

if (isset($_SESSION['user'])) {
    $url = CurlController::api() . "users?linkTo=id_user&equalTo={$_SESSION['user']->id_user}&select=wishlist_user";
    $response = CurlController::request($url, $method, $fields, $header)->result[0];

    if (!empty($response->wishlist_user)) {
        $wishlist = json_decode($response->wishlist_user, true);
    }
}

?>

<header class="header header--standard header--market-place-4" id="header" data-sticky="true">

    <!--=====================================
		Header TOP
		======================================-->

    <div class="header__top">

        <div class="container">

            <!--=====================================
				Social 
				======================================-->

            <div class="header__left">
                <ul class="d-flex justify-content-center">
                    <li><a href="#" target="_blank"><i class="fab fa-facebook-f mr-4"></i></a></li>
                    <li><a href="#" target="_blank"><i class="fab fa-instagram mr-4"></i></a></li>
                    <li><a href="#" target="_blank"><i class="fab fa-twitter mr-4"></i></a></li>
                    <li><a href="#" target="_blank"><i class="fab fa-youtube mr-4"></i></a></li>
                </ul>
            </div>

            <!--=====================================
			Contact & lenguage 
			======================================-->

            <div class="header__right">
                <ul class="header__top-links d-flex justify-content-between align-items-center">
                    <li><a href="<?php echo TemplateController::path() ?>become-vendor">Sell ​​on the Vamosbelén</a></li>
                    <li><a href="<?php echo TemplateController::path() ?>store-list">Store List</a></li>
                    <li><i class="icon-telephone"></i> Hotline:<strong> 1-800-234-5678</strong></li>
                    <li>
                        <div class="ps-dropdown language">
                            <a class="btn text-language">
                                Language
                            </a>
                            <ul class="ps-dropdown-menu">
                                <li>
                                    <a class="btn active" onclick="changeLang('en')">
                                        <img src="img/template/en.png" alt="english"> English
                                    </a>
                                    <a class="btn" onclick="changeLang('es')">
                                        <img src="img/template/es.png" alt="spanish"> Spanish
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>

        </div><!-- End Container -->

    </div><!-- Header Top -->

    <!--=====================================
		Header Content
		======================================-->

    <div class="header__content">

        <div class="container">

            <div class="header__content-left">

                <!--=====================================
					Logo
					======================================-->

                <a class="ps-logo" href="/">
                    <img src="img/template/logo.png" alt="logo">
                </a>

                <!--=====================================
					Menú
					======================================-->

                <div class="menu--product-categories">

                    <div class="menu__toggle">
                        <i class="icon-menu"></i>
                        <span> Shop by Department</span>
                    </div>

                    <div class="menu__content">
                        <ul class="menu--dropdown">
                            <?php foreach ($menu_categories as $key => $value) : ?>
                                <li class="menu-item-has-children has-mega-menu">
                                    <a href="<?php echo $path . $value->url_category ?>"><i class="<?php echo $value->icon_category; ?>"></i> <?php echo $value->name_category; ?></a>
                                    <div class="mega-menu">
                                        <?php

                                        // Traemos el listado de titulos
                                        $title_list = json_decode($value->title_list_category);

                                        ?>

                                        <?php foreach ($title_list as $key => $value) : ?>
                                            <div class="mega-menu__column">
                                                <h4><?php echo $value ?><span class="sub-toggle"></span></h4>
                                                <ul class="mega-menu__list">

                                                    <?php
                                                    // Traer las subcategorias

                                                    $url = CurlController::api() . "subcategories?linkTo=title_list_subcategory&equalTo=" . rawurlencode($value) . "&select=url_subcategory,name_subcategory";
                                                    $method = "GET";
                                                    $fields = array();
                                                    $header = array();

                                                    $menu_subcategories = CurlController::request($url, $method, $fields, $header)->result;
                                                    ?>

                                                    <?php foreach ($menu_subcategories as $key => $value) : ?>

                                                        <li><a href="<?php echo $path . $value->url_subcategory ?>"><?php echo $value->name_subcategory ?></a></li>

                                                    <?php endforeach ?>

                                                </ul>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                </li>
                            <?php endforeach ?>


                        </ul>

                    </div>

                </div><!-- End menu-->

            </div><!-- End Header Content Left-->

            <!--=====================================
				Search
				======================================-->

            <div class="header__content-center">
                <form class="ps-form--quick-search">

                    <input class="form-control input-search" type="text" placeholder="I'm looking for...">
                    <button type="button" class="btn-search" path="<?php echo $path; ?>">Search</button>
                </form>
            </div>

            <div class="header__content-right">

                <div class="header__actions">

                    <!--=====================================
						Wishlist
						======================================-->

                    <a class="header__extra" href="<?php echo $path; ?>account&wishlist">
                        <i class="icon-heart"></i>
                        <span>
                            <i class="total-wishlist"><?php echo count($wishlist); ?></i>
                        </span>
                    </a>

                    <!--=====================================
					Cart
					======================================-->

                    <?php

                    $total_price_sc = 0;

                    if (isset($_COOKIE['list-sc'])) {

                        $shopping_cart = json_decode($_COOKIE['list-sc'], true);
                        $total_sc = count($shopping_cart);
                    } else {
                        $total_sc = 0;
                    }

                    ?>

                    <div class="ps-cart--mini">

                        <a class="header__extra">
                            <i class="icon-bag2"></i><span><i><?php echo $total_sc; ?></i></span>
                        </a>

                        <?php if ($total_sc > 0) : ?>

                            <div class="ps-cart__content">

                                <div class="ps-cart__items">

                                    <?php foreach ($shopping_cart as $key => $value) : ?>

                                        <?php

                                        // Traer productos del carrito de compras

                                        $select = "url_product,name_store,url_category,name_product,image_product,price_product,offer_product,shipping_product";

                                        $url = CurlController::api() . "relations?rel=products,categories,stores&type=product,category,store&linkTo=url_product&equalTo={$value['product']}&select=$select";
                                        $method = "GET";
                                        $fields = array();
                                        $header = array();

                                        $item = CurlController::request($url, $method, $fields, $header)->result[0];

                                        ?>

                                        <div class="ps-product--cart-mobile">

                                            <div class="ps-product__thumbnail">
                                                <a href="<?php echo $path . $item->url_product; ?>">
                                                    <img src="img/products/<?php echo $item->url_category; ?>/<?php echo $item->image_product; ?>" alt="<?php echo $item->name_product; ?>">
                                                </a>
                                            </div>

                                            <div class="ps-product__content">

                                                <!-- Eliminar el producto del carrito -->
                                                <a class="ps-product__remove btn" onclick="removeShoppingCart('<?php echo $item->url_product; ?>', '<?php echo $_SERVER['REQUEST_URI'] ?>')">
                                                    <i class="icon-cross"></i>
                                                </a>

                                                <!-- Nombre del producto -->
                                                <a href="<?php echo $path . $item->url_product; ?>"><?php echo $item->name_product; ?></a>

                                                <!-- Tienda del producto -->
                                                <p class="mb-0"><strong>Sold by:</strong> <span class="text-uppercase"><?php echo $item->name_store; ?></span></p>

                                                <!-- Precio de envio de producto -->
                                                <p class="mb-0"><strong>Shipping:</strong>
                                                    $<?php echo ($item->shipping_product * $value['quantity']); ?>
                                                    <?php $total_price_sc += ($item->shipping_product * $value['quantity']); ?>
                                                </p>

                                                <!-- Especificaciones del producto -->
                                                <div class="small text-secondary">

                                                    <?php

                                                    if ($value['details'] != null) {

                                                        foreach (json_decode($value['details'], true) as $key => $detail) {

                                                            foreach (array_keys($detail) as $key => $list) {
                                                                echo "<div>$list: " . array_values($detail)[$key] . "</div>";
                                                            }
                                                        }
                                                    }

                                                    ?>

                                                </div>

                                                <!-- El precio del producto y la cantidad -->
                                                <p class="mb-0">

                                                    <!-- Cantidad del producto -->
                                                <p class="float-left">
                                                    <strong>Quantity:</strong> <?php echo $value['quantity']; ?>
                                                </p>

                                                <!-- Precio del producto -->

                                                <?php if ($item->offer_product != null) : ?>

                                                    <!-- El precio en oferta del producto -->

                                                    <p class="font-weight-bold ps-product__price sale float-right text-danger">
                                                        $
                                                        <?php

                                                        $price = TemplateController::offer_price(
                                                            $item->price_product,
                                                            json_decode($item->offer_product, true)[1],
                                                            json_decode($item->offer_product, true)[0]
                                                        );

                                                        echo $price;

                                                        $total_price_sc += ($price * $value['quantity']);

                                                        ?>

                                                        <del class="text-muted">$<?php echo $item->price_product ?></del>
                                                    </p>

                                                <?php else : ?>

                                                    <p class="font-weight-bold ps-product__price float-right text-secondary">
                                                        $<?php echo $item->price_product ?>
                                                        <?php $total_price_sc += ($item->price_product * $value['quantity']); ?>
                                                    </p>

                                                <?php endif ?>
                                                </p>
                                            </div>

                                        </div>

                                    <?php endforeach ?>

                                </div>

                                <div class="ps-cart__footer">

                                    <h3>Total:<strong>$<?php echo $total_price_sc; ?></strong></h3>
                                    <figure>
                                        <a class="ps-btn btn-view-cart" href="<?php echo $path; ?>shopping-cart">View Cart</a>
                                        <a class="ps-btn" href="<?php echo $path; ?>checkout">Checkout</a>
                                    </figure>

                                </div>

                            </div>

                        <?php endif ?>

                    </div>

                    <!-- Cuentas de usuario -->

                    <?php if (isset($_SESSION['user'])) : ?>
                        <div class="ps-block--user-header">
                            <div class="ps-block__left">

                                <?php if ($_SESSION['user']->method_user == "direct") : ?>

                                    <?php if ($_SESSION['user']->picture_user == null) : ?>
                                        <a href="<?php echo $path ?>account&wishlist">
                                            <img class="img-fluid rounded-circle" style="width: 40px;" src="img/users/default/default.png" alt="profile">
                                        </a>
                                    <?php else : ?>
                                        <a href="<?php echo $path ?>account&wishlist">
                                            <img class="img-fluid rounded-circle" style="width: 40px;" src="img/users/<?php echo $_SESSION['user']->id_user; ?>/<?php echo $_SESSION['user']->picture_user; ?>" alt="profile">
                                        </a>
                                    <?php endif ?>

                                <?php else : ?>

                                    <?php if (explode("/", $_SESSION['user']->picture_user)[0] == "https:") : ?>
                                        <a href="<?php echo $path ?>account&wishlist">
                                            <img class="img-fluid rounded-circle" style="width: 40px;" src="<?php echo $_SESSION['user']->picture_user; ?>" alt="profile">
                                        </a>
                                    <?php else : ?>
                                        <a href="<?php echo $path ?>account&wishlist">
                                            <img class="img-fluid rounded-circle" style="width: 40px;" src="img/users/<?php echo $_SESSION['user']->id_user; ?>/<?php echo $_SESSION['user']->picture_user; ?>" alt="profile">
                                        </a>
                                    <?php endif ?>

                                <?php endif ?>
                            </div>
                        </div>
                    <?php else : ?>
                        <!--=====================================
					    Login and Register
					    ======================================-->

                        <div class="ps-block--user-header">
                            <div class="ps-block__left">
                                <i class="icon-user"></i>
                            </div>
                            <div class="ps-block__right">
                                <a href="<?php echo $path ?>account&login#header">Login</a>
                                <a href="<?php echo $path ?>account&enrollment#header">Register</a>
                            </div>
                        </div>
                    <?php endif ?>

                    <?php if (isset($_SESSION['user'])) : ?>
                        <a href="<?php echo $path . "account&logout"; ?>" id="btn-logout" style="font-size: 24px;">
                            <i class="bi bi-box-arrow-right"></i>
                        </a>
                    <?php endif ?>

                </div><!-- End Header Actions-->

            </div><!-- End Header Content Right-->

        </div><!-- End Container-->

    </div><!-- End Header Content-->

</header>