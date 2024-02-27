<header class="header header--mobile" data-sticky="true">
    <div class="header__top">

        <div class="header__left">

            <ul class="d-flex justify-content-center">
                <li><a href="#" target="_blank"><i class="fab fa-facebook-f mr-4"></i></a></li>
                <li><a href="#" target="_blank"><i class="fab fa-instagram mr-4"></i></a></li>
                <li><a href="#" target="_blank"><i class="fab fa-twitter mr-4"></i></a></li>
                <li><a href="#" target="_blank"><i class="fab fa-youtube mr-4"></i></a></li>
            </ul>
        </div>

        <div class="header__right">

            <ul class="navigation__extra">

                <li><a href="<?php echo TemplateController::path() ?>become-vendor">Sell on Vamosbelén</a></li>
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

    </div>

    <div class="navigation--mobile">

        <div class="navigation__left">

            <a class="ps-logo pl-3 pl-sm-5" href="/">
                <img src="img/template/logo.png" width="300px" alt="logo">
            </a>

        </div>

        <div class="navigation__right">

            <div class="header__actions">

            </div>

        </div>

        <?php if (isset($_SESSION['user'])) : ?>
            <a href="<?php echo $path . "account&logout"; ?>" class="d-flex align-items-end" id="btn-logout" style="font-size: 24px;">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        <?php endif ?>

    </div>

</header> <!-- End Header Mobile -->

<!--=====================================
Navigation Mobile Categories
======================================-->

<div class="ps-panel--sidebar" id="navigation-mobile">
    <div class="ps-panel__header">
        <h3>Categories</h3>
    </div>
    <div class="ps-panel__content">
        <ul class="menu--mobile">
            <?php foreach ($menu_categories as $key => $value) : ?>

                <li class="current-menu-item menu-item-has-children has-mega-menu"><a href="<?php echo $path . $value->url_category ?>"><?php echo $value->name_category ?></a><span class="sub-toggle"></span>
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
                                        <li class="current-menu-item"><a href="<?php echo $path . $value->url_subcategory ?>"><?php echo $value->name_subcategory ?></a></li>
                                    <?php endforeach ?>
                                </ul>
                            </div>
                        <?php endforeach ?>
                    </div>
                </li>

            <?php endforeach ?>
        </ul>
    </div>
</div>

<!--=====================================
Navigation Mobile Search
======================================-->

<div class="ps-panel--sidebar search-sidebar" id="search-sidebar">
    <div class="ps-panel__header">
        <form class="ps-form--search-mobile">
            <div class="form-group--nest">
                <input class="form-control input-search" type="text" placeholder="Search something...">
                <button type="button" class="btn-search" path="<?php echo $path; ?>"><i class="icon-magnifier"></i></button>
            </div>
        </form>
    </div>
    <div class="navigation__content"></div>
</div>

<!--=====================================
Navigation Mobile Shoping Cart
======================================-->

<div class="ps-panel--sidebar" id="cart-mobile">
    <div class="ps-panel__header">
        <h3>Shopping Cart</h3>
    </div>
    <div class="navigation__content">
        <div class="ps-cart--mobile">

            <?php

            $total_price_sc = 0;

            if (isset($_COOKIE['list-sc'])) {

                $shopping_cart = json_decode($_COOKIE['list-sc'], true);
                $total_sc = count($shopping_cart);
            } else {
                $total_sc = 0;
            }

            ?>

            <?php if ($total_sc > 0) : ?>

                <div class="ps-cart__content">

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
    </div>
</div>