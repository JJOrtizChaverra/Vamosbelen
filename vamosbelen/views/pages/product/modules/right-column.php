<?php

$select = "url_category,image_product,name_product,offer_product,price_product,url_product,url_store,name_store,reviews_product,offer_product";

$url = CurlController::api() . "relations?rel=products,categories,subcategories,stores&type=product,category,subcategory,store&linkTo=id_store_product&equalTo={$product->id_store}&orderBy=id_product&orderMode=DESC&startAt=0&endAt=4&select=$select";
$method = "GET";
$fields = array();
$header = array();

$store_products = CurlController::request($url, $method, $fields, $header)->result;
?>



<aside class="widget widget_product widget_features">

    <p><i class="icon-network"></i> Shipping worldwide</p>

    <p><i class="icon-3d-rotate"></i> Free 7-day return if eligible, so easy</p>

    <p><i class="icon-receipt"></i> Supplier give bills for this product.</p>

    <p><i class="icon-credit-card"></i> Pay online or when receiving goods</p>

</aside>

<aside class="widget widget_sell-on-site">

    <p><i class="icon-store"></i> Sell on Vamosbelén?<a href="<?php echo TemplateController::path() ?>account&enrollment#header"> Register Now !</a></p>

</aside>

<aside class="widget widget_same-brand">

    <h3>Same Store</h3>

    <div class="widget__content">

        <?php foreach ($store_products as $key => $value) : ?>

            <div class="ps-product">
                <div class="ps-product__thumbnail">
                    <!-- Imagen del producto -->

                    <a href="<?php echo $path . $value->url_product; ?>">
                        <img src="img/products/<?php echo $value->url_category; ?>/<?php echo $value->image_product ?>" alt="<?php echo $value->name_product ?>">
                    </a>
                    <ul class="ps-product__actions">
                        <li><a class="btn" onclick="addShoppingCart('<?php echo $value->url_product; ?>', '<?php echo CurlController::api(); ?>', '<?php echo $_SERVER['REQUEST_URI'] ?>', this)" details-sc quantity-sc data-toggle="tooltip" data-placement="top" title="Add to cart"><i class="icon-bag2"></i></a></li>
                        <li><a href="<?php echo $path . $value->url_product; ?>" data-toggle="tooltip" data-placement="top" title="Quick View"><i class="icon-eye"></i></a></li>
                        <li><a class="btn" onclick="addWishlist('<?php echo $value->url_product ?>', '<?php echo CurlController::api(); ?>')" data-toggle="tooltip" data-placement="top" title="Add to Whishlist"><i class="icon-heart"></i></a></li>
                    </ul>
                </div>
                <div class="ps-product__container">

                    <!-- Nombre de la tienda -->

                    <a class="ps-product__vendor" href="<?php echo $path . $value->url_store ?>"><?php echo $value->name_store ?></a>

                    <div class="ps-product__content">

                        <!-- Nombre del producto -->

                        <a class="ps-product__title" href="<?php echo $path . $value->url_product ?>">
                            <?php echo $value->name_product ?></a>

                        <!-- Reseñas del producto -->

                        <div class="ps-product__rating">

                            <?php
                            $reviews = TemplateController::average_reviews(json_decode($value->reviews_product, true));
                            ?>

                            <select class="ps-rating" data-read-only="true">

                                <?php

                                if ($reviews > 0) {
                                    for ($i = 0; $i < 5; $i++) {
                                        if ($reviews < ($i + 1)) {
                                            echo '<option value="2">' . ($i + 1) . '</option>';
                                        } else {
                                            echo '<option value="1">' . ($i + 1) . '</option>';
                                        }
                                    }
                                } else {
                                    echo '<option value="0">0</option>';
                                    for ($i = 0; $i < 5; $i++) {
                                        echo '<option value="1">' . ($i + 1) . '</option>';
                                    }
                                }

                                ?>
                            </select>

                            <span>
                                (
                                <?php
                                if ($value->reviews_product != null) {
                                    echo count(json_decode($value->reviews_product, true));
                                } else {
                                    echo 0;
                                }
                                ?>
                                )
                            </span>

                        </div>

                        <!-- El precio en oferta del producto -->

                        <?php if ($value->offer_product != null) : ?>

                            <p class="ps-product__price sale">
                                $
                                <?php
                                echo TemplateController::offer_price(
                                    $value->price_product,
                                    json_decode($value->offer_product, true)[1],
                                    json_decode($value->offer_product, true)[0]
                                );
                                ?>

                                <del>$<?php echo $value->price_product; ?></del>
                            </p>

                        <?php else : ?>

                            <p class="ps-product__price">$<?php echo $value->price_product; ?></p>

                        <?php endif ?>

                    </div>
                    <div class="ps-product__content hover">
                        <!-- Nombre del producto -->

                        <a class="ps-product__title" href="<?php echo $path . $value->url_product ?>">
                            <?php echo $value->name_product ?></a>

                        <!-- El precio en oferta del producto -->

                        <?php if ($value->offer_product != null) : ?>

                            <p class="ps-product__price sale">
                                $
                                <?php
                                echo TemplateController::offer_price(
                                    $value->price_product,
                                    json_decode($value->offer_product, true)[1],
                                    json_decode($value->offer_product, true)[0]

                                );
                                ?>

                                <del>$<?php echo $value->price_product; ?></del>
                            </p>

                        <?php else : ?>

                            <p class="ps-product__price">$<?php echo $value->price_product; ?></p>

                        <?php endif ?>
                    </div>
                </div>
            </div>

        <?php endforeach ?>

    </div>

</aside>