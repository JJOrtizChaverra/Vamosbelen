<?php

// Traer la lista del carrito de compras

$product_sc = array();

if (isset($_COOKIE['list-sc'])) {
    $shopping_cart = json_decode($_COOKIE['list-sc'], true);

    $select = "url_product,name_product,url_category,price_product,image_product,stock_product,offer_product,name_store,shipping_product";
    $product_sc = array();

    foreach ($shopping_cart as $key => $value) {
        $url = CurlController::api() . "relations?rel=products,categories,stores&type=product,category,store&linkTo=url_product&equalTo={$value['product']}&select=$select";
        $method = "GET";
        $fields = array();
        $header = array();

        array_push($product_sc, CurlController::request($url, $method, $fields, $header)->result[0]);
    }
}

?>

<!--=====================================
Breadcrumb
======================================-->

<div class="ps-breadcrumb">

    <div class="container">

        <ul class="breadcrumb">

            <li><a href="/">Home</a></li>

            <li>Shopping cart</li>

        </ul>

    </div>

</div>

<!--=====================================
Shopping Cart
======================================-->

<div class="ps-section--shopping ps-shopping-cart">

    <div class="container">

        <div class="ps-section__header">

            <h1>Shopping Cart</h1>

        </div>

        <div class="ps-section__content">

            <div class="table-responsive">

                <table class="table ps-table--shopping-cart dt-responsive dt-client">

                    <thead>

                        <tr>

                            <th>Product name</th>
                            <th>PRICE</th>
                            <th>SHIPPING</th>
                            <th>QUANTITY</th>
                            <th>TOTAL</th>
                            <th></th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($product_sc as $key => $value) : ?>

                            <tr>
                                <td>

                                    <div class="ps-product--cart">

                                        <div class="ps-product__thumbnail">

                                            <!-- Imagen del producto -->

                                            <a href="<?php echo $path . $value->url_product; ?>"><img src="img/products/<?php echo $value->url_category; ?>/<?php echo $value->image_product ?>" alt="<?php echo $value->name_product; ?>"></a>

                                        </div>

                                        <div class="ps-product__content">

                                            <!-- Nombre del producto -->

                                            <a href="<?php echo $path . $value->url_product; ?>"><?php echo $value->name_product; ?></a>

                                            <div class="small text-secondary list-sc" url="<?php echo $value->url_product; ?>" details='<?php echo $shopping_cart[$key]['details']; ?>'>

                                                <?php

                                                if ($shopping_cart[$key]['details'] != null) {

                                                    foreach (json_decode($shopping_cart[$key]['details'], true) as $index => $detail) {

                                                        foreach (array_keys($detail) as $index => $list) {
                                                            echo "<div>$list: " . array_values($detail)[$index] . "</div>";
                                                        }
                                                    }
                                                }

                                                ?>

                                            </div>

                                            <!-- Nombre de la tienda -->

                                            <p>Sold By:<strong> <?php echo $value->name_store; ?></strong></p>

                                        </div>

                                    </div>

                                </td>

                                <td class="ps-product">
                                    <?php if ($value->offer_product != null) : ?>

                                        <!-- El precio en oferta del producto -->

                                        <h4 class="ps-product__price sale">
                                            $
                                            <?php

                                            $price = TemplateController::offer_price(
                                                $value->price_product,
                                                json_decode($value->offer_product, true)[1],
                                                json_decode($value->offer_product, true)[0]
                                            );

                                            echo "<span>$price</span>"

                                            ?>

                                            <br />

                                            <del>$<?php echo $value->price_product ?></del>
                                        </h4>

                                    <?php else : ?>

                                        <h4 class="ps-product__price">$<span><?php echo $value->price_product ?></span></h4>

                                    <?php endif ?>
                                </td>

                                <td class="text-center shipping">$<span current-shipping="<?php echo $value->shipping_product; ?>"><?php echo $value->shipping_product; ?></span></td>


                                <td>

                                    <div class="form-group--number quantity">

                                        <button class="up" onclick="changeQuantityShoppingCart($('#quantity-<?php echo $key; ?>').val(), 'up', <?php echo $value->stock_product ?>, <?php echo $key; ?>)">+</button>

                                        <button class="down" onclick="changeQuantityShoppingCart($('#quantity-<?php echo $key; ?>').val(), 'down', <?php echo $value->stock_product ?>, <?php echo $key; ?>)">-</button>

                                        <input id="quantity-<?php echo $key; ?>" class="form-control" type="text" placeholder="1" value="<?php echo $shopping_cart[$key]['quantity'] ?>" readonly>

                                    </div>

                                </td>

                                <td>$<span class="subtotal">0</span></td>

                                <td>

                                    <a class="btn" onclick="removeShoppingCart('<?php echo $value->url_product; ?>', '<?php echo $_SERVER['REQUEST_URI'] ?>')">
                                        <i class="icon-cross"></i>
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach ?>

                    </tbody>

                </table>

            </div>

            <hr>

            <div class="d-flex flex-row-reverse">
                <div class="p-2 total-price">
                    <h3>Total $<span>0</span></h3>
                </div>
            </div>

            <div class="ps-section__cart-actions">

                <a class="ps-btn" href="/">
                    <i class="icon-arrow-left"></i> Back to Shop
                </a>

                <?php if (isset($_COOKIE['list-sc']) && $_COOKIE['list-sc'] != "[]") : ?>

                    <a class="ps-btn" href="<?php echo $path; ?>checkout">
                        Proceed to checkout <i class="icon-arrow-right"></i>
                    </a>

                <?php endif ?>

            </div>

        </div>

    </div>

</div>