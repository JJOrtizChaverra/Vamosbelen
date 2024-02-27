<?php

// Traer los 20 productos con mas ventas

$select = "url_product,image_product,name_product,offer_product,price_product,url_category";

$url = CurlController::api() . "relations?rel=products,categories&type=product,category&orderBy=sales_product&orderMode=DESC&startAt=0&endAt=20&select=$select";
$method = "GET";
$fields = array();
$header = array();

$top_sales_products = CurlController::request($url, $method, $fields, $header)->result;

$top_sales = array();

// Organizar bloques de a 5 productos

for ($i = 0; $i < ceil(count($top_sales_products) / 4); $i++) {
    array_push($top_sales, array_slice($top_sales_products, ($i * 4), 4));
}

?>

<div class="col-xl-3 col-12 ">

    <aside class="widget widget_best-sale" data-mh="dealhot">

        <h3 class="widget-title">Top 20 Best Seller</h3>

        <div class="widget__content">

            <!-- Preload -->

            <div class="d-flex justify-content-center preload-true">
                <div class="spinner-border text-muted my-5"></div>
            </div>

            <!-- Load -->

            <div class="owl-slider preload-false" data-owl-auto="true" data-owl-loop="true" data-owl-speed="5000" data-owl-gap="0" data-owl-nav="false" data-owl-dots="false" data-owl-item="1" data-owl-item-xs="1" data-owl-item-sm="1" data-owl-item-md="1" data-owl-item-lg="1" data-owl-duration="1000" data-owl-mousedrag="on">

                <?php foreach ($top_sales as $key => $value) : ?>

                    <div class="ps-product-group">

                        <!--=====================================
                        Product
                        ======================================-->

                        <?php foreach ($value as $index => $item) : ?>

                            <div class="ps-product--horizontal">

                                <div class="ps-product__thumbnail">
                                    <a href="<?php echo $path . $item->url_product ?>">
                                        <img src="img/products/<?php echo $item->url_category ?>/<?php echo $item->image_product ?>" alt="">
                                    </a>
                                </div>

                                <div class="ps-product__content">

                                    <a class="ps-product__title" href="<?php echo $path . $item->url_product ?>"><?php echo $item->name_product; ?></a>

                                    <?php if ($item->offer_product != null) : ?>

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

                            </div><!-- End Product -->

                        <?php endforeach ?>

                    </div><!-- End Product Group -->

                <?php endforeach ?>
            </div>

        </div>

    </aside><!-- End Aside -->

</div><!-- End Columns -->