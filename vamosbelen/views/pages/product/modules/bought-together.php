<?php

$select = "id_product,url_category,url_product,image_product,name_product,offer_product,price_product,name_store,reviews_product,stock_product,url_store";

$url = CurlController::api() . "relations?rel=products,categories,subcategories,stores&type=product,category,subcategory,store&linkTo=title_list_product&equalTo={$product->title_list_product}&select=$select";
$method = "GET";
$fields = array();
$header = array();

$new_product = CurlController::request($url, $method, $fields, $header)->result;
?>

<?php if (count($new_product) > 1) : ?>

    <div class="ps-block--bought-toggether">

        <h4>Frequently Bought Together</h4>

        <div class="ps-block__content">

            <div class="ps-block__items">

                <!-- Current product -->

                <div class="ps-block__item">

                    <div class="ps-product ps-product--simple">

                        <div class="ps-product__thumbnail">

                            <!-- Imagen del producto -->

                            <a href="<?php echo $path . $product->url_product; ?>">
                                <img src="img/products/<?php echo $product->url_category; ?>/<?php echo $product->image_product ?>" alt="<?php echo $product->name_product ?>">
                            </a>

                        </div>

                        <div class="ps-product__container">

                            <!-- Nombre del producto -->

                            <div class="ps-product__content">
                                <a class="ps-product__title" href="<?php echo $path . $product->url_product ?>"><?php echo $product->name_product ?></a>


                                <?php if ($product->offer_product != null) : ?>

                                    <p class="ps-product__price sale">
                                        $
                                        <?php
                                        $price1 = TemplateController::offer_price(
                                            $product->price_product,
                                            json_decode($product->offer_product, true)[1],
                                            json_decode($product->offer_product, true)[0]
                                        );

                                        echo $price1;
                                        ?>

                                        <del>$<?php echo $product->price_product; ?></del>
                                    </p>

                                <?php else : ?>

                                    <?php $price1 = $product->price_product; ?>

                                    <p class="ps-product__price">$<?php echo $price1; ?></p>

                                <?php endif ?>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- New product -->

                <?php foreach ($new_product as $key => $value) : ?>

                    <?php if ($value->id_product != $product->id_product) : ?>

                        <div class="ps-block__item">

                            <div class="ps-product ps-product--simple">

                                <div class="ps-product__thumbnail">

                                    <!-- Imagen del producto -->

                                    <a href="<?php echo $path . $value->url_product; ?>">
                                        <img src="img/products/<?php echo $value->url_category; ?>/<?php echo $value->image_product ?>" alt="<?php echo $value->name_product ?>">
                                    </a>

                                </div>

                                <div class="ps-product__container">

                                    <div class="ps-product__content">

                                        <!-- Nombre del producto -->

                                        <div class="ps-product__content">
                                            <a class="ps-product__title" href="<?php echo $path . $value->url_product ?>"><?php echo $value->name_product ?></a>

                                            <?php if ($value->offer_product != null) : ?>

                                                <p class="ps-product__price sale">
                                                    $
                                                    <?php
                                                    $price2 = TemplateController::offer_price(
                                                        $value->price_product,
                                                        json_decode($value->offer_product, true)[1],
                                                        json_decode($value->offer_product, true)[0]
                                                    );

                                                    echo "$price2";

                                                    ?>

                                                    <del>$<?php echo $value->price_product ?></del>
                                                </p>

                                            <?php else : ?>

                                            <?php $price2 = $value->price_product; ?>

                                                <p class="ps-product__price">$<?php echo $price2; ?></p>

                                            <?php endif ?>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <div class="ps-block__item ps-block__total">

                            <p>Total Price:<strong> $<?php echo $price1 + $price2; ?></strong></p>

                            <a class="ps-btn" onclick="addShoppingCart2('<?php echo $product->url_product; ?>', '<?php echo $value->url_product; ?>', '<?php echo CurlController::api(); ?>', '<?php echo $_SERVER['REQUEST_URI'] ?>', this)" details-sc quantity-sc>Add All to cart</a>
                            <a class="ps-btn ps-btn--gray ps-btn--outline" onclick="addWishlist2('<?php echo $product->url_product; ?>', '<?php echo $value->url_product; ?>', '<?php echo CurlController::api(); ?>')">Add All to whishlist</a>

                        </div>


            </div>

        </div>

    </div>

    <?php return ?>

<?php endif ?>

<?php endforeach ?>

<?php endif ?>