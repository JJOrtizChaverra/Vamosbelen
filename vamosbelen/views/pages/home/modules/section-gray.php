<?php

$url = CurlController::api() . "categories?orderBy=views_category&orderMode=DESC&startAt=0&endAt=3";
$method = "GET";
$fields = array();
$header = array();

$top_categories = CurlController::request($url, $method, $fields, $header)->result;

?>

<!-- Preload -->

<div class="d-flex justify-content-center preload-true">
    <div class="spinner-border text-muted my-5"></div>
</div>

<!-- Load -->

<div class="ps-section--gray preload-false">

    <div class="container">

        <!--=====================================
        Products of category
        ======================================-->

        <?php foreach ($top_categories as $key_category => $value_category) : ?>

            <div class="ps-block--products-of-category">

                <!--=====================================
                Menu subcategory
                ======================================-->

                <div class="ps-block__categories">

                    <h3><?php echo $value_category->name_category ?></h3>

                    <ul>

                        <?php

                        // Traer la subcategoria segun el id de la categoria

                        $select = "name_subcategory,url_subcategory";

                        $url = CurlController::api() . "subcategories?linkTo=id_category_subcategory&equalTo={$value_category->id_category}&select=$select";
                        $method = "GET";
                        $fields = array();
                        $header = array();

                        $list_subcategories = CurlController::request($url, $method, $fields, $header)->result;

                        ?>

                        <?php foreach ($list_subcategories as $key => $value_subcategory) : ?>

                            <li><a href="<?php echo $path . $value_subcategory->url_subcategory ?>"><?php echo $value_subcategory->name_subcategory ?></a></li>

                        <?php endforeach ?>

                    </ul>

                    <a class="ps-block__more-link" href="<?php echo $path . $value_subcategory->url_subcategory ?>">View All</a>

                </div>

                <!--=====================================
                Vertical Slider Category
                ======================================-->

                <?php

                // Traer el listado de productos

                $select = "vertical_slider_product,name_product,image_product,stock_product,offer_product,url_product,reviews_product,price_product";

                $url = CurlController::api() . "products?linkTo=id_category_product&equalTo={$value_category->id_category}&orderBy=views_product&orderMode=DESC&startAt=0&endAt=6&select=$select";
                $method = "GET";
                $fields = array();
                $header = array();

                $list_products = CurlController::request($url, $method, $fields, $header)->result;

                ?>

                <div class="ps-block__slider">

                    <div class="ps-carousel--product-box owl-slider" data-owl-auto="true" data-owl-loop="true" data-owl-speed="7000" data-owl-gap="0" data-owl-nav="true" data-owl-dots="true" data-owl-item="1" data-owl-item-xs="1" data-owl-item-sm="1" data-owl-item-md="1" data-owl-item-lg="1" data-owl-duration="500" data-owl-mousedrag="off">

                        <a href="/">

                            <img src="img/slider/vertical/<?php echo $key_category + 1; ?>.jpeg" alt="vertical-slider-vamosbelen">

                        </a>

                        <?php foreach ($list_products as $key => $value_products) : ?>

                            <?php if ($key !== 4) : ?>

                                <?php if ($value_products->vertical_slider_product != null) : ?>

                                    <a href="<?php echo $path . $value_products->url_product ?>">

                                        <img src="img/products/<?php echo $value_category->url_category ?>/vertical/<?php echo $value_products->vertical_slider_product ?>" alt="<?php echo $value_products->name_product ?>">

                                    </a>

                                <?php endif ?>

                            <?php endif ?>

                        <?php endforeach ?>

                    </div>

                </div>

                <!--=====================================
                Block Product Box
                ======================================-->

                <div class="ps-block__product-box">

                    <!--=====================================
                    Product Simple
                    ======================================-->

                    <?php foreach ($list_products as $key => $value_products) : ?>

                        <div class="ps-product ps-product--simple">

                            <div class="ps-product__thumbnail">

                                <!-- Imagen del producto -->

                                <a href="<?php echo $path . $value_products->url_product ?>">

                                    <img src="img/products/<?php echo $value_category->url_category ?>/<?php echo $value_products->image_product ?>" alt="<?php echo $value_products->name_product ?>">

                                </a>

                                <!-- Descuento de oferta o fuera del stock -->


                                <?php if ($value_products->stock_product == 0) : ?>

                                    <div class="ps-product__badge out-stock">Out Of Stock</div>

                                <?php else : ?>

                                    <?php if ($value_products->offer_product != null) : ?>

                                        <div class="ps-product__badge">
                                            -
                                            <?php echo TemplateController::offer_discount(
                                                $value_products->price_product,
                                                json_decode($value_products->offer_product, true)[1],
                                                json_decode($value_products->offer_product, true)[0]
                                            );
                                            ?>
                                            %
                                        </div>
                                    <?php endif ?>



                                <?php endif ?>

                            </div>

                            <div class="ps-product__container">

                                <div class="ps-product__content" data-mh="clothing">

                                    <!-- Titulo del producto -->

                                    <a class="ps-product__title" href="<?php echo $path . $value_products->url_product ?>"><?php echo $value_products->name_product ?></a>

                                    <div class="ps-product__rating">

                                        <!-- Reseñas del producto -->

                                        <?php
                                        $reviews = TemplateController::average_reviews(json_decode($value_products->reviews_product, true));
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
                                            if ($value_products->reviews_product != null) {
                                                echo count(json_decode($value_products->reviews_product, true));
                                            } else {
                                                echo 0;
                                            }
                                            ?>
                                            review)
                                        </span>

                                    </div>

                                    <?php if ($item->offer_product != null) : ?>

                                        <!-- El precio en oferta del producto -->

                                        <p class="ps-product__price sale">
                                            $
                                            <?php
                                            echo TemplateController::offer_price(
                                                $item->price_product,
                                                json_decode($item->offer_product, true)[1],
                                                json_decode($item->offer_product, true)[0]
                                            );
                                            ?>

                                            <del>$<?php echo $item->price_product ?></del>
                                        </p>

                                    <?php else : ?>

                                        <p class="ps-product__price">$<?php echo $item->price_product ?></p>

                                    <?php endif ?>

                                </div>

                            </div>

                        </div> <!-- End Product Simple -->

                    <?php endforeach ?>

                </div><!-- End Block Product Box -->

            </div><!-- End Products of category -->

        <?php endforeach ?>

    </div><!-- End Container-->

</div><!-- End Section Gray-->