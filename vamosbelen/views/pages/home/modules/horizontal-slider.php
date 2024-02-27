<?php

// Traer 5 productos aleatoriamente

$random_start = rand(1, ($total_products - 5));

$url = CurlController::api() . "relations?rel=products,categories&type=product,category&orderBy=id_product&orderMode=ASC&startAt=$random_start&endAt=5&select=url_category,horizontal_slider_product,url_product,name_product,default_banner_product";
$method = "GET";
$fields = array();
$header = array();

$products_hslider = CurlController::request($url, $method, $fields, $header)->result;

$random_slider_default = rand(1, 3);

?>

<!-- Preload -->

<div class="d-flex justify-content-center preload-true">
    <div class="spinner-border text-muted my-5"></div>
</div>

<!-- Load -->

<div class="ps-home-banner preload-false">
    <div class="ps-carousel--nav-inside owl-slider" data-owl-auto="true" data-owl-loop="true" data-owl-speed="5000" data-owl-gap="0" data-owl-nav="true" data-owl-dots="true" data-owl-item="1" data-owl-item-xs="1" data-owl-item-sm="1" data-owl-item-md="1" data-owl-item-lg="1" data-owl-duration="1000" data-owl-mousedrag="on" data-owl-animate-in="fadeIn" data-owl-animate-out="fadeOut">

        <div class="ps-banner--market-4" data-background="img/slider/horizontal/<?php echo $random_slider_default; ?>.jpeg">

            <div class="ps-banner--market-4">

                <img src="img/slider/horizontal/<?php echo $random_slider_default; ?>.jpeg" alt="horizontal-slider-vamosbelen">

            </div>

        </div>

        <?php foreach ($products_hslider as $key => $value) : ?>

            <?php

            if ($value->horizontal_slider_product != null) {

                $hs_slider = json_decode($value->horizontal_slider_product, true);
            }

            ?>

            <?php if ($key !== 3) : ?>

                <div class="ps-banner--market-4" data-background="img/products/<?php echo $value->url_category ?>/horizontal/<?php echo $hs_slider['IMG tag'] ?>">
                    <img src="img/products/<?php echo $value->url_category ?>/horizontal/<?php echo $hs_slider['IMG tag'] ?>" alt="<?php echo $value->name_product ?>">
                    <div class="ps-banner__content">
                        <h4><?php echo $hs_slider['H4 tag']; ?></h4>
                        <h3><?php echo $hs_slider['H3-1 tag']; ?> <br />
                            <?php echo $hs_slider['H3-2 tag']; ?> <br />
                            <p><?php echo $hs_slider['H3-3 tag']; ?> <strong> <?php echo $hs_slider['H3-4s tag'] ?></strong></p>
                        </h3>
                        <a class="ps-btn" href="<?php echo $path . $value->url_category ?>"><?php echo $hs_slider['Button tag']; ?></a>
                    </div>
                </div>
            <?php endif ?>

        <?php endforeach ?>
    </div>

</div><!-- End Home Banner-->