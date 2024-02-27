<div class="ps-block--shop-features">

    <div class="ps-block__header">

        <h3>Recommended Items</h3>

        <div class="ps-block__navigation">

            <a class="ps-carousel__prev" href="#recommended">
                <i class="icon-chevron-left"></i>
            </a>

            <a class="ps-carousel__next" href="#recommended">
                <i class="icon-chevron-right"></i>
            </a>

        </div>

    </div>

    <div class="ps-block__content">

        <!-- Preload -->

        <div class="d-flex justify-content-center preload-true">
            <div class="spinner-border text-muted my-5"></div>
        </div>

        <!-- Load -->

        <div class="preload-false owl-slider" id="recommended" data-owl-auto="true" data-owl-loop="true" data-owl-speed="10000" data-owl-gap="30" data-owl-nav="false" data-owl-dots="false" data-owl-item="6" data-owl-item-xs="2" data-owl-item-sm="2" data-owl-item-md="3" data-owl-item-lg="4" data-owl-item-xl="5" data-owl-duration="1000" data-owl-mousedrag="on">

            <!--=====================================
            Product
            ======================================-->

            <?php foreach ($recommended_items as $key => $value) : ?>

                <div class="ps-product">

                    <div class="ps-product__thumbnail">

                        <!-- Imagen del producto -->

                        <a href="<?php echo $path . $value->url_product; ?>">
                            <img src="img/products/<?php echo $value->url_category; ?>/<?php echo $value->image_product ?>" alt="<?php echo $value->name_product ?>">
                        </a>

                        <!-- Descuento de oferta o fuera del stock -->

                        <?php if ($value->stock_product == 0) : ?>

                            <div class="ps-product__badge out-stock">Out Of Stock</div>

                        <?php else : ?>

                            <?php if ($value->offer_product != null) : ?>

                                <div class="ps-product__badge">
                                    -
                                    <?php echo TemplateController::offer_discount(
                                        $value->price_product,
                                        json_decode($value->offer_product, true)[1],
                                        json_decode($value->offer_product, true)[0]
                                    );
                                    ?>
                                    %
                                </div>
                            <?php endif ?>



                        <?php endif ?>

                        <!-- Botones de acciones -->

                        <ul class="ps-product__actions">

                            <li>
                                <a class="btn" onclick="addShoppingCart('<?php echo $value->url_product; ?>', '<?php echo CurlController::api(); ?>', '<?php echo $_SERVER['REQUEST_URI'] ?>', this)" details-sc quantity-sc data-toggle="tooltip" data-placement="top" title="Add to Cart">
                                    <i class="icon-bag2"></i>
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo $path . $value->url_product ?>" data-toggle="tooltip" data-placement="top" title="Quick View">
                                    <i class="icon-eye"></i>
                                </a>
                            </li>

                            <li>
                                <a class="btn" onclick="addWishlist('<?php echo $value->url_product ?>', '<?php echo CurlController::api(); ?>')" data-toggle="tooltip" data-placement="top" title="Add to Whishlist">
                                    <i class="icon-heart"></i>
                                </a>
                            </li>

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

                            <?php if ($value->offer_product != null) : ?>

                                <!-- El precio en oferta del producto -->

                                <p class="ps-product__price sale">
                                    $
                                    <?php
                                    echo TemplateController::offer_price(
                                        $value->price_product,
                                        json_decode($value->offer_product, true)[1],
                                        json_decode($value->offer_product, true)[0]
                                    );
                                    ?>

                                    <del>$<?php echo $value->price_product ?></del>
                                </p>

                            <?php else : ?>

                                <p class="ps-product__price">$<?php echo $value->price_product ?></p>

                            <?php endif ?>

                        </div>

                        <div class="ps-product__content hover">

                            <!-- Nombre del producto -->

                            <a class="ps-product__title" href="<?php echo $path . $value->url_product ?>">
                                <?php echo $value->name_product ?></a>

                            <?php if ($value->offer_product != null) : ?>

                                <!-- El precio en oferta del producto -->

                                <p class="ps-product__price sale">
                                    $
                                    <?php
                                    echo TemplateController::offer_price(
                                        $value->price_product,
                                        json_decode($value->offer_product, true)[1],
                                        json_decode($value->offer_product, true)[0]
                                    );
                                    ?>

                                    <del>$<?php echo $value->price_product ?></del>
                                </p>

                            <?php else : ?>

                                <p class="ps-product__price">$<?php echo $value->price_product ?></p>

                            <?php endif ?>

                        </div>

                    </div>

                </div><!-- End Product -->

            <?php endforeach ?>

        </div>

    </div>

</div><!-- End Recommended Items -->