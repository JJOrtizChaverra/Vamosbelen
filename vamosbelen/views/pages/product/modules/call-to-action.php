<header class="header header--product header--sticky" data-sticky="true">

    <nav class="navigation">

        <div class="container">

            <article class="ps-product--header-sticky">

                <div class="ps-product__thumbnail">

                    <img src="img/products/<?php echo $product->url_category ?>/<?php echo $product->image_product ?>" alt="<?php echo $product->name_product;  ?>">

                </div>

                <div class="ps-product__wrapper">

                    <div class="ps-product__content">

                        <a class="ps-product__title" href="#"><?php echo $product->name_product;  ?></a>

                    </div>

                    <div class="ps-product__shopping">
                        <span class="ps-product__price">
                            <?php if ($product->offer_product != null) : ?>

                                <!-- El precio en oferta del producto -->

                                <span>
                                    $
                                    <?php
                                    echo TemplateController::offer_price(
                                        $product->price_product,
                                        json_decode($product->offer_product, true)[1],
                                        json_decode($product->offer_product, true)[0]
                                    );
                                    ?>
                                </span>
                                <del>$<?php echo $product->price_product; ?></del>

                            <?php else : ?>

                                <span class="text-dark">$<?php echo $product->price_product; ?></span>

                            <?php endif ?>
                        </span>
                        <a class="ps-btn" onclick="addShoppingCart('<?php echo $product->url_product; ?>', '<?php echo CurlController::api(); ?>', '<?php echo $_SERVER['REQUEST_URI'] ?>', this)" details-sc quantity-sc> Add to Cart</a>

                    </div>

                </div>

            </article>

        </div>

    </nav>

</header>